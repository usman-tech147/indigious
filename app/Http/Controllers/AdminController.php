<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\BeltingEvaluation;
use App\Models\Category;
use App\Models\ManualSubscriptionRequest;
use App\Models\Package;
use App\Models\Payment;
use App\Models\SubCategory;
use App\Models\Video;
use App\Models\WebsiteSettings;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use function Clue\StreamFilter\fun;

class AdminController extends Controller
{
    public function getUserDetails($id){
            $user=User::findOrFail($id);
            return view('admin.user-detail',compact('user'));
    }
    public function updatePosition(Request $request){
            $positions=$request->input('positions');
                $videos=Video::all();
            foreach ($positions as $position){
                $video=$videos->where('id',$position[0])->first();
                $video->position=$position[1];
                $video->save();
            }
    }
    public function index(){
        $packages=Package::all();
        $videos=Video::all();
        $users=User::whereHas('packages', function($query){
            return $query->where('subscribed_status','not like','Expired');
        });
        return view('admin.index',compact('packages','videos','users'));
    }
    public function beltingEvolutionRequests(){
        return view('admin.belting-evolution-requests');
    }
    public function adminProfile(){
        return view('admin.admin-profile');
    }
    public function createNewPackage(){
//        $products = PayPalController::getAllProducts();
//        $productDetails = PayPalController::getProductDetails();
//        dd($productDetails);
        return view('admin.create-new-package');
    }
    public function login(){

        return view('admin.login');
    }
    public function forgotPassword(){

        return view('admin.forgot-password');
    }
    public function newVideoUpload($package_id=null){

        $selectedPackage=Package::where('id',$package_id)->first();
        $packages=Package::all();
        return view('admin.new-video-upload',compact('packages','selectedPackage'));
    }
    public function packageList(){

        return view('admin.package-list');
    }
    public function userEdit(){

        return view('admin.user-edit');
    }
    public function users(){

        return view('admin.users');
    }
    public function videosList(Request $request){

        $packages=Package::all();
        return view('admin.videos-list',compact('packages'));

    }
    public function getAllPackages(){
        $packages=Package::all();
        foreach ($packages as $package) {
            $data[] = array(
                'id' => $package->name,
                'text' => $package->name
            );
        }
        return Response::json($data,200);
    }
    public function getSelectedPackages(Request $request){
        $packages=Package::all();
        if($request->get('search')){
            $packages=Package::where('name','like','%'.$request->get('search').'%')->get();
        }
        foreach ($packages as $package) {
            $data[] = array(
                'id' => $package->id,
                'text' => $package->name
            );
        }
        return Response::json($data,200);
    }

    public function getPackageCategoriesId(Request $request){
        $data=[];
        $categories=[];
        $package=null;
        if($request->get('name')) {
            $package = Package::findOrFail($request->get('name'));
        }
            $categories=Category::where(function($query) use ($request,$package){
            if($request->get('search')){
                $query->where('name','like','%'.$request->get('search').'%');
            }
            if($package){
                $query->where('package_id',$package->id);
            }
        })->get();
        foreach ($categories as $category) {
            $data[] = array(
                'id' => $category->id,
                'text' => $category->name
            );
        }
        return Response::json($data,200);
    }
    public function getPackageCategoriesSubCategoriesId(Request $request){
        $data=[];
        $categories=[];
        $package=null;
        if($request->get('name')) {
            $package = Category::findOrFail($request->get('name'));
        }
            $categories=SubCategory::where(function($query) use ($request,$package){
            if($request->get('search')){
                $query->where('name','like','%'.$request->get('search').'%');
            }
            if($package){
                $query->where('category_id',$package->id);
            }
        })->get();
        foreach ($categories as $category) {
            $data[] = array(
                'id' => $category->id,
                'text' => $category->name
            );
        }
        return Response::json($data,200);
    }
    public function getPackageCategories(Request $request){
        $data=[];
        $package=null;
        if($request->get('name')) {
            $package = Package::where('name', $request->get('name'))->first();
        }
            $categories=Category::where(function($query) use ($request,$package){
                if($request->get('search')){
                    $query->where('name','like','%'.$request->get('search').'%');
                }
                if($package){
                    $query->where('package_id',$package->id);
                }
            })->get();
        foreach ($categories as $category) {
            $data[] = array(
                'id' => $category->name,
                'text' => $category->name
            );
        }
                return Response::json($data,200);
    }
    public function getPackageCategorySubCategories(Request $request){
        $data=[];
        $package=null;
        if($request->get('name')) {
            $package = Category::where('name', $request->get('name'))->first();
        }
            $categories=SubCategory::where(function($query) use ($request,$package){
                if($request->get('search')){
                    $query->where('name','like','%'.$request->get('search').'%');
                }
                if($package){
                    $query->where('category_id',$package->id);
                }
            })->get();
        foreach ($categories as $category) {
            $data[] = array(
                'id' => $category->name,
                'text' => $category->name
            );
        }
                return Response::json($data,200);
    }
    public function getCategoryVideos(Request $request){

        $data=[];
        $category=null;
        if($request->get('name')) {
            $category = SubCategory::where('name','like', $request->get('name'))->first();
        }
        $videos=Video::where(function ($query) use ($request,$category){
            if($request->get('search')) {
                $query->where('name', 'like', '%' . $request->get('search') . '%');
            }
            if($category){
                $query->where('sub_category_id', $category->id);
            }

        })->get();
        foreach ($videos as $video) {
            $data[] = array(
                'id' => $video->title,
                'text' => $video->title
            );
        }
        return Response::json($data,200);
    }
    public function websiteList(){
        $websiteSettings=WebsiteSettings::first();
        return view('admin.website-settings',compact('websiteSettings'));
    }
    public function loginPost(Request $request){
        $request->validate([
           'email'=>'required|email',
           'password'=>'required',
        ]);

        if(Auth::guard('admin')->attempt(['email'=>$request->input('email'),'password'=>$request->input('password')],$request->input('check'))){

            return redirect()->route('admin');
        }
        else{
            return redirect()->back()->withInput(['email'])->with('danger','Login failed email or password is incorrect.');
        }

    }
    public function passwordReset(Request $request){
        $request->validate([
           'email'=>'required'
        ]);
        $admin=Admin::where('email','like',$request->input('email'))->first();
        if($admin!=null){
            $rand=rand(100000,999999);
            $admin->verification_code=Hash::make($rand);
            $admin->verification_code_time=new \DateTime();
            $admin->save();
            MailController::sendForgotPasswordMailAdmin($admin->email,$admin->verification_code);
            return redirect()->route('admin.sign-in')->with('success','Password reset link sent on your email it will expire within 5 minutes.');

        }
        else{
            return redirect()->route('admin.forgot-password')->with('danger','User doesn\'t exist try again!');

        }
    }
    public function logout(){
        if(Auth::guard('admin')->check()){
            Auth::logout();
            return redirect()->route('home');
        }
    }
    public function verifyForgotPasswordEmail(Request $request,$email){
        $code=$request->get('token');

        $admin=Admin::where('email',$email)
            ->where('verification_code',$code)->first();

        if($admin==null){
            return redirect()->route('admin.forgot-password')->with('danger','The link you are trying to access is invalid or broken!');
        }
        else{
            $currentTime=new Carbon(new \DateTime());

            $diff=$currentTime->diffInRealMinutes($admin->verification_code_time);
            if($diff>=5){
                return redirect()->route('admin.forgot-password')->with('danger','The link you trying to access is expired please try to get another one.');
            }
              else{
                  return view('admin.password-reset',[
                      'email'=>$email,'code'=>$code
                  ])->with('success','Change Your Password!');
              }
        }
    }
    public function passwordResetPage(Request $request,$email){
        $request->validate([
           'password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
           'confirm_password'=>'same:password'
        ]);
        $code=$request->get('token');
        $admin=Admin::where('email',$email)
        ->where('verification_code',$code)->first();
        if($admin==null){
            return redirect()->route('admin.forgot-password')->with('danger','The link you are trying to access is invalid or broken!');
        }
        else{
            $currentTime=new Carbon(new \DateTime());
            $diff=$currentTime->diffInRealMinutes($admin->verification_code_time);
            if($diff>=5){
                return redirect()->back()->with('danger','Link expired try to get another one.');

            }
            else{
                $admin->password=Hash::make($request->input('password'));
                $admin->verification_code=null;
                $admin->verification_code_time=null;
                $admin->save();
                return redirect()->route('admin.sign-in')->with('success','Password successfully changed!');
            }
        }
    }
    public function getAllUsers(Request $request){

        $columns = array(
            0   =>'id',
            1   =>'name',
            2   =>'email',
            3   =>'packages',
            4   =>'phone_no',
            5   =>'institution',
            6   =>'action'
        );
        $users=User::all();


        $limit = $request->input('length');
        $start = $request->input('start');
//        $order = $columns[$request->input('order.0.column')];
//        $dir = $request->input('order.0.dir');
        $name=$request->input('name');
        $institution=$request->input('institution');
        $package_name=$request->input('package_name');
        $usersQuery=User::where(function($query) use ($name,$institution,$package_name){
            if($name){
                $query->where('name','like','%'.$name.'%');
            }
            if($institution){
                $query->where('institution','like','%'.$institution.'%');
            }
            if($package_name){
                $query->whereHas('packages', function ($query) use ($package_name) {
                    $query->where('name', 'like', '%'.$package_name.'%');
                });
            }

        });
        $totalData=$usersQuery->count();
            $totalFiltered = $totalData;
        $users=$usersQuery
            ->limit($limit)
            ->offset($start)
            ->get();

        $data = array();
        if(!empty($users)) {
                    $i=1;
                foreach ($users as $user) {

                        $edit = "/admin/edit-user/" . $user->id;
                        $delete = "/admin/delete-user/" . $user->id;
                        $view = "/admin/user-details/" . $user->id;
                        if($user->is_blocked==true){
                         $block='<a href="#" data-id="' . $user->id . '" data-block="' . $user->is_blocked . '" class="tag btn-block-user">Unblock User</a>';
                        }
                         else{
                        $block='<a href="javascript:void(0)" data-id="' . $user->id . '" data-block="' . $user->is_blocked . '" class="tag btn-block-user">Block User</a>';
                         }
                    $packages = $user->packages;
                        $packageData = '';
                        foreach ($packages as $package) {
//
                         if($package->pivot->subscribed_status!='Expired'){
                             $sub=new Carbon($package->pivot->subscribed_at);
                             $exp=new Carbon($package->pivot->expired_at);
                             $packageData = $packageData . '<a href="'.route('admin.package-detail',[$package->id]).'" data-package_name="'.$package->name.'"  data-username="'.$package->pivot->username.'" data-password="'.$package->pivot->password.'" data-subscribed_at="'.$sub->format('d-M-Y').'" data-expired_at="'.$exp->format('d-M-Y').'" class="tag">' . $package->name . '</a>';
                         }
                        }
                        $nestedData['no'] = $i++;
                        $nestedData['id'] = $user->id;
                        $nestedData['name'] = $user->name;
                        $nestedData['email'] = $user->email;
                        $nestedData['packages'] = $packageData;
                        $nestedData['phone_no'] = $user->phone_no;
                        $nestedData['institution'] = $user->institution;
                        $nestedData['action'] = '<a href=' . $view . ' class="tag">View</a><a href=' . $edit . ' class="tag">Edit</a> <a href=' . $delete . ' data-id=' . $user->id . ' class="tag btn-delete-user">Delete</a>'.$block;
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
    public function editUser($id){
        $user=User::findOrFail($id);
        return view('admin.user-edit',compact('user'));
    }
    public function updateUser(Request $request,$id){
        $request->validate([
            'name'=>'required|max:'.env('INPUT_SMALL'),
            'email'=>'required',
            'phone_no'=>'required|digits:11',
            'institution'=>'required|max:'.env('INPUT_BIG')
        ]);
        $user=User::findOrFail($id);
        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->phone_no=$request->input('phone_no');
        $user->institution=$request->input('institution');

        $user->save();
        return redirect()->back()->with('success','User details updated successfully');
    }
    public function deleteUser($id){
        $user=User::findOrFail($id);
            if($user->delete()){
                try {
                    unlink(public_path('/uploads/profile-pictures/'.$user->profile_picture));

                }catch (\Exception $ex){

                }
            }

    }
    public function deleteUserPackage($user_id,$package_id){
        $user=User::findOrFail($user_id);
//        unlink(public_path('/uploads/package/'.$package->thumbnail));
        $user->packages()->detach($package_id);
    }
    public function websiteSettings(Request $request){
        $websiteSettings=WebsiteSettings::first();
        $rules=[
            'facebook'=>['required_if:facebook_check,1'],
            'instagram'=>['required_if:instagram_check,1'],
            'linkedin'=>['required_if:linkedin_check,1'],
            'twitter'=>['required_if:twitter_check,1'],
            'favicon'=>['mimes:jpg,jpeg,png']

        ];
        $message=[
            'required_if'=>':attribute url is required if checkbox is checked',
        ];
            $this->validate($request,$rules,$message);
        $websiteSettings->fill($request->except('favicon'))->save();
        if($request->hasFile('favicon')){
            $favicon=$request->file('favicon');
            $imageName=$favicon->getClientOriginalName();
            try {
                unlink(public_path('uploads/favicon/'.$websiteSettings->favicon));
            }catch (\Exception $ex){

            }
            $favicon->move(public_path('uploads/favicon'),$imageName);
            $websiteSettings->favicon=$imageName;
            $websiteSettings->save();
        }
            return redirect()->back()->with('success','Website settings successfully updated');
    }

    public function adminProfileUpdate(Request $request){
        $request->validate([
            'current_password'=>'required|current_password',
            'new_password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirm_new_password'=>'same:new_password'
        ]);
        $admin=Admin::findOrFail(\auth()->guard('admin')->user()->id);
        $admin->password=Hash::make($request->input('new_password'));
        $admin->save();
        return redirect()->back()->with('success','Password changed');

    }
    public function getAllBeltingRequests(Request $request){
        $columns = array(
            0   =>'id',
            1   =>'name',
            2   =>'email',
            3   =>'phone',
            4   =>'created_at',
            5   =>'status',
            6   =>'message',
            7   =>'action'
        );
        $beltingEvaluations=BeltingEvaluation::all();
//        $totalData=$beltingEvaluations->count();
        $name=$request->input('name');
        $status=$request->input('status');
        $limit = $request->input('length');
        $start = $request->input('start');
        $beltingEvaluationsQuery=BeltingEvaluation::where(function($query) use ($name,$status){
            if($name){
                $query->where('name','like','%'.$name.'%');
            }
            if($status){
                $query->where('status','like','%'.$status.'%');
            }
        });
            $totalData = $beltingEvaluationsQuery->count();
            $totalFiltered=$totalData;
        $beltingEvaluations=$beltingEvaluationsQuery->
        limit($limit)
            ->offset($start)
            ->get();

        $data = array();
        if(!empty($beltingEvaluations))
        {
            $i=1;
            foreach ($beltingEvaluations as $beltingEvaluation)
            {
                if($beltingEvaluation->status=='Pending'){
                    $a='<option value="">Select Status</option><option value="Approved">Approved</option><option value="Rejected">Rejected</option>';
                }
                if($beltingEvaluation->status=='Approved'){
                    $a='<option value="">Select Status</option><option value="Pending">Pending</option><option value="Rejected">Rejected</option>';
                }
                if($beltingEvaluation->status=='Rejected'){
                    $a='<option value="">Select Status</option><option value="Pending">Pending</option><option value="Approved">Approved</option>';
                }


                $nestedData['no'] = $i++;
                $nestedData['id'] = $beltingEvaluation->id;
                $nestedData['name'] = $beltingEvaluation->name;
                $nestedData['email'] =$beltingEvaluation->email;
                $nestedData['phone_no'] =$beltingEvaluation->phone_no;
                $nestedData['date'] =$beltingEvaluation->created_at->format('d-M-Y');
                $nestedData['status'] =$beltingEvaluation->status;
                $nestedData['message'] =$beltingEvaluation->message;
                $nestedData['action']='<select class="tag change-status" data-id="'.$beltingEvaluation->id.'">'.$a.'</select>';
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
    public function updateBeltingStatus(Request $request)
    {
        $request->validate([
            'status' => 'required'
        ]);
        $beltingRequest = BeltingEvaluation::findOrFail($request->input('belting_id'));
            if ($request->input('status')=='Pending' || $request->input('status')=='Approved' || $request->input('status')=='Rejected'){
            $beltingRequest->status = $request->input('status');
             }
            $beltingRequest->save();
    }
    public function viewPayments(){
        return view('admin.payments');
    }
    public function getAllPayments(Request $request){

//            $columns = array(
//                0   =>'id',
//                1   =>'name',
//                2   =>'email',
//                3   =>'packages',
//                4   =>'phone_no',
//                5   =>'institution',
//                6   =>'action'
//            );
            $payments=Payment::all();


            $limit = $request->input('length');
            $start = $request->input('start');
//        $order = $columns[$request->input('order.0.column')];
//        $dir = $request->input('order.0.dir');
                $paymentsQuery = Payment::where(function ($query) use ($request){
                    if($request->input('name')){
                        $query->whereHas('user', function ($query) use ($request) {
                            $query->where('name', 'like', '%'.$request->input('name').'%');
                        });
                    }

                });
        $totalData=$paymentsQuery->count();
            $totalFiltered = $totalData;
                $payments=$paymentsQuery->limit($limit)
                    ->orderBy('id','desc')
                    ->offset($start)
                    ->get();


            $data = array();
            if(!empty($payments)) {
                $i=1;
                foreach ($payments as $payment) {


                    $user = $payment->user;
                    if($payment->payment_by=='Stripe')
                    {
                        $package=$user->packages->where('pivot.subscription_id',$payment->payment_id)->first();
                        if(!empty($package)){

                            $subscribed_date=new \Carbon\Carbon($package->pivot->subscribed_at);
                        if($package->pivot->subscribed_status=='Active'){
                            $expired_date=new \Carbon\Carbon($package->pivot->expired_at);
                            $expired_date=$expired_date->format('d-M-Y');
                            if($package->pivot->renewal_status==1 && $package->pivot->subscribed_status=='Active'){
                                $expired_date='Auto Subscription Off';
                            }
                        }
                        else if($package->pivot->subscribed_status=='Expired'){
                            $expired_date='Expired';
                        }
                        }else if(empty($package)) {
                            $subscribed_date = new \Carbon\Carbon($payment->created_at);
                            $expired_date = 'Expired';
                        }
                    }
                    else if($payment->payment_by=='Paypal'){
                        $package=$user->packages->where('pivot.billing_agreement_id',$payment->payment_id)->first();
                        if(!empty($package)){
                        $subscribed_date=new \Carbon\Carbon($package->pivot->subscribed_at);
                        $expired_date=new \Carbon\Carbon($package->pivot->expired_at);
                        if($package->pivot->subscribed_status=='Active'){
                            $expired_date=$expired_date->format('d-M-Y');

                        }
                        else if($package->pivot->subscribed_status=='Suspended'){
                            $expired_date='Auto Subscription Off';

                        }
                        else if($package->pivot->subscribed_status=='Cancelled'){
                            $expired_date='Auto Subscription Off';

                        }
                        else if($package->pivot->subscribed_status=='Expired'){
                            $expired_date='Expired';
                        }
                        }
                        else{
                            $subscribed_date=new \Carbon\Carbon($payment->created_at);
                            $expired_date='Expired';
                            }
                    }else if($payment->payment_by=='Manual'){
                        $package=$user->packages->where('pivot.subscription_id',$payment->payment_id)->first();
                        if(!empty($package)){
                            $subscribed_date=new \Carbon\Carbon($package->pivot->subscribed_at);
                            $expired_date=new \Carbon\Carbon($package->pivot->expired_at);
                            if($package->pivot->subscribed_status=='Active'){
                                $expired_date=$expired_date->format('d-M-Y');

                            }
                            else if($package->pivot->subscribed_status=='Expired'){

                                $expired_date='Manually';
                            }
                        }
                        else{
                            $package=\App\Models\Package::find($payment->package_id);
                            $subscribed_date=new \Carbon\Carbon($payment->created_at);
                            $expired_date='Manually';
                        }
                    }
                    $package=Package::find($payment->package_id);
                    $viewUser='/admin/user-details/'.$user->id;
                    $viewPackage='/admin/package-details/'.$package->id;

                    $nestedData['no'] = $i++;
                    $nestedData['id'] = $payment->id;
                    $nestedData['subscription_id']=$payment->payment_id;
                    $nestedData['username'] = '<a class="text-dark" href="'.$viewUser.'">'.$user->name.'</a>';
                    $nestedData['package_name'] = '<a class="text-dark" href="'.$viewPackage.'">'.$package->name.'</a>';
                    $nestedData['amount'] = '$'.$payment->subtotal;
                    $nestedData['subscribed_at'] = $subscribed_date->format('d-M-Y');
                    $nestedData['expired_at'] = $expired_date;
                    $nestedData['payment_by'] = $payment->payment_by;
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

    public function viewVideo($id){
        $video=Video::findOrFail($id);

        $responsesCollection=collect([json_decode(VdoCipherController::getOtp($video->video_id),true)]);
        if(isset($responsesCollection[0]['message']) && ($responsesCollection[0]['message'])=="video not found"){
            abort(404);
        }
        return view('admin.view-video',compact('responsesCollection','video'));
    }
    public function adminProfileChangeEmail(Request $request){
        $request->validate([
           'email'=>'required|email'
        ]);
        $admin=Admin::findOrFail(\auth()->guard('admin')->user()->id);
        $admin->email=$request->input('email');
        $admin->save();
        return redirect()->back()->with('success','Email updated successfully.');
    }
    public function editUserPassword(Request $request,$id){
        $request->validate([
           'password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirm_password'=>'required|same:password',
        ]);
            $user=User::findOrFail($id);
            $user->password=Hash::make($request->input('password'));
            $user->save();
            return redirect()->back()->with('success','Password successfully changed.');

    }
    public function blockUser(Request $request){
        $user=User::findOrFail($request->input('user_id'));
        $user->is_blocked=!($user->is_blocked);
        $user->save();
        return Response::json(['success'=>$user->is_blocked],200);
    }
    public function userManualSubscription(Request $request){
        $request->validate([
            'package'=>'required|exists:packages,id',
            'user'=>'required|exists:users,id',
            'expiry_date'=>'required|date|after:now',
            'price'=>'required_if:free,false',
        ]);
        $user=User::findOrFail($request->input('user'));
        $expiry_date=new Carbon($request->input('expiry_date'));

        if($user->packages->where('id',$request->input('package'))->first()) {

            $pivot = $user->packages->where('id', $request->input('package'))->first()->pivot;
            if ($pivot->subscribed_status == 'Expired'){
                $pivot->subscribed_status = 'Active';
            $pivot->subscribed_at = new \DateTime();
            $pivot->expired_at = $expiry_date->endOfDay();
            $pivot->renewal_status = 0;
            $pivot->billing_agreement_id = null;
            $pivot->error_message = null;
            $pivot->updated_at = \Carbon\Carbon::now();
                $random = 'manual_' . uniqid() . time();

                if ($request->input('price') && $request->input('free')=='false') {

                Payment::create([
                    'payment_id' => $random,
                    'user_id' => $user->id,
                    'subtotal' => $request->input('price'),
                    'total_amount' => $request->input('price'),
                    'package_id' => $request->input('package'),
                    'payment_by' => 'Manual'
                ]);
            }
            $pivot->subscription_id = $random;

            $pivot->save();
        }else{
                return redirect()->back()->with('danger','User already subscribed.');

            }
        }else{
            $random='manual_'.uniqid().time();

            if($request->input('price') && $request->input('free')=='false'){


                Payment::create([
                    'payment_id'=>$random,
                    'user_id'=>$user->id,
                    'subtotal'=>$request->input('price'),
                    'total_amount'=>$request->input('price'),
                    'package_id'=>$request->input('package'),
                    'payment_by'=>'Manual'
                ]);
            }
            $user->packages()->attach($request->input('package'), [
                'username' => sha1(uniqid() . time() . date('d-m-y')),
                'password' => '$2y$10$b19UQbpdgen.vWs8NzK0a.CFjrH.2jVcEgMhBt7pN6drd1aN3hkC6',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
                'payment_by' => 'Manual',
                'subscribed_status'=>'Active',
                'subscription_id'=>$random,
                'renewal_status'=>0,
                'subscribed_at'=>new \DateTime(),
                'expired_at'=>$expiry_date->endOfDay(),
            ]);

        }
        $s=ManualSubscriptionRequest::where('user_id',$request->input('user'))->where('package_id',$request->input('package'))
            ->first();
        if($s){
            $s->status="Approved";
            $s->save();
        }
        return redirect()->back()->with('success','User has been subscribed to the package');
    }
    public function userNotSubscribedPackages(Request $request){
        $user=User::find($request->input('user'));
        if(empty($user)){
            return Response::json([
                'id'=>'',
                'text'=>''
            ]);
        }
        $userPackage=$user->packages->where('pivot.subscribed_status','!=','Expired');


        return Response::json(Package::selectRaw('id, name as text')->whereNotIn('id',$userPackage->pluck('id'))->get());
        //    return view('admin.user-manual-subscription');
    }
    public function getPackagePrice(Request $request){
        $package=Package::find($request->input('package_id'));


        return Response::json(['price'=>$package->price_year]);
        //    return view('admin.user-manual-subscription');
    }
    public function manageUserSubscription(){

        return view('admin.manage-user-subscription');
    }
    public function userSubscriptionList(Request $request){
        $where1=' `users`.`id` != 0';
        $where=" subscribed_status not like 'Expired'";
        $where2=' id!=0';
        $users=User::whereRaw($where1)->whereHas('packages')->with(['packages'=>function ($query) use ($where){
            $query->whereRaw($where);
        }])->get();
        $packages=$users->pluck('packages')->collapse();

        if($request->input('package_name')){
            $where.=" and name like '%".$request->input('package_name')."%'";
        }
        if($request->input('username')){
            $where1.=" and name like '%".$request->input('username')."%'";
        }
        if($request->input('payment_by')){
            $where.=" and payment_by like '".$request->input('payment_by')."'";
        }
        $users=User::whereRaw($where1)->whereHas('packages')->with(['packages'=>function ($query) use ($where){
            $query->whereRaw($where);
        }])->get();
        $packages=$users->pluck('packages')->collapse();
        $limit = $request->input('length');
        $start = $request->input('start');

        $packages=$packages->skip($start)->take($limit);
        $totalData = $packages->count();
        $totalFiltered=$totalData;
        $data = array();
        if(!empty($packages))
        {
            $i=1;
            foreach ($packages->sortByDesc('pivot.subscribed_at') as $package)
            {
                $user=User::where('id',$package->pivot->user_id)->first();
                $route=route('admin.update-subscription',[$user->id,$package->id]);
                $nestedData['no'] = $i++;
                $nestedData['package_id'] = $package->id;
                $nestedData['user_id'] = $package->pivot->user_id;
                $nestedData['package_name'] =$package->name;
                $nestedData['user_name'] =$user->name;
                $nestedData['status'] =$package->pivot->subscribed_status;
                $nestedData['payment_by'] =$package->pivot->payment_by;
                $nestedData['action']="<a href='$route' class='tag'>Edit Subscription</a>";
                $data[] = $nestedData;
            }
        }
        $json=array(
            "draw"=>intval(\request('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=>$data
        );
        return \Illuminate\Support\Facades\Response::json($json);
    }
    public function updateSubscription($user_id,$package_id){

        $user=User::where('id',$user_id)->first();
        $package=$user->packages->where('id',$package_id)->first();
        if(empty($user) || empty($package)){
            abort(404);
        }
        return view('admin.edit-subscription',compact('user','package'));
    }
    public function submitUpdateSubscription(Request $request,$user_id,$package_id){
        $user=User::where('id',$user_id)->first();
        $package=$user->packages->where('id',$package_id)->first();
        $expiry_date=new Carbon($request->input('expiry_date'));
        $request->validate([
//            'package'=>'required|exists:packages,id',
//            'user'=>'required|exists:users,id',
            'expiry_date'=>'required|date|after:'.$package->pivot->expired_at,
            'price'=>'nullable'
        ]);


        if(empty($user) || empty($package)){
            abort(404);
        }

        if($package){
            $random='manual_'.uniqid().time();
            if($request->input('price')){


                Payment::create([
                    'payment_id'=>$random,
                    'user_id'=>$user->id,
                    'subtotal'=>$request->input('price'),
                    'total_amount'=>$request->input('price'),
                    'package_id'=>$package_id,
                    'payment_by'=>'Manual'
                ]);
            }
            $pivot = $package->pivot;
            $pb=$pivot->payment_by;
            $si=$pivot->subscription_id;
            $bi=$pivot->billing_agreement_id;

            $pivot->subscribed_status = 'Active';
            $pivot->subscribed_at = new \DateTime();
            $pivot->expired_at =$expiry_date->endOfDay() ;
            $pivot->renewal_status = 0;
            $pivot->billing_agreement_id=null;
            $pivot->error_message=null;
            $pivot->frequency=null;
            $pivot->interval_count=null;
            $pivot->payment_method=null;
            $pivot->updated_at = \Carbon\Carbon::now();
            $pivot->payment_by='Manual';
            $pivot->subscription_id=$random;
            $pivot->save();
            if($pb=='PayPal'){
                PayPalController::cancelSubscription($bi,'User subscription change to manual by admin');
            }
            if($pb=='Stripe'){
                \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
                $subscription = \Stripe\Subscription::retrieve($si);
                $subscription->cancel();
            }

            return redirect()->back()->with('success','User subscription has been updated.');
        }


    }
    public function addSubscription(){

        return view('admin.add-manual-subscription');
    }
    public function allUsersForSelect(){

        return Response::json(User::selectRaw('id, name as text')->where('verified',1)->get());

    }
    public function userSubscriptionRequest(){

        return view('admin.manual-subscription-request');

    }
    public function userSubscriptionRequestList(Request $request){
        $where1=' `users`.`id` != 0';
        $where=' `manual_subscription_requests`.`id` != 0';
        if($request->input('username')){
            $where1.=" and name like '%".$request->input('username')."%'";
        }
        if($request->input('status')){
            $where.=" and status = '".$request->input('status')."'";
        }
        $totalData=ManualSubscriptionRequest::whereRaw($where)->with(['user'=>function ($query) use ($where1){
            $query->whereRaw($where1);
        },'package'])->count();
        $totalFiltered=$totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $subs=ManualSubscriptionRequest::whereRaw($where)->limit($limit)->offset($start)->whereHas('user',function ($query) use ($where1){
            $query->whereRaw($where1);
        })->with(['user'])->get();

        $data = array();
        if(!empty($subs))
        {
            $i=1;
            foreach ($subs as $sub)
            {

                $route='javascript:void(0)';
                $action='N/A';
                if($sub->status!='Approved' && $sub->status!='Rejected'){
                    $action="<a href='$route' class='tag approve-subscription' data-id='$sub->id' data-user='$sub->user_id' data-package='$sub->package_id'>Approve</a><a href='$route' class='tag reject-subscription'  data-user='$sub->user_id' data-package='$sub->package_id'>Reject</a>";;
                }
                $nestedData['no'] = $i++;
                $nestedData['package_id'] = $sub->package_id;
                $nestedData['user_id'] = $sub->user_id;
                $nestedData['package_name'] =$sub->package->name;
                $nestedData['user_name'] =$sub->user->name;
                $nestedData['status'] =$sub->status;
                $nestedData['action']=$action;
                $data[] = $nestedData;
            }
        }
        $json=array(
            "draw"=>intval(\request('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=>$data
        );
        return \Illuminate\Support\Facades\Response::json($json);
    }
    public function userSubscriptionRequestReject(Request $request){
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'package_id'=>'required|exists:packages,id',
        ]);
        $sub=ManualSubscriptionRequest::where('user_id',$request->input('user_id'))
            ->where('package_id',$request->input('package_id'))->first();
        $sub->status='Rejected';
        if($sub->save()){
            return Response::json(['message'=>'Rejected']);
        }else{
            return Response::json(['message'=>"Some error has occurred"],422);

        }

    }
    public function getSubscriptionRequestDetails($id){
        $sub=ManualSubscriptionRequest::where('id',$id)->with('user','package')->first();

        return Response::json(['subscription'=>$sub?$sub:null]);
    }
    public function addNewSubCategory(Request $request){
            $categories=Category::all();
        return view('admin.new-sub-category',compact('categories'));
    }
    public function storeNewSubCategory(Request $request,$category_id){
        $request->merge(compact('category_id'));
        $validatedData=$request->validate([
           'name'=>'required',
           'category_id'=>'required|exists:categories,id',
           'detail'=>'required|max:'.env('TEXT_AREA_LIMIT'),
        ]);
        $subCategory=new SubCategory($validatedData);
        if($subCategory->save()){
            return redirect()->back()->with('success','Sub Category Added Successfully.');
        }
        return redirect()->back()->with('danger','Some error has occurred.');

    }
    public function getSubCategoryList($category_id){
        $category=Category::findOrFail($category_id);

        return view('admin.sub-categories-list',compact('category'));
    }
    public function subCategoryListDataTable(Request $request){


        $limit = $request->input('length');
        $start = $request->input('start');


        $a=0;
        $category_id=$request->input('category_id');
        $subcategoriesQuery =  SubCategory::where('category_id',$category_id)->where(function ($query) use ($request){
//            if($request->input('category_name')){
//                $query->whereHas('category',function ($query) use ($request){
//                    $query->where('name', 'LIKE',"%".$request->input('category_name')."%");
//                });
//                $a=1;
//            }
            if($request->input('name')){
                    $query->where('name', 'LIKE',"%".$request->input('name')."%");
                $a=1;
            }
        });
        $totalData=SubCategory::all()->count();
        $totalFiltered=$totalData;
        if($a>0){
            $totalFiltered=  $subcategoriesQuery->count();


        }
        $sub_categories= $subcategoriesQuery->offset($start)
            ->limit($limit)
            ->get();
        $data = array();
        if(!empty($sub_categories))
        {
            foreach ($sub_categories as $key=>$sub_category)
            {
//                $edit=route('admin.edit-sub-category',[$sub_category->id]);
                $edit="javascript:void(0)";
                $delete=route('admin.delete-sub-category',[$sub_category->id]);
                $nestedData['no'] = ($start+1)+$key;
                $nestedData['id'] = $sub_category->id;
                $nestedData['name'] = $sub_category->name;
                $nestedData['category_name'] = $sub_category->category->name;
                $nestedData['package'] =$sub_category->category->package->name;
                $nestedData['video'] =$sub_category->videos->count();
                $nestedData['detail'] =$sub_category->detail;
                $nestedData['action']='<a href='.$edit.' class="tag" data-id="'.$sub_category->id.'" data-name="'.$sub_category->name.'" data-detail="'.$sub_category->detail.'" data-target="#subCategoryModal" data-toggle="modal">Edit</a> <a href='.$delete.' data-id='.$sub_category->id.' class="tag btn-delete-sub-category">Delete</a>';
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
    public function editSubCategory($id){
        $sub_category=SubCategory::findOrFail($id);
        $categories=Category::all();

        return view('admin.edit-sub-category',compact('sub_category','categories'));
    }
    public function updateSubCategory(Request $request,$id){

        $validatedData=$request->validate([
            'e_name'=>'required',
//            'category_id'=>'required|exists:categories,id',
            'e_detail'=>'required|max:'.env('TEXT_AREA_LIMIT'),
        ]);
        $subCategory=SubCategory::findOrFail($id)->update(['name'=>$request->input('e_name'),'detail'=>$request->input('e_detail')]);
        if($subCategory){
            return redirect()->back()->with('success','Sub Category Updated Successfully.');
        }
        return redirect()->back()->with('danger','Some error has occurred.');

    }
    public function deleteSubCategory($id){
        $sub_category=SubCategory::findOrFail($id)->delete();
        if($sub_category){
            return Response::json(['message'=>'success']);
        }
        return Response::json(['message'=>'error'],422);


    }

}
