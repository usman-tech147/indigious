<?php

use App\Http\Controllers\CommunityController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PayPalWebhookController;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use PayPal\Api\Agreement;

//Home Page Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::post('/contact-us', [HomeController::class, 'submitContactUs'])->name('contact-us');
Route::get('/packages', [HomeController::class, 'packages'])->name('packages');
Route::get('/package-details/{id}', [HomeController::class, 'packageDetails'])->name('package-details');
Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');
Route::post('/get-package-info', [HomeController::class, 'getPackageInfo']);
//Guest Routes
Route::middleware(['guest:admin', 'guest:user', 'guest:community'])->group(function () {
    Route::get('/video-access/{username}', [HomeController::class, 'videoAccess'])->name('video-access');
    Route::post('/video-access/{username}', [HomeController::class, 'videoAccessLogin'])->name('video-access');
    Route::get('/sign-in', [HomeController::class, 'signIn'])->name('sign-in');
    Route::get('/sign-up', [HomeController::class, 'signUp'])->name('sign-up');
    Route::post('/sign-in', [UserController::class, 'signIn'])->name('sign-in');
    Route::post('/sign-up', [UserController::class, 'signUp'])->name('sign-up');
    Route::get('/verify-email/{token}/{email}', [UserController::class, 'verifyEmailWithLink'])->name('verify-email');
    Route::get('/forgot-password', [HomeController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [HomeController::class, 'submitForgotPassword'])->name('forgot-password');
    Route::get('/password-reset/{email}', [HomeController::class, 'verifyForgotPasswordEmail'])->name('password-reset');
    Route::put('/password-reset/{email}', [HomeController::class, 'passwordResetPage'])->name('password-reset');
});
//Community Auth Routes:
Route::middleware(['auth:community'])->prefix('community')->group(function () {
    Route::get('/subscribed-package', [CommunityController::class, 'subscribedPackageDetails'])->name('community.subscribed-package');
    Route::post('/logout', [CommunityController::class, 'logout'])->name('community.logout');
    Route::get('/video/{id}', [CommunityController::class, 'getVideo'])->name('community.video');
    Route::post('belting-request', [CommunityController::class, 'submitBeltingRequest'])->name('community.belting-request');
    Route::get('subscribed-package/video/{id}', [CommunityController::class, 'getVideoDetail'])->name('community.subscribed-package-video');
});

//User Auth Routes
Route::middleware(['auth:user', 'blocked'])->prefix('user')->group(function () {
    Route::post('/manually-subscribe', [PaymentController::class, 'payManually'])->name('user.manual-subscription');
    Route::get('/', [UserController::class, 'index'])->name('user');
    Route::put('/', [UserController::class, 'updateProfile'])->name('user');
    Route::put('/update-profile-picture', [UserController::class, 'updateProfilePicture'])->name('user.profile-picture');
    Route::put('/delete-profile-picture', [UserController::class, 'deleteProfilePicture'])->name('user.delete-profile-picture');
    Route::get('/access-password', [UserController::class, 'accessPassword'])->name('user.access-password');
    Route::get('/belting-request', [UserController::class, 'beltingRequest'])->name('user.belting-request');
    Route::post('/belting-request', [UserController::class, 'submitBeltingRequest'])->name('user.belting-request');
    Route::get('/subscribed-package/{id}', [UserController::class, 'subscribedPackageDetails'])->name('user.subscribed-package');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::put('/change-password', [UserController::class, 'submitChangePassword'])->name('user.change-password');
    Route::get('/access-video', [UserController::class, 'accessVideo'])->name('user.access-video');
    Route::get('/video-details', [UserController::class, 'videoDetails'])->name('user.video-details');
    Route::get('/video/{id}', [UserController::class, 'getVideo'])->name('user.video');
    Route::get('/subscribed-package/video/{id}', [UserController::class, 'getVideoData'])->name('user.video-information');
    Route::post('/get-all-belting-request', [UserController::class, 'getAllBeltingRequests'])->name('user.get-all-belting-request');
    Route::get('/all-belting-request', [UserController::class, 'getAllRequestData'])->name('user.all-belting-request');
    Route::get('/success', [PaymentController::class, 'success'])->name('user.payment-successful');
    Route::put('/change-access-password/{package_id}/{pivot_id}', [UserController::class, 'changeAccessPassword'])->name('user.change-access-password');
    Route::get('/manage-plan', [UserController::class, 'viewPlan'])->name('user.view-plan');
    Route::post('/subscribed-plans/turn-off-auto', [UserController::class, 'turnOffAutoRenewal'])->name('user-subscribed-plans-turn-off-auto');
    Route::post('/subscribed-plans/turn-on-auto', [UserController::class, 'turnOnAutoRenewal'])->name('user-subscribed-plans-turn-on-auto');
    Route::post('/subscribed-plans/cancel-subscription', [UserController::class, 'cancelSubscription'])->name('user-subscribed-plans-cancel-subscription');
    Route::post('/update-payment/{package}', [UserController::class, 'updatePayment'])->name('user.update-payment');
    Route::post('/subscribe/package', [PaymentController::class, 'payWithStripe']);
    Route::get('/process-subscription', [PaymentController::class, 'paypalSuccess']);
    Route::post('/subscribe/package/paypal', [PaymentController::class, 'payWithPayPal'])->name('user-subscribe-package-paypal');
    Route::post('/subscribed-plans/suspend-subscription', [UserController::class, 'suspendSubscription'])->name('user-subscribed-plans-suspend-subscription');
    Route::post('/subscribed-plans/reactive-subscription', [UserController::class, 'reActivateSubscription'])->name('user-subscribed-plans-reactive-subscription');
    Route::post('/subscribed-plans/cancel-subscription-paypal', [UserController::class, 'cancelSubscriptionPaypal'])->name('user-subscribed-plans-cancel-subscription-paypal');
    Route::get('/belting-request-detail/{id}', [UserController::class, 'getBeltingRequestDetail'])->name('user.belting-request-detail');
    Route::get('/all-subscribed-packages', [UserController::class, 'allSubscribedPackages'])->name('user.all-subscribed-packages');
    Route::get('/subscription-requests', [UserController::class, 'getSubscriptionRequest'])->name('user.subscription-request');
    Route::post('/subscription-requests-list', [UserController::class, 'getSubscriptionRequestList'])->name('user.subscription-request-list');
});
Route::post('/user/logout', [UserController::class, 'logout'])->name('user.logout')->middleware('auth:user');

//Route::get('test/index.php/user/process-agreement',[PaymentController::class,'paypalSuccess'])->middleware('auth:user');
//Route::get('/cancel',[PaymentController::class,'cancel']);

//Stripe Webhook Route
Route::stripeWebhooks('stripe-subscribe-webhook');
//PayPal Webhook Route
Route::post('paypal-webhook', [PayPalWebhookController::class, 'webhook']);

//Admin Routes
Route::middleware(['guest:admin', 'guest:user', 'guest:community'])->prefix('admin')->group(function () {

    Route::get('/e2fc714c4727ee9395f324cd2e7f331fe2fc714c4727ee9395f324cd2e7f331fe2fc714c4727ee9395f324cd2e7f331fe2fc714c4727ee9395f324cd2e7f331f/login', [AdminController::class, 'login'])->name('admin.sign-in');
    Route::post('/login', [AdminController::class, 'loginPost'])->name('admin.login');
    Route::get('/forgot-password', [AdminController::class, 'forgotPassword'])->name('admin.forgot-password');
    Route::post('/forgot-password', [AdminController::class, 'passwordReset'])->name('admin.forgot-password');
    Route::get('/password-reset/{email}', [AdminController::class, 'verifyForgotPasswordEmail'])->name('admin.password-reset');
    Route::put('/password-reset/{email}', [AdminController::class, 'passwordResetPage'])->name('admin.password-reset-page');

});

//Admin Auth Routes
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    Route::get('', [AdminController::class, 'index'])->name('admin');
    Route::get('/belting-evolution-requests', [AdminController::class, 'beltingEvolutionRequests'])->name('admin.belting-evolution-requests');
    Route::get('/create-new-package', [AdminController::class, 'createNewPackage'])->name('admin.create-new-package');
    Route::get('/new-video-upload/{package_id?}', [AdminController::class, 'newVideoUpload'])->name('admin.new-video-upload');
    Route::get('/package-list', [AdminController::class, 'packageList'])->name('admin.package-list');
    Route::get('/user-edit', [AdminController::class, 'userEdit'])->name('admin.user-edit');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/videos-list', [AdminController::class, 'videosList'])->name('admin.videos-list');
    Route::get('/get-package-categories-list', [AdminController::class, 'getPackageCategories'])->name('admin.getPackageCategories');
    Route::get('/get-package-category-sub-categories-list', [AdminController::class, 'getPackageCategorySubCategories'])->name('admin.getPackageCategorySubCategories');
    Route::post('/delete-video-poster/{id}', [VideoController::class, 'deletePoster']);
    Route::get('/get-package-categories-id-list', [AdminController::class, 'getPackageCategoriesId'])->name('admin.getPackageCategoriesId');
    Route::get('/get-package-category-subcategories-id-list', [AdminController::class, 'getPackageCategoriesSubCategoriesId'])->name('admin.getPackageCategoriesId');
    Route::get('/get-all-packages-list', [AdminController::class, 'getAllPackages'])->name('admin.getAllPackages');
    Route::get('/get-selected-packages-list', [AdminController::class, 'getSelectedPackages']);
    Route::get('/get-category-videos', [AdminController::class, 'getCategoryVideos'])->name('admin.getCategoryVideos');
    Route::get('/website-settings', [AdminController::class, 'websiteList'])->name('admin.website-settings');
    Route::get('/admin-profile', [AdminController::class, 'adminProfile'])->name('admin.admin-profile');
    Route::put('/admin-profile-change-email', [AdminController::class, 'adminProfileChangeEmail'])->name('admin.change-email');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::post('/create-new-package', [PackageController::class, 'addNewPackage'])->name('admin.create-new-package');
    Route::post('/get-all-packages', [PackageController::class, 'packageListDataTable'])->name('admin.get-all-packages');

    Route::get('/new-category', [CategoryController::class, 'index'])->name('admin.new-category');
    Route::post('/new-category/{package_id}', [CategoryController::class, 'store'])->name('admin.new-category');
    Route::get('/category-list/{package_id}', [CategoryController::class, 'categoryList'])->name('admin.category-list');
    Route::post('/get-all-categories', [CategoryController::class, 'categoryListDataTable'])->name('admin.get-all-categories');
    Route::get('/edit-category/{id}', [CategoryController::class, 'editCategory'])->name('admin.edit-category');
    Route::put('/edit-category/{id}', [CategoryController::class, 'updateCategory'])->name('admin.edit-category');
    Route::delete('/delete-category/{id}', [CategoryController::class, 'deleteCategory'])->name('admin.delete-category');

    Route::prefix('sub-category')->group(function () {
        Route::get('/new', [AdminController::class, 'addNewSubCategory'])->name('admin.new-sub-category');
        Route::post('/new/{category_id}', [AdminController::class, 'storeNewSubCategory'])->name('admin.new-sub-category');
        Route::get('/edit/{id}', [AdminController::class, 'editSubCategory'])->name('admin.edit-sub-category');
        Route::put('/edit/{id}', [AdminController::class, 'updateSubCategory'])->name('admin.edit-sub-category');
        Route::get('/list/{category_id}', [AdminController::class, 'getSubCategoryList'])->name('admin.sub-category-list');;
        Route::post('/all', [AdminController::class, 'subCategoryListDataTable'])->name('admin.get-all-sub-categories');
        Route::delete('/delete/{id}', [AdminController::class, 'deleteSubCategory'])->name('admin.delete-sub-category');

    });


    Route::get('/user-details/{id}', [AdminController::class, 'getUserDetails']);
    Route::post('/new-video', [VideoController::class, 'getVideoUploadToken'])->name('admin.new-video');
    Route::get('/edit-video-details/{id}', [VideoController::class, 'editUploadedVideo'])->name('admin.edit-video');
    Route::put('/edit-video-details/{id}', [VideoController::class, 'updateUploadedVideo'])->name('admin.edit-video');
    Route::delete('/delete-video/{id}', [VideoController::class, 'deleteVideo'])->name('admin.delete-video');
    Route::get('/get-all-videos', [VideoController::class, 'getAll'])->name('admin.getAllVideos');
    Route::get('/view-video/{id}', [AdminController::class, 'viewVideo']);
    Route::post('/get-package-categories', [CategoryController::class, 'getPackageCategory'])->name('admin.get-package-categories');
    Route::get('/edit-package/{id}', [PackageController::class, 'editPackage'])->name('admin.edit-package');
    Route::put('/edit-package/{id}', [PackageController::class, 'updatePackage'])->name('admin.edit-package');
    Route::delete('/delete-package/{id}', [PackageController::class, 'deletePackage'])->name('admin.delete-package');
    Route::post('/get-all-users', [AdminController::class, 'getAllUsers'])->name('admin.get-all-users');
    Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.edit-user');
    Route::put('/edit-user/{id}', [AdminController::class, 'updateUser'])->name('admin.edit-user');
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::post('/delete-user-package/{user_id}/{package_id}', [AdminController::class, 'deleteUserPackage']);
    Route::put('/website-settings', [AdminController::class, 'websiteSettings'])->name('admin.website-settings');
    Route::put('/admin-profile', [AdminController::class, 'adminProfileUpdate'])->name('admin.admin-profile');
    Route::post('/get-all-belting-request', [AdminController::class, 'getAllBeltingRequests'])->name('admin.get-all-belting-request');
    Route::put('/update-belting-status', [AdminController::class, 'updateBeltingStatus'])->name('admin.update-belting-status');
    Route::get('/package-details/{id}', [PackageController::class, 'getPackageDetails'])->name('admin.package-detail');
    Route::get('/video/{id}', [PackageController::class, 'getVideo'])->name('user.video');
    Route::get('/payments', [AdminController::class, 'viewPayments'])->name('admin.payments');
    Route::post('/get-all-payments', [AdminController::class, 'getAllPayments']);
    Route::post('/delete-poster/{id}', [VideoController::class, 'deletePoster'])->name('admin.delete-poster');
    Route::post('/update-video-positions', [AdminController::class, 'updatePosition'])->name('admin.update-video-positions');
    Route::put('/edit-user-password/{id}', [AdminController::class, 'editUserPassword'])->name('admin.edit-user-password');
    Route::put('/block-user', [AdminController::class, 'blockUser'])->name('admin.block-user');
    Route::post('delete-free-video/{id}', [PackageController::class, 'deleteFreeVideo'])->name('admin.delete-free-video');
    Route::put('user-manual-subscription', [AdminController::class, 'userManualSubscription'])->name('admin.manual-subscription');
    Route::get('all-users', [AdminController::class, 'allUsersForSelect'])->name('admin.all-users');
    Route::get('user-not-subscribed-packages', [AdminController::class, 'userNotSubscribedPackages'])->name('admin.user-not-subscribed-package');
    Route::post('get-package-price', [AdminController::class, 'getPackagePrice'])->name('admin.get-package-price');
    Route::get('manage-user-subscription', [AdminController::class, 'manageUserSubscription'])->name('admin.manage-user-subscription');
    Route::get('manage-user-subscription-request', [AdminController::class, 'userSubscriptionRequest'])->name('admin.manage-user-subscription-request');
    Route::post('user-subscription-request-list', [AdminController::class, 'userSubscriptionRequestList'])->name('admin.user-subscription-request-list');
    Route::post('user-subscription-request-reject', [AdminController::class, 'userSubscriptionRequestReject'])->name('admin.user-subscription-request-reject');
    Route::get('user-subscription-request/{id}', [AdminController::class, 'getSubscriptionRequestDetails'])->name('admin.user-subscription-request-details');
    Route::post('user-subscription-list', [AdminController::class, 'userSubscriptionList'])->name('admin.user-subscription-list');
    Route::get('update-subscription/{user_id}/{package_id}', [AdminController::class, 'updateSubscription'])->name('admin.update-subscription');
    Route::put('update-subscription/{user_id}/{package_id}', [AdminController::class, 'submitUpdateSubscription'])->name('admin.submit-update-subscription');
    Route::get('add-subscription', [AdminController::class, 'addSubscription'])->name('admin.add-subscription');

});

