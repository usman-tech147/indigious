<?php

use App\Http\Controllers\API\CommunityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    // User routes
        Route::post('login',[UserController::class,'signIn']);
        Route::post('register',[UserController::class,'signUp']);
        Route::post('forgot-password',[UserController::class,'forgotPassword']);
        Route::get('get-all-packages',[UserController::class,'getAllPackages']);
        Route::get('get-package/{id}',[UserController::class,'getPackageById']);
        Route::post('contact-us',[UserController::class,'contactUs']);
        Route::get('contact',[UserController::class,'contact']);
    // Auth user routes
    Route::prefix('user')->middleware(['auth:user_api'])->group(function (){
        Route::post('logout',[UserController::class,'logout']);
        Route::get('details',[UserController::class,'getDetails']);
        Route::post('update-profile',[UserController::class,'updateProfile']);
        Route::get('subscribed-packages',[UserController::class,'subscribedPackages']);
        Route::get('package/{id}',[UserController::class,'getSubscribedPackageById']);
        Route::get('video/{id}',[UserController::class,'getVideoById']);
        Route::post('submit-belting-request',[UserController::class,'submitBeltingRequest']);
        Route::get('belting-request',[UserController::class,'getBeltingRequest']);
        Route::post('change-password',[UserController::class,'changePassword']);
        Route::get('manage-subscribed-packages',[UserController::class,'manageSubscribedPlan']);
        Route::post('change-subscription-status',[UserController::class,'changeAutoRenewStatus']);
        Route::get('subscription-requests',[UserController::class,'getSubscriptionRequests']);
        Route::get('access-passwords',[UserController::class,'getAccessPassword']);
        Route::post('change-access-password/{package_id}',[UserController::class,'changeAccessPassword']);
    });

    Route::post('community/login',[CommunityController::class,'login']);

    Route::prefix('community')->middleware('auth:community_api')->group(function (){
        Route::get('package',[CommunityController::class,'getSubscribedPackageDetails']);
        Route::get('video/{id}',[CommunityController::class,'getVideoById']);
        Route::post('belting-request',[CommunityController::class,'submitBeltingRequest']);
        Route::post('logout',[CommunityController::class,'logout']);
    });
    Route::fallback(function (){
        return Response::json(['message'=>'Invalid route.'],422);
    });

