<?php



use App\Http\Controllers\Admin\OrdersController;

use App\Http\Controllers\Api\AboutUsController;

use App\Http\Controllers\Api\AddressController;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\BusinesPlanController;

use App\Http\Controllers\Api\ChatController;

use \App\Http\Controllers\Api\CmsController;

use App\Http\Controllers\Api\ContactUsController;

use App\Http\Controllers\Api\DonationController;

use App\Http\Controllers\Api\DonorController;

use App\Http\Controllers\Api\EventController;

use App\Http\Controllers\Api\HomeController;

use App\Http\Controllers\Api\LibraryController;

use App\Http\Controllers\Api\NewsController;

use App\Http\Controllers\Api\PostController;

use App\Http\Controllers\Api\ProductController;

use App\Http\Controllers\Api\ProfileController;

use \App\Http\Controllers\Api\TeamController;

use App\Http\Controllers\Api\TestimonialController;

use App\Http\Controllers\Api\UserController;

use \App\Http\Controllers\Api\UserFiltrationController;

use \App\Http\Controllers\Api\OrderController;

use \App\Http\Controllers\Api\OccupationController;

use \App\Http\Controllers\Api\CommonController;

use \App\Http\Controllers\Api\GuestOrderController;

use \App\Http\Controllers\Api\SubscriptionController;

use \App\Http\Controllers\Api\PrayerController;

use \App\Http\Controllers\Api\MagazineController;

use \App\Http\Controllers\Api\NotificationController;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;



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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();

});

Route::any('professions/{slug}', '\App\Http\Controllers\Home\OccupationController@indexApp')->middleware('web');

Route::any('home/professions/{slug}', '\App\Http\Controllers\Home\OccupationController@indexHomeProfessionApp');

Route::get('home', [HomeController::class, 'home']);

Route::post('getLibraryCount', [\App\Http\Controllers\Api\LibraryController::class, 'getLibrary']);

Route::post('getLibraryDetails', [\App\Http\Controllers\Api\LibraryController::class, 'getLibraryDetails']);

Route::get('forgetPassword', [AuthController::class, 'forgetPassword']);

Route::get('getProfessionsList', [HomeController::class, 'getProfessionsList']);



Route::get('getDonationCategories', [DonationController::class, 'getDonationCategories']);

Route::get('donationDetails', [DonationController::class, 'donationDetails']);

Route::get('getDonationPaymentHistory', [DonationController::class, 'getDonationPaymentHistory']);

Route::get('getDonationList', [DonationController::class, 'getDonationList']);

Route::post('doDonate', [DonationController::class, 'doDonate']);

Route::get('paymentMethods', [DonationController::class, 'paymentMethods']);

Route::get('getPaymentMethodDetails', [DonationController::class, 'getPaymentMethodDetails']);

Route::get('getAboutInfo', [HomeController::class, 'getAboutInfo']);

Route::get('getCountryCodes', [AddressController::class, 'getCountryCodes']);

Route::get('getAddresses', [AddressController::class, 'getAddresses']);

/**Countries Cities adnd Addresses **/

Route::get('filterAddresses', [AddressController::class, 'filterAddresses']);

Route::post('filterUserListAddresses', [AddressController::class, 'filterUserListAddresses']);

Route::get('getCountries', [AddressController::class, 'getCountries']);



Route::get('getCities', [CommonController::class, 'getCities']);



Route::group(['middleware' => 'lang_required'], function () {

    // Route::get('getNewsDetails', [NewsController::class, 'getNews']);

    Route::get('getMustafaiContactInfo', [ContactUsController::class, 'getMustafaiContactInfo']);

    Route::get('getTestimonials', [TestimonialController::class, 'getTestimonials']);

    Route::post('getNewsDetails', [NewsController::class, 'getNews']);

    Route::get('aboutUs', [AboutUsController::class, 'aboutUs']);

    Route::post('ourTeam', [TeamController::class, 'ourTeam']);

    Route::get('page', [CmsController::class, 'getCmsPage']);

    Route::post('/joinEvent', [\App\Http\Controllers\Api\MustafaiEvent::class, 'joinEvent'])->name('event.create.api');

    Route::post('/getEventDetails', [EventController::class, 'getEventDetails']);

    Route::get('/getAllEvents', [EventController::class, 'getAllEvents']);

    Route::post('/getMustafaiStore', [ProductController::class, 'getMustafaiStore']);

    Route::post('/getFeaturedProduct', [ProductController::class, 'getFeaturedProduct']);

    Route::get('getAllLibrary', [LibraryController::class, 'getLibrary']);

    Route::post('getLibraryDetails', [LibraryController::class, 'getLibraryDetails']);

    Route::post('getLibraryAlbumDetails', [LibraryController::class, 'getLibraryAlbumDetails']);

    Route::post('getOccupations', [OccupationController::class, 'getOccupations']);

    Route::post('userOccupation', [OccupationController::class, 'userOccupation']);

    Route::get('magazines', [MagazineController::class, 'magzines']);

    Route::get('magazineCategory', [MagazineController::class, 'magazineCategory']);

});



Route::post('contactUs', [HomeController::class, 'contactUS']);

Route::get('settings', [HomeController::class, 'settings']);

Route::post('getAllPosts', [PostController::class, 'getAllPosts']);

Route::post('getPostDetails', [PostController::class, 'getPostDetails']);

/**REMOVE from Authentication Middlware **/

Route::post('postLike', [PostController::class, 'likePost']);

Route::post('addCommentOnPost', [PostController::class, 'commentPost']);

Route::post('saveDonor', [DonorController::class, 'saveDonor']);

/** CART API ROUTES**/

Route::post('addToCart', [OrderController::class, 'addToCart']);

Route::get('removeCart', [OrderController::class, 'removeCart']);

Route::get('getCart', [OrderController::class, 'getCart']);

Route::post('orderNow', [OrderController::class, 'orderNow']);





Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('userList', [HomeController::class, 'userList']);

    Route::get('cabinetUsers', [HomeController::class, 'cabinetUsers']);

    Route::post('getUserActivities', [UserController::class, 'getUserActivities']);

    //Route::post('addToCart', [OrderController::class, 'addToCart']);

    Route::post('getPosting', [PostController::class, 'index']);

    // Route::post('postLike', [PostController::class, 'likePost']);

    // Route::post('addCommentOnPost', [PostController::class, 'commentPost']);

    Route::post('getUserPosts', [PostController::class, 'getUserPosts']);

    Route::post('deleteUserPost', [PostController::class, 'destroy']);

    Route::post('addUserPost', [PostController::class, 'addUserPost']);



    Route::post('getProfile', [ProfileController::class, 'getProfile']);

    Route::post('updateProfile', [ProfileController::class, 'updateProfile']);

    Route::post('addExperience', [ProfileController::class, 'addExperience']);

    Route::post('deleteExperience', [ProfileController::class, 'destroyExperience']);

    Route::post('addEducation', [ProfileController::class, 'addEducation']);

    Route::post('addSkills', [ProfileController::class, 'addSkills']);

    Route::post('create-resume', [ProfileController::class, 'editResume']);

    Route::post('changePassword', [ProfileController::class, 'changePassword']);

    Route::get('/alladdress/{id?}',  [ProfileController::class, 'index']);

    Route::post('/profile/address', [ProfileController::class, 'updateAddress']);

    Route::post('/destroyUser', [ProfileController::class, 'destroyUser']);



    Route::post('getContacts', [ChatController::class, 'getContacts']);

    Route::post('getChatGroupHead', [ChatController::class, 'getChatGroupHead']);

    Route::post('getMessages', [ChatController::class, 'getMessages']);

    Route::post('sendMessage', [ChatController::class, 'sendMessage']);



    Route::post('createGroup', [ChatController::class, 'createGroup']);

    Route::post('deleteGroup', [ChatController::class, 'deleteGroup']);

    Route::post('deleteConversation', [ChatController::class, 'deleteConversation']);





    Route::group(['middleware' => 'lang_required'], function () {

        Route::get('getBusinessPlans', [BusinesPlanController::class, 'getBusinessPlans']);

    });

    // business booster

    Route::get('/getPlanDetails', [BusinesPlanController::class, 'getPlanDetails']);

    Route::post('submitApplicationDateChange', [BusinesPlanController::class, 'submitApplicationDateChange']);

    Route::post('submitBusinessApplication', [BusinesPlanController::class, 'submitApplication']);

    Route::get('getBusinesPlanDates', [BusinesPlanController::class, 'getBusinesPlanDates']);

    Route::post('getSubmittedBBInfo', [BusinesPlanController::class, 'getSubmittedBBInfo']);

    Route::post('businessApplicationInvoicesDates', [BusinesPlanController::class, 'bbInvoicesDates']);

    Route::post('bbSubmitInvoices', [BusinesPlanController::class, 'submitInvoices']);

    Route::get('downloadApplication', [BusinesPlanController::class, 'downloadApplication']);



    // filtration

    Route::get('/getUsersFilterContent', [UserFiltrationController::class, 'getUsersFilterContent']);

    Route::post('/getUsersListing', [UserFiltrationController::class, 'getUsersListing']);





    Route::get('getJobApplicant', [PostController::class, 'getJobApplicant']);

    /** END**/

    /** ORDER API ROUTE**/

    Route::get('getMyOrders', [OrderController::class, 'getMyOrders']);

    /** END**/



    Route::get('getFriendRequestList', [ChatController::class, 'friendRequests']);

    Route::post('sendFriendRequest', [ChatController::class, 'sendFriendRequest']);

    Route::get('getAvailableContacts', [ChatController::class, 'availableContacts']);

    Route::post('responseToFriendRequest', [ChatController::class, 'responseToFriendRequest']);

    Route::post('deleteSelectedMessage', [ChatController::class, 'deleteSelectedMessage']);

    Route::post('/logout', [AuthController::class, 'logout']);



    //subscription Module

    Route::get('pay-subscription-form-data', [SubscriptionController::class, 'paySubscription']);

    Route::get('user-subscriptions', [SubscriptionController::class, 'userSubscriptions']);

    Route::post('pay-user-subscription', [SubscriptionController::class, 'payUserSubscription']);

    Route::post('addOccupation', [OccupationController::class, 'addOccupation']);

    Route::get('notifications', [NotificationController::class, 'notifications']);

    Route::get('readUserNotifications', [NotificationController::class, 'readUserNotifications']);

    Route::get('getNotificationCounter', [NotificationController::class, 'getNotificationCounter']);

    Route::post('getBloodDonor', [DonorController::class, 'getBloodDonor']);



    Route::post('pushUserNotification', [NotificationController::class, 'pushUserNotification']);







});

    /** JOB API ROUTE**/

    Route::post('applyJob', [UserController::class, 'applyForJob']);

 // Occupations

  Route::post('userDeleteComment', [PostController::class, 'userDeleteComment']);



Route::post('/signIn', [AuthController::class, 'login']);

Route::post('/signUp', [AuthController::class, 'signUp']);

Route::get('/cabinetUserRole', [AuthController::class, 'cabinetUserRole']);



Route::post('/like-post', [PostController::class, 'likePost'])->name('post.like');

Route::post('/share-post', [PostController::class, 'sharePost'])->name('post.share');

Route::get('/get-social-links', [PostController::class, 'getSocialLinks'])->name('get-social-links');

Route::get('/likes-counter', [PostController::class, 'fetchRealTimeLikes'])->name('likes.counter');



Route::post('/comment-post', [PostController::class, 'commentPost'])->name('post.comment');

Route::post('deleteComment', [PostController::class, 'deleteComment'])->name('post.delete-comment');

Route::get('/comments-info', [PostController::class, 'fetchRealTimeComments'])->name('comments.info');





Route::post('/make-mustafai-event', [\App\Http\Controllers\Api\MustafaiEvent::class, 'store'])->name('event.create');



Route::get('/team-member-library_albums', [TeamController::class, 'librarySections']);

Route::get('/album', [LibraryController::class, 'library']);

Route::post('/guestAddToCart', [GuestOrderController::class, 'addToCartSession']);

Route::get('/getCartGuest', [GuestOrderController::class, 'getCartGuest']);

Route::post('/placeOrderGuest', [GuestOrderController::class, 'placeOrderGuest']);

Route::get('getLocation', [ProfileController::class, 'getLocation']);

Route::get('getProductCategories', [ProductController::class, 'getProductCategories']);

Route::get('getDailyPrayerTimes', [PrayerController::class, 'getDailyPrayerTimes']);

Route::get('getMonthlyPrayerTimes', [PrayerController::class, 'getMonthlyPrayerTimes']);



// Route::any('professions/{slug}', '\App\Http\Controllers\Home\OccupationController@index');

