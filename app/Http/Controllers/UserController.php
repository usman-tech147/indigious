<?php

namespace App\Http\Controllers;

use App\Models\BeltingEvaluation;
use App\Models\ManualSubscriptionRequest;
use App\Models\Package;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{

    public function index()
    {
        $user=User::findOrFail(\auth()->guard('user')->user()->id);
        return view('users.index',compact('user'));
    }

    public function accessPassword()
    {
        $user=User::with('packages')->where('id',\auth()->guard('user')->user()->id)->first();
        $userPackages=$user->packages->filter(function($value){
            if($value->pivot->subscribed_status!='Expired' && $value->pivot->subscribed_status!='Past_due'){
                return $value;
            }
        });
        return view('users.access-password',compact('user','userPackages'));
    }

    public function beltingRequest()
    {
        return view('users.belting-request');
    }

    public function subscribedPackageDetails($id)
    {

        $user=User::findOrFail(\auth()->guard('user')->user()->id);
        $package= Package::whereHas('users', function($q) use ($user) {$q->where('subscribed_status','Not Like','Expired');$q->where('subscribed_status','Not Like','Past_due'); $q->where('user_id',$user->id);})->where('id',$id)->first();
        if($package==null) {
            abort(404);
        }
//            if( $user->packages->where('id',$id)->first()->pivot->where('subscribed_status','Not Like','Expired')->first()==null){
//            abort(404);
//        }
        $categories=$package->categories;

        return view('users.subscribed-package', compact('categories','package'));
    }

    public function changePassword()
    {
        return view('users.change-password');
    }

    public function accessVideo()
    {
        return view('users.access-video');
    }

    public function videoDetails()
    {
        return view('users.video-details');
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

                    return redirect()->route('user.all-subscribed-packages');
                } else {
                    return redirect()->back()->with('danger', 'Login failed Username or Password incorrect');
                }
            } else {
                return redirect()->back()->with('danger', 'Please verify your account first an email has been sent to you!');

            }
        } else {
            return redirect()->back()->with('danger', 'Login failed Username or Password incorrect!');

        }
    }
    public function logout(Request $request){

        if(\auth()->guard('user')->check()){
            Auth::logout();
            return redirect()->route('home');
        }
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
        return redirect()->route('sign-in')->with('success','Verification email has been sent to you!');
    }
    public function verifyEmailWithLink($token,$email){

        $user=User::where('token',$token)->where('email',$email)->first();
        if($user==null){
            return redirect()->route('sign-in')->with('danger','Link is broken or invalid.');
        }
        else if($user!=null && $user->verified==0){
            $user->verified=1;
            \Stripe\Stripe::setApiKey(env('STRIPE_SK'));

            $customer = \Stripe\Customer::create([
                'email' => $user->email,
            ]);
            $user->customer_id=$customer->id;
            $user->save();
            return redirect()->route('sign-in')->with('success','Email has been verified you can login now.');

        }
        else if($user!=null && $user->verified==1){

            return redirect()->route('sign-in')->with('danger','Link is broken or invalid.');

        }

    }

    public function updateProfile(Request $request){
        $request->validate([
            'name'=>['required','max:50'],
            'institution'=>['required','max:100'],
            'phone_no'=>['required','digits:11'],
        ]);

        $user=User::findOrFail(\auth()->guard('user')->user()->id);
        $user->name=$request->input('name');
        $user->institution=$request->input('institution');
        $user->phone_no=$request->input('phone_no');
        $user->save();
        return redirect()->back()->with('success','Profile Updated!');
    }
    public function updateProfilePicture(Request $request){
        $request->validate([
            'profile_picture'=>['required','mimes:jpg,jpeg,png'],
        ]);

        $user=User::findOrFail(\auth()->guard('user')->user()->id);
        if($request->hasFile('profile_picture')) {
            $profile_picture=$request->file('profile_picture');
            $profile_picture_name=time().$profile_picture->getClientOriginalName();
            $profile_picture->move(public_path('uploads/profile-pictures'),$profile_picture_name);
            if($user->profile_picture!=''){
                unlink(public_path('uploads/profile-pictures/'.$user->profile_picture));
            }
            $user->profile_picture=$profile_picture_name;
            $user->save();
        }
        return Response::json(['success'=>'Profile Updated!']);
    }
    public function deleteProfilePicture(Request $request){

        $user=User::findOrFail(\auth()->guard('user')->user()->id);
        if($user->profile_picture!=''){

            if(file_exists(public_path('uploads/profile-pictures/'.$user->profile_picture))) {

                unlink(public_path('uploads/profile-pictures/'.$user->profile_picture));
            }
            $user->profile_picture='';
            $user->save();
        }
        return Response::json(['success'=>'Profile Picture Deleted!']);
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
            'user_id'=>\auth()->guard('user')->user()->id,
            'status'=>'Pending'
        ]);
        $beltingRequest->save();
        return redirect()->back()->with('success','Your request has been sent to admin.');
    }
    public function submitChangePassword(Request $request){
        $request->validate([
            'password'=>['required','current_password_user'],
            'new_password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirm_new_password'=>['required','same:new_password']

        ]);
        $user=User::findOrFail(\auth()->guard('user')->user()->id);
        $user->password=Hash::make($request->input('new_password'));
        $user->save();
        return redirect()->back()->with('success','Password Successfully Changed');
    }
    public function getVideo(Request $request,$id){
        //    playback otp


        $video=Video::findOrFail($id);

        if($video->category->package->users->where('id', \auth()->guard('user')->user()->id)->first()==null) {
            return Response::json(array(
                'response' => [
                    ['otp'=> 'invalid',
                        'playbackInfo'=>'invalid']
                ],
            ));
        }
        if($video->category->package->users->where('id', \auth()->guard('user')->user()->id)->first()->id != \auth()->guard('user')->user()->id) {
            return Response::json(array(
                'response' => [
                    ['otp'=> 'invalid',
                        'playbackInfo'=>'invalid']
                ],
            ));
        }
        $responseObj=json_decode(VdoCipherController::getOtp($video->video_id));
        if(property_exists($responseObj,'error')){
            return  json_encode(['error'=>'Error']);
        }
        $data['otp']= $responseObj->otp;
        $data['playbackInfo']= $responseObj->playbackInfo;
        $responses[]=$data;
        $responsesCollection=collect($responses);

        return Response::json(array(
            'response' => $responsesCollection,
        ));
    }

    public function getVideoData(Request $request,$id){
        //    playback otp
        $video=Video::findOrFail($id);
        $this->checkAuthUserForVideo($video);

        $responseObj=json_decode(VdoCipherController::getOtp($video->video_id));
        if(property_exists($responseObj,'error')){
            return  json_encode(['error'=>'Error']);
        }
        $data['otp']= $responseObj->otp;
        $data['playbackInfo']= $responseObj->playbackInfo;
        $responses[]=$data;
        $responsesCollection=collect($responses);


        return view('users.video-details',compact('responsesCollection','video'));
    }

    public function getAllBeltingRequests(Request $request){
        $columns = array(
            0   =>'id',
            1   =>'name',
            2   =>'email',
            3   =>'status',
            4   =>'action'
        );
        $limit = $request->input('length');
        $start = $request->input('start');

            $beltingEvaluationsQuery=BeltingEvaluation::where('user_id',auth()->guard('user')->user()->id)->where(function ($query) use ($request){
                if($request->input('status')){
                    $query->where('status','like','%'.$request->input('status').'%');
                }
                if($request->input('name')){
                    $query->where('name','like','%'.$request->input('name').'%');
                }
            });
        $totalData=$beltingEvaluationsQuery->count();
        $totalFiltered = $totalData;

        $beltingEvaluations=$beltingEvaluationsQuery->limit($limit)
            ->offset($start)
            ->orderBy('id','desc')
            ->get();

        $data = array();
        if(!empty($beltingEvaluations))
        {
            $i=$start+1;
            foreach ($beltingEvaluations as $beltingEvaluation)
            {
                $route=route('user.belting-request-detail',$beltingEvaluation->id);
                $nestedData['no'] = $i++;
                $nestedData['id'] = $beltingEvaluation->id;
                $nestedData['name'] = $beltingEvaluation->name;
                $nestedData['email'] =$beltingEvaluation->email;
                $nestedData['phone_no'] =$beltingEvaluation->phone_no;
                $nestedData['date'] =$beltingEvaluation->created_at->toDateTimeString();
                $nestedData['status'] =$beltingEvaluation->status;
                $nestedData['message'] =$beltingEvaluation->message;
                $nestedData['action']="<a href='$route' class='tags-btn'>View Details</a>";
                $data[] = $nestedData;
            }
        }

        $json=array(
            "draw"=>intval(\request('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=>$data
        );
        return Response::json($json);
    }

    public function getAllRequestData(){
        return view('users.all-belting-request');
    }
    public function changeAccessPassword(Request $request,$package_id,$pivot_id){

        $user=User::findOrFail(\auth()->guard('user')->user()->id);
        $package=$user->packages->where('id',$package_id)->first();
        $pivot=$package->pivot;
            if(Hash::check($request->input('password'),$pivot->password)){
                if($request->input('new_password')==$request->input('confirm_password')) {
                    if(preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',$request->input('new_password'))){
                    $pivot->password=Hash::make($request->input('new_password'));
                    $pivot->save();
                    return redirect()->back()->with('success','Password Changed.');
                }else{
                        return redirect()->back()->with('danger','Password should have minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.');
                    }
            }
            else{
                return redirect()->back()->with('danger','Password and confirm password does not match.');
                }
            }
            else{
                return redirect()->back()->with('danger','Current password is incorrect.');
            }
    }

    public function checkAuthUserForVideo($video){
        if($video->subCategory->category->package->users->where('id', \auth()->guard('user')->user()->id)->first()==null) {
            abort(404);
        }
        if($video->subCategory->category->package->users->where('id', \auth()->guard('user')->user()->id)->first()->id != \auth()->guard('user')->user()->id) {
            abort(404);
        }
    }

    public function turnOffAutoRenewal(Request $request){
        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
        $user=User::find(\auth()->guard('user')->user()->id);
        $userPackage=$user->packages->find($request->input('package_id'));
        $subscription = \Stripe\Subscription::retrieve($userPackage->pivot->subscription_id);
        $userPackage->pivot->renewal_status=1;
        \Stripe\Subscription::update($userPackage->pivot->subscription_id, [
            'cancel_at_period_end' => true,
        ]);
        $userPackage->pivot->save();
        return redirect()->back()->with('success','Auto Renewal Turned Off');
    }
    public function turnOnAutoRenewal(Request $request){
        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
        $user=User::find(\auth()->guard('user')->user()->id);
        $userPackage=$user->packages->find($request->input('package_id'));
        $subscription = \Stripe\Subscription::retrieve($userPackage->pivot->subscription_id);
        $userPackage->pivot->renewal_status=0;

        \Stripe\Subscription::update($userPackage->pivot->subscription_id, [
            'cancel_at_period_end' => false,
        ]);
        $userPackage->pivot->save();
        return redirect()->back()->with('success','Auto Renewal Turned On');

    }
    public function cancelSubscription(Request $request){

        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
        $user=User::find(\auth()->guard('user')->user()->id);
        $userPackage=$user->packages->find($request->input('package_id'));
        $subscription = \Stripe\Subscription::retrieve($userPackage->pivot->subscription_id);
        $userPackage->pivot->renewal_status=1;
        $userPackage->pivot->save();
        $subscription->cancel();
        return redirect()->back()->with('success','Subscription Cancelled');

    }
    public function suspendSubscription(Request $request){

        $user=User::find(\auth()->guard('user')->user()->id);
        $userPackage=$user->packages->find($request->input('package_id'));
        $userPackage->pivot->renewal_status=false;
        $userPackage->pivot->save();

        PayPalController::suspendSubscription($userPackage->pivot->billing_agreement_id,'User wanted to suspend subscription');
        return redirect()->back()->with('success','Auto renewal turned off.');

    }
    public function reActivateSubscription(Request $request){
        $user=User::find(\auth()->guard('user')->user()->id);
        $userPackage=$user->packages->find($request->input('package_id'));
        $userPackage->pivot->renewal_status=true;
        $userPackage->pivot->save();
        PayPalController::activateSubscription($userPackage->pivot->billing_agreement_id,'User wanted to activate subscription');

        return redirect()->back()->with('success','Auto renewal turned on.');
    }
    public function cancelSubscriptionPaypal(Request $request){
        $user=User::find(\auth()->guard('user')->user()->id);
        $userPackage=$user->packages->find($request->input('package_id'));
        PayPalController::cancelSubscription($userPackage->pivot->billing_agreement_id,'User wanted to suspend subscription');

        return redirect()->back()->with('success','Subscription Cancelled.');
    }

    public function viewPlan(){
        $user=User::find(\auth()->guard('user')->user()->id);

        return view('users.view-plan',compact('user'));
    }
    public function updatePayment($package){

        $user=User::find(\auth()->guard('user')->user()->id);
        $package=$user->packages->where('id',$package)->first();
        $pm=$package->pivot->payment_method;
        \Stripe\Stripe::setApiKey(env("STRIPE_SK"));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'setup',
            'customer' => $user->customer_id,
            'setup_intent_data' => [
                'metadata' => [
                    'customer_id' => $user->customer_id,
                    'subscription_id' => $package->pivot->subscription_id,
                ],
            ],
            'success_url' => env('APP_URL').'/user/manage-plan',
            'cancel_url' => env('APP_URL').'/user/manage-plan',
        ]);
        return Response::json(['id'=>$session->id]);
    }
    public function getBeltingRequestDetail($id){
        $beltingRequest=BeltingEvaluation::findOrFail($id);
        return view('users.belting-request-detail',compact('beltingRequest'));
    }
    public function allSubscribedPackages(){
        $user= User::findOrFail(\auth()->guard('user')->user()->id);
        $packages= Package::whereHas('users', function($q) use ($user) 
        {
            $q->where('subscribed_status','Not Like','Expired');
            $q->where('subscribed_status','Not Like','Past_due'); 
            $q->where('user_id',$user->id);
        })->get();
        dd($packages->toArray());
        return view('users.all-subscribed-packages',compact('packages','user'));
    }
    public function getSubscriptionRequest(){
        return view('users.subscription-requests');
    }
    public function getSubscriptionRequestList(Request $request){


        $totalData=ManualSubscriptionRequest::where('user_id',Auth::guard('user')->id())->count();
        $totalFiltered=$totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $subs=ManualSubscriptionRequest::where('user_id',Auth::guard('user')->id())->limit($limit)->offset($start)->with('package')->get();

        $data = array();
        if(!empty($subs))
        {
            $i=1;
            foreach ($subs as $sub)
            {
                $nestedData['no'] = $i++;
                $nestedData['package_id'] = $sub->package_id;
                $nestedData['user_id'] = $sub->user_id;
                $nestedData['package_name'] =$sub->package->name;
                $nestedData['user_name'] =$sub->user->name;
                $nestedData['status'] =$sub->status;
                $data[] = $nestedData;
            }
        }
        $json=array(
            "draw"=>intval(\request('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=>$data
        );
        return Response::json($json);
    }

}
