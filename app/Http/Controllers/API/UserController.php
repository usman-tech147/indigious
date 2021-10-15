<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\VdoCipherController;
use App\Mail\ContactUs;
use App\Models\BeltingEvaluation;
use App\Models\ManualSubscriptionRequest;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use App\Models\Video;
use App\Models\WebsiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SK')
        );
        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
    }
    public function signIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if($user!=null){
            if ($user->verified == 1) {
                if (Auth::guard('user')->attempt(['email' => strtolower($request->input('email')), 'password' => $request->input('password')])) {
                    $token=$user->createToken($request->email)->accessToken;
                    return Response::json(['message'=>'Successfully logged in.',
                            'user'=>$user,
                            'token'=>$token
                        ]);

                } else {
                    return Response::json(['message'=>'Login failed Username or Password incorrect.'],422);
                }
            } else {
                return Response::json(['message'=>'Please verify your account first an email has been sent to you!'],422);
            }
        } else {
            return Response::json(['message'=>'Login failed Username or Password incorrect!'],422);
        }
    }
    public function getDetails(Request $request){
        $user=User::find($request->user()->id);
        return Response::json([
            'user'=>$user
        ]);
    }
    public function signUp(Request $request){
        $request->validate([
            'name'=>['required','max:50'],
            'email'=>['required','email','unique:users'],
            'institution'=>['required','max:'.env('INPUT_SMALL')],
            'phone_no'=>['required','digits:11'],
            'password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirm_password'=>['required','same:password'],
        ]);
        $user=new User([
            'name'=>$request->input('name'),
            'email'=>strtolower($request->input('email')),
            'institution'=>$request->input('institution'),
            'phone_no'=>$request->input('phone_no'),
            'password'=>Hash::make($request->input('password')),
        ]);
        if($user->save()) {

            $token=sha1(uniqid().time().date('d-m-y'));
            $user->token=$token;
            $user->save();

            MailController::sendEmailVerification($token,$user->email);
        }
        return Response::json(['message'=>'Verification email has been sent to you!'],201);
    }
    public function forgotPassword(Request $request){
        $request->validate([
            'email'=>'required'
        ]);
        $user=User::where('email','like',$request->input('email'))->first();
        if($user!=null){
            $rand=rand(100000,999999);
            $user->verification_code=Hash::make($rand);
            $user->verification_code_time=new \DateTime();
            $user->save();
            MailController::sendForgotPasswordMail($user->email,$user->verification_code);
            return Response::json(['message'=>'Password reset link sent on your email it will expire within 5 minutes.'],201);
        }
        else{
            return Response::json(['message'=>'User doesn\'t exist try again!'],201);

        }
    }
    public function getAllPackages(Request $request){
            $page=0;
            $limit=Package::all()->count();
            if($request->get('limit')){
                $limit=$request->get('limit');
            }
            if($request->get('page')){
                $page=($request->get('page')-1)*$limit;
            }
            $packagesQ=Package::where(function ($query){

            });
            $totalNumber=$packagesQ->count();
            $packages=$packagesQ->offset($page)->limit($limit)->get();
            $subPackages=$packages->map(function ($package){
                $package->subscribed=false;
                if(Auth::guard('user_api')->check()){
                    $user=User::find(Auth::guard('user_api')->user()->id);
                    $userPackage=$user->packages->where('id',$package->id)->first();
                    if($userPackage && $userPackage->pivot && $userPackage->pivot->subscribed_status!='Expired'){
                        $package->subscribed=true;
                    }
                }
                return $package;
            });
        return Response::json([
            'package'=>$subPackages,
            'filtered'=>$subPackages->count(),
            'total'=>$totalNumber
        ]);
    }
    public function contactUs(Request $request){
        $request->validate([
            'name' => 'required',
            'email'=>'required',
            'phone_no'=>'required|digits:11',
            'subject'=>'required',
            'message'=>'required',
        ]);
        try {
            Mail::to(WebsiteSettings::first()->contact_email)->send(new ContactUs($request->except(['_token'])));
            return Response::json([
                'message'=>"Message sent we will contact you shortly."
            ]);

        }catch (\Exception $ex){
            return Response::json([
                'message'=>"Some error has occurred."
            ]);
        }
    }
    public function contact(){
        $web=WebsiteSettings::first();
        return Response::json([
           'website_detail'=>$web->makeHidden(['stripe_api_pk','stripe_api_sk','paypal_email','favicon','id','created_at','updated_at'])
        ]);
    }
    public function getPackageById($id){
        $package=Package::with(['categories','categories.subCategories.videos'])
        ->where('id',$id)->get();
        $subPackage=$package->map(function ($pkg){
            $pkg->subscribed=false;
            if(Auth::guard('user_api')->check()){
                $user=User::find(Auth::guard('user_api')->user()->id);
                $userPackage=$user->packages->where('id',$pkg->id)->first();
                if($userPackage && $userPackage->pivot && $userPackage->pivot->subscribed_status!='Expired'){
                    $pkg->subscribed=true;
                }
            }
            return $pkg;
        });
        return Response::json([
           'package'=>$package->first()
        ]);
    }
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return Response::json($response, 200);
    }
    public function updateProfile(Request $request){
        $request->validate([
            'name'=>['required','max:50'],
            'institution'=>['required','max:100'],
            'phone_no'=>['required','digits:11'],
        ]);

        $user=User::find($request->user()->id);
        if(!$user){
            return Response::json("User not found!", 422);
        }
        $user->name=$request->input('name');
        $user->institution=$request->input('institution');
        $user->phone_no=$request->input('phone_no');
        $user->save();
        return Response::json(['message'=>"Profile Updated!"], 200);
    }
    public function subscribedPackages(Request $request){
        $subscribedPackages=$request->user()->packages->where('pivot.subscribed_status','!=','Expired');
        $subscribedPackages->map(function ($package) {
            if(($package->pivot->payment_by=='Stripe' || $package->pivot->payment_by=='PayPal' ) && $package->pivot->subscribed_status!='Expired') {
                if($package->pivot->frequency=='YEAR' && $package->pivot->interval_count==1){
                    $package->pivot->valid_for="1 Year";
                }
                if($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==1){
                    $package->pivot->valid_for="1 Month";
                }
                if($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==6){
                    $package->pivot->valid_for="6 Months";
                }
            }
            if($package->pivot->payment_by=='Manual' && $package->pivot->subscribed_status!='Expired') {
                $sDate=new \Carbon\Carbon($package->pivot->subscribed_at);
                $date=new \Carbon\Carbon($package->pivot->expired_at);
                $diff=$date->diffAsCarbonInterval($sDate);
                $y=0;
                $m=0;
                $d=0;
                if($diff->format('%y')>0){
                    $t='';
                    if($diff->format('%y')>1){
                        $t='s';
                    }
                    $y= $diff->format('%y')." Year$t ";
                }
                if($diff->format('%m')>0){
                    $t='';
                    if($diff->format('%m')>1){
                        $t='s';
                    }
                    $m= $diff->format('%m')." Month$t ";
                }
                if($diff->format('%d')>0){
                    $t='';
                    if($diff->format('%d')>1){
                        $t='s';
                    }
                    $d= $diff->format('%d')." Day$t";
                }
                $dateTemp="";
                if($y){
                    $dateTemp.=$y;
                }
                if($m){
                    $dateTemp.=$m;
                }
                if($d){
                    $dateTemp.=$d;
                }
                $package->pivot->valid_for=$dateTemp;
            }
            });
            return Response::json([
           'packages'=>$subscribedPackages
        ]);
    }
    public function getSubscribedPackageById(Request $request,$id){
        $subscribedPackage=null;
        if($request->user()->packages){
            $subscribedPackage=$request->user()->packages->where('id',$id)->where('pivot.subscribed_status','!=','Expired')->first();
        }
        return Response::json([
           'package'=>$subscribedPackage->load('categories','categories.subCategory.videos')
        ]);
    }
    public function getVideoById(Request $request,$id){
        $video=Video::find($id);
        $packageCheck=false;
        if($video){
            $temp=$video->category->package->users;
            if($temp){
                $temp2=$temp->where('id',$request->user()->id)->first();
                if($temp2 && $temp2->pivot->subscribed_status!='Expired'){
                    $packageCheck=true;
                }
            }
        }
        $videoReturn=null;
        $videoOtp=null;
        if($packageCheck){
            $videoReturn=$video->load('subCategory.category.package');
            $responseObj=json_decode(VdoCipherController::getOtp($video->video_id));
            if(property_exists($responseObj,'error')){
                return  json_encode(['message'=>'Some error has occurred try again.'],422);
            }
            $videoOtp=[
              'otp'=>$responseObj->otp,
              'playbackInfo'=>$responseObj->playbackInfo,
            ];
        }

        return Response::json([
           'video'=>$videoReturn,
            'video_otp'=>$videoOtp
        ]);
    }
    public function submitBeltingRequest(Request $request){
        $request->validate([
            'name'=>['required','max:50'],
            'email'=>['required','email'],
            'phone_no'=>['required','digits:11'],
            'message'=>['required','max:500']
        ]);

        $beltingRequest=new BeltingEvaluation([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'phone_no'=>$request->input('phone_no'),
            'message'=>$request->input('message'),
            'user_id'=>$request->user()->id,
            'status'=>'Pending'
        ]);
        $beltingRequest->save();
        return Response::json(['message'=>'Your request has been sent to admin.']);
    }
    public function getBeltingRequest(Request $request){
        $limit=BeltingEvaluation::where('user_id',$request->user()->id)->count();
        $start=0;
        if($request->input('limit')){
            $limit = $request->input('limit');
        }
        if($request->input('page')){
            $start = ($request->input('page')-1)*$limit;
        }
        $beltingEvaluationsQuery=BeltingEvaluation::where('user_id',$request->user()->id)->where(function ($query) use ($request){
            if($request->input('status')){
                $query->where('status','like','%'.$request->input('status').'%');
            }
            if($request->input('name')){
                $query->where('name','like','%'.$request->input('name').'%');
            }
        });
        $totalData=$beltingEvaluationsQuery->count();
        $beltingEvaluations=$beltingEvaluationsQuery->limit($limit)
            ->offset($start)
            ->orderBy('id','desc')
            ->get();
        $totalFiltered = $beltingEvaluations->count();
        $data = array();
        if(!empty($beltingEvaluations))
        {
            $i=$start+1;
            foreach ($beltingEvaluations as $beltingEvaluation)
            {
                $nestedData['no'] = $i++;
                $nestedData['id'] = $beltingEvaluation->id;
                $nestedData['name'] = $beltingEvaluation->name;
                $nestedData['email'] =$beltingEvaluation->email;
                $nestedData['phone_no'] =$beltingEvaluation->phone_no;
                $nestedData['date'] =$beltingEvaluation->created_at->toDateTimeString();
                $nestedData['status'] =$beltingEvaluation->status;
                $nestedData['message'] =$beltingEvaluation->message;
                $data[] = $nestedData;
            }
        }
        $json=array(
            "total"=> intval($totalData),
            "filtered"=> intval($totalFiltered),
            "belting_requests"=>$data
        );
        return Response::json($json);
    }
    public function changePassword(Request $request){
        $request->validate([
            'password'=>['required','current_password_user_api'],
            'new_password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirm_new_password'=>['required','same:new_password']
        ]);
        $user=User::findOrFail($request->user()->id);
        $user->password=Hash::make($request->input('new_password'));
        $user->save();
        return Response::json(['message'=>'Password Successfully Changed']);
    }
    public function manageSubscribedPlan(Request $request){
        $subscribedPackages=$request->user()->packages;
        $subscribedPackages->map(function ($package){
            if($package->pivot->payment_by=='Stripe' && $package->pivot->subscribed_status!='Expired'){

                $pm=$this->stripe->paymentMethods->retrieve(
                    $package->pivot->payment_method,
                    []
                );
                $package->pivot->paid_amount=Payment::where('payment_id',$package->pivot->subscription_id)->first()->total_amount;

                $package->pivot->payment_using="/images/".$pm['card']['brand'].".png";

                if($package->pivot->renewal_status==0){
                    $package->pivot->auto_renew="on";
                }else{
                    $package->pivot->auto_renew="off";
                }
                if($package->pivot->frequency=='YEAR' && $package->pivot->interval_count==1){
                    $package->pivot->valid_for="1 Year";
                }
                if($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==1){
                    $package->pivot->valid_for="1 Month";
                }
                if($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==6){
                    $package->pivot->valid_for="6 Months";
                }
            }else if($package->pivot->payment_by=='PayPal' && $package->pivot->subscribed_status!='Expired'){
                $package->pivot->paid_amount=Payment::where('payment_id',$package->pivot->billing_agreement_id)->first()->total_amount;
                $package->pivot->payment_using="/images/paypal.png";

                if($package->pivot->renewal_status==1){
                    $package->pivot->auto_renew="on";
                }else{
                    $package->pivot->auto_renew="off";
                }
                if($package->pivot->subscribed_status=="Cancelled"){
                    $package->pivot->auto_renew="not available";
                }
                if($package->pivot->frequency=='YEAR' && $package->pivot->interval_count==1){
                    $package->pivot->valid_for="1 Year";
                }
                if($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==1){
                    $package->pivot->valid_for="1 Month";
                }
                if($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==6){
                    $package->pivot->valid_for="6 Months";
                }
            }else if($package->pivot->payment_by=='Manual' && $package->pivot->subscribed_status!='Expired'){
                if(Payment::where('payment_id',$package->pivot->subscription_id)->first()){

                    $package->pivot->paid_amount=Payment::where('payment_id',$package->pivot->subscription_id)->first()->total_amount;
                }else{
                    $package->pivot->paid_amount="Free";
                }
                $sDate=new \Carbon\Carbon($package->pivot->subscribed_at);
                $date=new \Carbon\Carbon($package->pivot->expired_at);
                $diff=$date->diffAsCarbonInterval($sDate);
                $y=0;
                $m=0;
                $d=0;
                if($diff->format('%y')>0){
                    $t='';
                    if($diff->format('%y')>1){
                        $t='s';
                    }
                    $y= $diff->format('%y')." Year$t ";
                }
                if($diff->format('%m')>0){
                    $t='';
                    if($diff->format('%m')>1){
                        $t='s';
                    }
                    $m= $diff->format('%m')." Month$t ";
                }
                if($diff->format('%d')>0){
                    $t='';
                    if($diff->format('%d')>1){
                        $t='s';
                    }
                    $d= $diff->format('%d')." Day$t";
                }
                $dateTemp="";
                if($y){
                    $dateTemp.=$y;
                }
                if($m){
                    $dateTemp.=$m;
                }
                if($d){
                    $dateTemp.=$d;
                }
                $package->pivot->valid_for=$dateTemp;
                $package->pivot->payment_using="Manual";
                $package->pivot->auto_renew="not available";
            }else{
                $package->pivot->valid_for="1 Year";
                $package->pivot->paid_amount=$package->price_year;

            }
            if($package->pivot->subscribed_status!='Expired'){
                $package->pivot->error_message=null;
            }
            return $package;
        });
        return Response::json([
            'packages'=>$subscribedPackages
        ]);
    }
    public function changeAutoRenewStatus(Request $request){
        $request->validate([
            'package_id'=>['required','exists:packages,id']
        ]);
        $user=User::find($request->user()->id);
        $msg='';
        $userPackage=$user->packages->find($request->input('package_id'));
        if($userPackage->pivot->payment_by=='Stripe' && $userPackage->pivot->status!='Expired'){
            $subscription = \Stripe\Subscription::retrieve($userPackage->pivot->subscription_id);
            $status=$userPackage->pivot->renewal_status;
            $userPackage->pivot->renewal_status=!$status;
            if($status==1){
                $msg='Auto Renewal Turned On.';
                $turnOff=false;
            }else{
                $msg='Auto Renewal Turned Off.';
                $turnOff=true;
            }
            \Stripe\Subscription::update($userPackage->pivot->subscription_id, [
                'cancel_at_period_end' => $turnOff,
            ]);
            $userPackage->pivot->save();
        }else if($userPackage->pivot->payment_by=='PayPal' && $userPackage->pivot->status!='Expired' && $userPackage->pivot->status!='Cancelled'){
            $status=$userPackage->pivot->renewal_status;
            $userPackage->pivot->renewal_status=!$status;
            if($status==1){
                $msg='Auto Renewal Turned Off.';
                PayPalController::suspendSubscription($userPackage->pivot->billing_agreement_id,'User wanted to suspend subscription');
            }else{
                PayPalController::activateSubscription($userPackage->pivot->billing_agreement_id,'User wanted to activate subscription');
                $msg='Auto Renewal Turned On.';
            }
            $userPackage->pivot->save();
        }
        return Response::json([
           'message'=>$msg
        ]);

    }
    public function getSubscriptionRequests(Request $request){
        $manualSubscriptionRequest=ManualSubscriptionRequest::where('user_id',$request->user()->id)->get();
        return Response::json([
            'subscription_requests'=>$manualSubscriptionRequest
        ]);
    }
    public function getAccessPassword(Request $request){
        $accessPasswords=$request->user()->packages->where('pivot.subscribed_status','!=','Expired');
        $accessPasswordsTemp=[];
        foreach ($accessPasswords as $accessPassword){
            $accessPasswordsTemp['package_id']=$accessPassword->id;
            $accessPasswordsTemp['pivot_id']=$accessPassword->pivot->id;
            $accessPasswordsTemp['username']=env('APP_URL').'/'.$accessPassword->pivot->username;
        }
        return Response::json([
            'access_passwords'=>$accessPasswordsTemp
        ]);
    }
    public function changeAccessPassword(Request $request,$package_id){
        $user=User::findOrFail($request->user('user_api')->id);
        $package=$user->packages->where('id',$package_id)->first();
        $pivot=$package->pivot;
        if(Hash::check($request->input('password'),$pivot->password)){
            if($request->input('new_password')==$request->input('confirm_password')) {
                if(preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',$request->input('new_password'))){
                    $pivot->password=Hash::make($request->input('new_password'));
                    $pivot->save();
                    return Response::json([
                        'message'=>"Password Successfully Changed."
                    ]);
                }else{
                    return Response::json([
                        'message'=>'Password should have minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.'
                    ],422);
                }
            }
            else{
                return Response::json([
                    'message'=>'Password and confirm password does not match.'
                ],422);
            }
        }
        else{
            return Response::json([
                'message'=>'Current password is incorrect.'
            ],422);
        }
    }
}
