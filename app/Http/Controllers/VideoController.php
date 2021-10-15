<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\SubCategory;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
class VideoController extends Controller
{
    public function getVideoUploadToken(Request $request){
            $request->validate([
                'video_name'=>['required','max:'.env('INPUT_SMALL')],
                'video'=>['required','mimetypes:video/avi,video/mp4,video/quicktime,video/x-matroska'],
                'description'=>['required','max:'.env('TEXT_AREA_LIMIT')],
//                'package'=>['required','exists:packages,id'],
                'sub_category'=>['required','exists:sub_categories,id'],
                'poster'=>['mimes:jpeg,jpg,png']
            ]);
            $video=$request->file('video');
            $videoName=$video->getRealPath();
            $title=$request->input('video_name');
            $trimmedTitle=trim(str_replace(' ','_',$title));

            $response=VdoCipherController::getToken($trimmedTitle);
             $responseObj = json_decode($response);

        if (property_exists($responseObj,'message')) {
            return Response::json(['error'=>$responseObj->message]);

        } else {

            $uploadCredentials = $responseObj->clientPayload;
            $filePath = $videoName;
            $uploadResponse=VdoCipherController::uploadVideoAgainstToken($filePath,$uploadCredentials);
            $uploadResponseObj=json_decode($uploadResponse);
            if (property_exists($uploadResponseObj,'success')) {

                $posterPath=public_path('/images/ni.jpg');
                $mimeType='image/jpg';
                $val=0;
                if($request->hasFile('poster')) {
                    $val=1;
                    $poster = $request->file('poster');
                    $posterPath = $poster->getRealPath();
                    $mimeType = 'image/' . $poster->getClientOriginalExtension();
                }
                $posterResponse=VdoCipherController::uploadPoster($responseObj->videoId,$posterPath,$mimeType);

                $responseVideoDetails=VdoCipherController::getVideoDetail($responseObj->videoId);
                if(!property_exists($responseVideoDetails,'error')) {
                    $videoDetails = collect(json_decode($responseVideoDetails));
                }
                $p="";
                if(isset($videoDetails['posters'])){
                    if(count($videoDetails['posters'])>0){
                        $p=$videoDetails['posters'][0]->posterUrl;
                    }
                }else{
                    if(isset($videoDetails['poster'])){
                        $p=$videoDetails['poster'];

                    }
                }
                $video=new Video([
                        'title'=>$title,
                        'description'=>$request->input('description'),
//                        'package_id'=>$request->input('package'),
                        'sub_category_id'=>$request->input('sub_category'),
                        'video_id'=>$responseObj->videoId,
                        'poster'=>$p,
                        'poster_set'=>$val
                        ]);
                    $video->save();

                return Response::json(['message'=>'Video Uploaded!']);

            } else {
                return Response::json(['error'=>'Error While Uploading Video'],500);
            }

        }
    }
    public function editUploadedVideo($id){
        $video=Video::with('subCategory')
            ->where('videos.id',$id)
            ->first();
        if($video==null){
            abort(404);
        }


        $packages=Package::all();
        $categories=Category::all();
        $sub_categories=SubCategory::all();
        return view('admin.edit-video',[
            'video'=>$video,
            'packages'=>$packages,
            'categories'=>$categories,
            'sub_categories'=>$sub_categories

        ]);
    }
    public function updateUploadedVideo(Request $request,$id){
        $video=Video::findOrFail($id);

        $request->validate([
            'video_name'=>['required','max:'.env('INPUT_SMALL')],
            'poster'=>['mimes:jpg,jpeg,png'],
            'description'=>['required','max:'.env('TEXT_AREA_LIMIT')],
//            'package'=>['required','exists:packages,id'],
            'sub_category'=>['required','exists:sub_categories,id'],

        ]);

        if($request->hasFile('poster')) {
            $poster=$request->file('poster');
            $posterPath=$poster->getRealPath();
            $mimeType='image/'.$poster->getClientOriginalExtension();
           $uploadedPosterResponse= json_encode(VdoCipherController::uploadPoster($video->video_id,$posterPath,$mimeType));

        if (property_exists($uploadedPosterResponse,'error')) {
            return redirect()->back()->with('danger','Failed to upload poster!');
         }
        else{
            $videoDetails=json_decode(VdoCipherController::getVideoDetail($video->video_id));

            if (property_exists($videoDetails,'error')) {
                abort(404);
            }
            else {
                $video->poster=$videoDetails->poster;
                $video->poster_set=1;
            }
        }
        }
//        else{
//            $this->deletePoster($id);
//        }
        $video->title=$request->input('video_name');
        $video->description=$request->input('description');
//        $video->package_id=$request->input('package');
        $video->sub_category_id=$request->input('sub_category');

        $video->save();
        return redirect()->back()->with('success','Video information successfully updated');
    }
    public function deleteVideo($id){
        $video=Video::findOrFail($id);

        $videoDeleteResponse=json_decode(VdoCipherController::deleteVideo($video->video_id));
        if(property_exists($videoDeleteResponse,'success')){
            $video->delete();
        }
        else {
            return Response::json(['error'=>'Error While Deleting Video']);

        }
    }

    public function getAll(Request $request){
        //    playback otp
        $packageName=$request->get('package_name');
        $categoryName=$request->get('category_name');
        $subCategoryName=$request->get('sub_category_name');
        $videoName=$request->get('video_name');
        $current=$request->get('current');
        $length=$request->get('length');
        $current=($current-1)*$length;
        $responses=[];

        $videosQuery=Video::where(function ($query) use  ($videoName,$categoryName,$packageName,$subCategoryName){
            if($videoName){
                $query->where('title','like','%'.$videoName.'%');
            }
                $query->whereHas('subCategory',function ($query) use ($categoryName,$packageName,$subCategoryName){
                    if($subCategoryName){
                        $query->where('name','like','%'.$subCategoryName.'%');
                    }
                    if($categoryName){
                        $query->whereHas('category', function($q) use ($categoryName,$packageName)
                        {
                            $q->where('name','like',''.$categoryName.'');
                            if($packageName){
                                $q->whereHas('package', function($q) use ($packageName)
                                {
                                    $q->where('name','like',''.$packageName.'');
                                });
                            }
                        });
                    }

                });

        });
        $total=$videosQuery->count();
        $videos=$videosQuery->offset($current)->limit($length)->get();
        $responses=array();
        if($videos->count()>0) {
            foreach ($videos as $video) {
                $responseObj=json_decode(VdoCipherController::getOtp($video->video_id));
                if (property_exists($responseObj,'error')) {
                    return Response::json(['error' => $responseObj->error]);
                } else {
                    if(isset($responseObj->otp) && isset($responseObj->playbackInfo)) {
                    $data['otp'] = $responseObj->otp;
                    $data['playbackInfo'] = $responseObj->playbackInfo;
                    $responses[] = $data;
                }
                }
            }
        }
        else{
            return Response::json(array(
                'response' => [],
                'videos' => [],
            ));
        }
        $data=[];
        foreach ($videos as $video){
            $nestedData['id']=$video->id;
            $nestedData['title']=$video->title;
            $nestedData['category']=$video->subCategory->category->name;
            $nestedData['sub_category']=$video->subCategory->name;
            $nestedData['description']=$video->description;
            $nestedData['package_name']=$video->subCategory->category->package->name;
            $data[]=$nestedData;
        }
        $responsesCollection=collect($responses);
            return Response::json(array(
                'response' => $responsesCollection,
                'videos' => $data,
                'total'=>$total
                ));
    }
    public function deletePoster($id){
       return VdoCipherController::deletePoster($id);
    }

}
