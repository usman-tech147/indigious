<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VdoCipherController;
use App\Models\BeltingEvaluation;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class CommunityController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        $community=UserPackage::where('username',$request->input('username'))->first();

        if (Auth::guard('community_test')->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {

            $token=$community->createToken($community->username)->accessToken;
            return Response::json([
                'message'=>'Successfully logged in.',
                'user'=>$community,
                'token'=>$token
            ]);
        }else{
            return Response::json([
                'message'=>'Username or password incorrect!'
            ]);
        }
    }
    public function getSubscribedPackageDetails(Request $request){
        $community= $request->user('community_api');
        $package=$community->package;
        if($package->subscribed_status=='Expired'){
            return Response::json([
                'package'=>null,
                'message'=>'Package is expired.'
            ]);
        }
        return Response::json([
            'package'=>$package->load('categories.subCategory.videos'),
        ]);
    }
    public function getVideoById(Request $request,$id){

            $community=$request->user('community_api');
            if($community->subscribed_status!='Expired'){
               $video=Video::where('id',$id)->whereHas('category.package',function ($query) use ($community){
                    $query->where('id',$community->package_id);
               })->first();
            }
        $responseObj=json_decode(VdoCipherController::getOtp($video->video_id));
        if(property_exists($responseObj,'error')){
            return  Response::json(['message'=>'Some error has occurred try again.'],422);
        }
        $videoData['video_detail']=$video;
        $videoData['otp']=$responseObj->otp;
        $videoData['playbackInfo']=$responseObj->playbackInfo;
            return Response::json($videoData);
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
            'user_id'=>$request->user('community_api')->user_id,
            'status'=>'Pending'
        ]);
        $beltingRequest->save();
        return Response::json(['message'=>'Your request has been sent to admin.']);
    }
    public function logout (Request $request) {
        $token = $request->user('community_api')->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return Response::json($response, 200);
    }
}
