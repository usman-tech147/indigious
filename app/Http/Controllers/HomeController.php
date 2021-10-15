<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use App\Models\Package;
use App\Models\User;
use App\Models\WebsiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;


class HomeController extends Controller
{
    public function index(){
        $packages=Package::limit(3)->get();

        return view('index',compact('packages'));
    }

    public function aboutUs(){
        return view('about-us');
    }
    public function contactUs(){
        $websiteSettings=\App\Models\WebsiteSettings::first();
        return view('contact-us',compact('websiteSettings'));
    }
    public function signIn(){
        return view('sign-in');
    }
    public function signUp(){
        return view('sign-up');
    }
    public function videoAccess($username){
        return view('video-access',compact('username'));
    }
    public function videoAccessLogin(Request $request,$username){

        $request->validate([
            'password'=>'required'
        ]);
        if(auth()->guard('community')->attempt(['username'=>$username,'password'=>$request->input('password')])){
            return redirect()->route('community.subscribed-package');
        }
        return redirect()->back()->with('danger','Incorrect Password.');


    }
    public function packages(){
        $packages=Package::simplePaginate(12);
        return view('packages',compact('packages'));
    }
    public function faqs(){
        return view('faqs');
    }
    public function getPackageInfo(Request $request){
        $package=Package::findOrFail($request->input('package_id'));
        return Response::json($package);
    }
    public function submitContactUs(Request $request){
        $request->validate([
            'name' => 'required',
            'email'=>'required',
            'phone_no'=>'required|digits:11',
            'subject'=>'required',
            'message'=>'required',
        ]);
        try {
            Mail::to(WebsiteSettings::first()->contact_email)->send(new ContactUs($request->except(['_token'])));
            return redirect()->back()->with('success','Message sent we will contact you shortly.');

        }catch (\Exception $ex){
            return redirect()->back()->with('danger','Some error has occurred.');

        }
    }
    public function forgotPassword(){
        return view('forgot-password');
    }
    public function submitForgotPassword(Request $request){
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
            return redirect()->route('sign-in')->with('success','Password reset link sent on your email it will expire within 5 minutes.');

        }
        else{
            return redirect()->route('forgot-password')->with('danger','User doesn\'t exist try again!');

        }
    }
    public function verifyForgotPasswordEmail(Request $request,$email){
        $code=$request->get('token');

        $user=User::where('email',$email)
            ->where('verification_code',$code)->first();

        if($user==null){
            return redirect()->route('forgot-password')->with('danger','The link you are trying to access is invalid or broken!');
        }
        else{
            $currentTime=new Carbon(new \DateTime());

            $diff=$currentTime->diffInRealMinutes($user->verification_code_time);
            if($diff>=5){
                return redirect()->route('forgot-password')->with('danger','Link expired try to get another one.');

            }
            else{
                return view('password-reset',[
                    'email'=>$email,'code'=>$code
                ])->with('success','Change Your Password!');
            }
        }
    }
    public function passwordResetPage(Request $request,$email){
        $request->validate([
            'password'=>'required|min:8',
            'confirm_password'=>'same:password'
        ]);
        $code=$request->get('token');
        $user=User::where('email',$email)
            ->where('verification_code',$code)->first();
        if($user==null){
            return redirect()->route('forgot-password')->with('danger','The link you are trying to access is invalid or broken!');
        }
        else{
            $currentTime=new Carbon(new \DateTime());
            $diff=$currentTime->diffInRealMinutes($user->verification_code_time);
            if($diff>=5){
                return redirect()->back()->with('danger','Link expired try to get another one.');

            }
            else{
                $user->password=Hash::make($request->input('password'));
                $user->verification_code=null;
                $user->verification_code_time=null;
                $user->save();
                return redirect()->route('sign-in')->with('success','Password successfully changed!');
            }
        }
    }
    public function packageDetails($id){
        $package=Package::findOrFail($id);
        return view('package-details',compact('package'));
    }
}
