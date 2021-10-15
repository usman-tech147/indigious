<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Facades\Response;


class VdoCipherController extends Controller
{
  public static function getToken($trimmedTitle){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://dev.vdocipher.com/api/videos?title=$trimmedTitle",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "PUT",
          CURLOPT_HTTPHEADER => array(
              "Authorization: Apisecret ".env('VDO_CIPHER_API_KEY')
          ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      if ($err) {
          return json_encode(['message'=>$err]);
      }
      else{
          return $response;
      }
  }
  public static  function uploadVideoAgainstToken($filePath,$uploadCredentials){

      $ch = curl_init($uploadCredentials->uploadLink);

      curl_setopt ($ch, CURLOPT_POST, 1);

      curl_setopt ($ch, CURLOPT_POSTFIELDS, [
          'policy' => $uploadCredentials->policy,
          'key'    => $uploadCredentials->key,
          'x-amz-signature' => $uploadCredentials->{'x-amz-signature'},
          'x-amz-algorithm' => $uploadCredentials->{'x-amz-algorithm'},
          'x-amz-date' => $uploadCredentials->{'x-amz-date'},
          'x-amz-credential' => $uploadCredentials->{'x-amz-credential'},
          'success_action_status' => 201,
          'success_action_redirect' => '',
          'file' => new \CurlFile($filePath),

      ]);
      curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

      $response = curl_exec($ch);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $err = curl_error($ch);

      curl_close($ch);

      if (!$err && $httpcode === 201) {
          return json_encode(['success'=>'Uploaded']);
      } else {
          return json_encode(['error'=>$response]);

      }

  }
  public static function uploadPoster($videoId,$posterPath,$mimeType){


      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_URL => "https://dev.vdocipher.com/api/videos/$videoId/files",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_POSTFIELDS => [
              'file' => curl_file_create($posterPath, $mimeType),
          ],
          CURLOPT_HTTPHEADER => array(
              "Content-Type: multipart/form-data",
              "Accept: application/json",
              "Authorization: Apisecret " . env('VDO_CIPHER_API_KEY'),
          ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      curl_close($curl);

      if ($err) {
          return json_encode(['error'=>'Poster Uploading Failed']);
      }
      else{
          return json_encode(['success'=>'Poster Uploaded']);

      }
  }
  public static function getVideoDetail($videoId){
      $curl = curl_init();

      curl_setopt_array($curl, array(
          CURLOPT_URL => "https://dev.vdocipher.com/api/videos/".$videoId,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
              "Accept: application/json",
              "Content-Type: application/json",
              "Authorization: Apisecret " . env('VDO_CIPHER_API_KEY'),
          ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
          return json_encode(['error'=>'Some Error Has Occurred!']);
      }
      else {
          return $response;
      }
  }
    public static function deletePoster($id){
        $video=Video::findOrFail($id);
        $posterPath=public_path('/images/ni.jpg');
        $mimeType='image/jpg';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dev.vdocipher.com/api/videos/$video->video_id/files",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_POSTFIELDS => [
                'file' => curl_file_create($posterPath, $mimeType),
            ],
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data",
                "Accept: application/json",
                "Authorization: Apisecret " . env('VDO_CIPHER_API_KEY'),
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            return Response::json(['message'=>'error']);

        }
        else{
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://dev.vdocipher.com/api/videos/".$video->video_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: Apisecret " . env('VDO_CIPHER_API_KEY'),
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return Response::json(['message'=>'error']);

            }
            else {
                $videoDetails= collect(json_decode($response));
                $video->poster=$videoDetails['poster'];
                $video->poster_set=0;

            }

        }

        $video->save();
        return Response::json(['message'=>'updated']);
    }
    public static function deleteVideo($videoID){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dev.vdocipher.com/api/videos?videos=".$videoID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Apisecret ".env('VDO_CIPHER_API_KEY'),
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return json_encode(['error'=>'Error Deleting Video']);
        }
        else {
            return json_encode(['success'=>'Deleted']);

        }
    }
    public static function getOtp($videoId){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dev.vdocipher.com/api/videos/$videoId/otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "ttl" => 3600,
            ]),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Apisecret " . env('VDO_CIPHER_API_KEY'),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return json_encode(['error' => "cURL Error #:" . $err]);
        } else {
           return $response;
        }

    }

}
