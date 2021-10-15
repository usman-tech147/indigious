<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use App\Models\Video;
use App\Models\BeltingEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
class CommunityController extends Controller
{
    public function subscribedPackageDetails()
    {

        $community=\auth()->guard('community')->user();

            $package = Package::where('id', $community->package_id)->first();
        return view('community.package', compact('package'));
    }
    public function logout(){
        if(Auth::guard('community')->check()){
            Auth::guard('community')->logout();
            return redirect()->route('home');
        }
    }
    public function getVideo(Request $request,$id){
        //    playback otp
        $video=Video::findOrFail($id);

        if($video->subCategory->category->package->where('id', \auth()->guard('community')->user()->package_id)->first()==null) {
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
    public function submitBeltingRequest(Request $request){
        $community=\auth()->guard('community')->user();

        $request->validate([
            'name'=>['required','max:'.env('INPUT_SMALL')],
            'email'=>['required','email'],
            'phone_no'=>['required','digits:11'],
            'message'=>['required','max:'.env('TEXT_AREA_LIMIT')
            ]
        ]);

        $beltingRequest=new BeltingEvaluation([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'phone_no'=>$request->input('phone_no'),
            'message'=>$request->input('message'),
            'user_id'=>$community->user_id,
            'status'=>'Pending'
        ]);
        $beltingRequest->save();
        return redirect()->back()->with('success','Your request has been sent to admin.');
    }
    public function getVideoDetail($id){
        $community=\auth()->guard('community')->user();

        $video=Video::findOrFail($id);
        if($video){
            if(!$video->subCategory->category->package->where('id',$community->package_id)){
                abort(404);
            }
        }
        $responseObj=json_decode(VdoCipherController::getOtp($video->video_id));
        if(property_exists($responseObj,'error')){
            return  json_encode(['error'=>'Error']);
        }
        $data['otp']= $responseObj->otp;
        $data['playbackInfo']= $responseObj->playbackInfo;
        $responses[]=$data;
        $responsesCollection=collect($responses);
        return view('community.video-detail',compact('video','responsesCollection'));
    }
}
