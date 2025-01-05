<?php

use App\Http\Controllers\Admin\CabinetController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\User\DonationDetailController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\JobBankController;
use App\Http\Controllers\User\LibraryController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\UserSubscriptionController;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;
use App\Models\Admin\LibraryAlbum;
use App\Models\ViewUserData;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes(['verify' => true]);

Route::get('/db-backup', function () {
    Artisan::call('database:backup');
    dump('Database backup done.');
});
Route::get('/subscription-plan', function() {
    $re = Artisan::call('user:subscription');
    dd($re);
});
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    dump('cleared');
});
Route::get('/show-resume', function () {
    return view('user.pdf.resume');
});
Route::get('/download', function () {
    return view('download');
});



Route::get('/run-mail-commands', function () {
    Artisan::call('send:mails');
    dd('Email triggered.');
});

Route::get('/cron', function () {
    Artisan::call('schedule:run');
    dd('schedule run.');
});

Route::get('/user-subscription-cron', function () {
    Artisan::call('user:subscription');
    echo 'cron run at '.now();
});

// Route::view('/email-template', 'mails.welcome-mail', $details);
Route::get('/email-template', function () {
    return view('mails.welcome-mail', [
        'user_name' => 'saad',
        'content'  => "<p>We are very happy to welcome you to the Mustafai Portral team .</p><p>You Are Approved By Admin and Now You can Login Into Mustafai Portral</p>",
        'links'    =>  "<a href='/'>Click Here To Login Page Of Mustafai</a>",
    ]);
});

//__library load on guest side_____________
Route::get('/library-load', [App\Http\Controllers\User\LibraryController::class, 'loadLibrary']);
Route::get('/validate-register', [App\Http\Controllers\User\HomeController::class, 'validateRegisteration']);



Route::get('/album', [App\Http\Controllers\Home\Pages\LibraryController::class, 'library']);
Route::get('/album-load', [App\Http\Controllers\Home\Pages\LibraryController::class, 'albumLoad']);
Route::get('/view-playlist', [App\Http\Controllers\Home\Pages\LibraryController::class, 'viewPlaylist'])->name('home.view.playlist');

Route::get('/', [App\Http\Controllers\Home\HomeController::class, 'index']);
Route::post('/upload-form/fileupload', [App\Http\Controllers\Admin\CommonController::class, 'importUsers'])->name('uploadusers');
//___ for move next prev__________//
Route::get('/move-lib', [App\Http\Controllers\User\LibraryController::class, 'moveLib']);
Route::get('/more-posts', [\App\Http\Controllers\Home\HomeController::class, 'getDynamicPosts']);
Route::get('/mustlibrary', [App\Http\Controllers\Home\Pages\LibraryController::class, 'allLibrary']);

Route::get('/all-testimonials', [App\Http\Controllers\Home\Pages\PagesController::class, 'allTestimonials']);
Route::get('/all-team', [App\Http\Controllers\Home\Pages\PagesController::class, 'allTeam']);
Route::get('/our-donations', [App\Http\Controllers\Home\Pages\PagesController::class, 'donations'])->name('our-donations');
Route::get('/events', [App\Http\Controllers\Home\Pages\PagesController::class, 'events']);
Route::get('/event/{id?}', [App\Http\Controllers\Home\Pages\PagesController::class, 'event'])->name('event.detail');
Route::get('/view-library/{type}', [App\Http\Controllers\Home\Pages\PagesController::class, 'viewLibrary']);
Route::get('/employee-details', [App\Http\Controllers\Home\Pages\EmployeeSectionController::class, 'employee']);

Route::get('get-payment-method', '\App\Http\Controllers\Home\DonationController@getDonationPaymentMethod');

Route::get('/headlines/{id?}', [\App\Http\Controllers\Home\HomeController::class, 'headlines'])->name('headlines');

// guest Store
Route::get('/mustafai-store', [\App\Http\Controllers\Home\Pages\ProductController::class, 'getProducts'])->name('guest.store');
// Route::post('/order-now', [\App\Http\Controllers\Home\Pages\ProductController::class, 'orderNowHome'])->name('order-now');
Route::post('/order-now', [\App\Http\Controllers\Home\Pages\ProductController::class, 'orderNowHome'])->name('order-now');
Route::post('/add-cart-guest', [\App\Http\Controllers\Home\Pages\ProductController::class, 'addToCartSession']);
Route::get('/get-cart-home', [\App\Http\Controllers\Home\Pages\ProductController::class, 'getCartHome']);
Route::get('/home.remove-cart', [\App\Http\Controllers\Home\Pages\ProductController::class, 'removeCart']);
Route::get('/magazines', [\App\Http\Controllers\Home\Pages\MagzineController::class, 'magzines'])->name('magazines');

Route::get('apply-profession-filter-addresses', [HomeController::class, 'applyFilterAddresses'])->name('profession.addresses.filter.apply');
Route::post('/profession-filter-addresses', [\App\Http\Controllers\Home\OccupationController::class, 'filterProfessionAddresses'])->name('profession.addresses.filter');


// Authentication Routes...
Route::get('admin', [App\Http\Controllers\Auth\Admin\LoginController::class, 'login'])->name('admin.auth.login');

Route::post('admin', [App\Http\Controllers\Auth\Admin\LoginController::class, 'loginAdmin'])->name('admin.auth.loginAdmin');

Route::any('admin/logout', [App\Http\Controllers\Auth\Admin\LoginController::class, 'logout'])->name('admin.auth.logout');

// Admin Dashbaord routes
Route::prefix('admin')->namespace('Admin')->group(static function () {
    Route::middleware('auth:admin')->group(function () {

        Route::post('users-validate', '\App\Http\Controllers\Admin\UserController@validateJquery')->name('admin.user.validate');
        Route::post('users-phone-validate', '\App\Http\Controllers\Admin\UserController@validatePhone')->name('admin.user.validatephone');
        Route::get('/user/show-occupation', '\App\Http\Controllers\Admin\UserController@userOccupation')->name('admin.user.show-occupation');

        Route::any('assign-book-issue', [\App\Http\Controllers\Admin\BookReceiptController::class,'assignBook'])->name('admin.assign-reciepts-issue');
        Route::any('book-receipts-leaf', [\App\Http\Controllers\Admin\BookReceiptController::class,'createLeaf'])->name('admin.create.leaf');

        Route::get('book-receipts-status', [\App\Http\Controllers\Admin\BookReceiptController::class,'changeStatus'])->name('admin.change.book.status');
        Route::get('book-receipts-log', [\App\Http\Controllers\Admin\BookReceiptController::class,'seeBookReceiptLogs'])->name('admin.book.logs');

        Route::any('book-leaf-update', [\App\Http\Controllers\Admin\BookReceiptController::class,'updateLeaf'])->name('admin.update.leaf');
        Route::get('/contacts-status', [App\Http\Controllers\Admin\ContactController::class, 'updateStatus']);
        Route::get('/subscription-status-update', [\App\Http\Controllers\Admin\UserController::class, 'subscriptionStatusUpdate']);
        Route::post('/subscription-receipt-update', [\App\Http\Controllers\Admin\UserController::class, 'subscriptionReceiptUpdate'])->name('admin.update.receipt');

        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('order-details', '\App\Http\Controllers\Admin\OrdersController@orderDetails');
        Route::get('order-change-status', '\App\Http\Controllers\Admin\OrdersController@updateOrderStatus');
        Route::get('virtual-link-mail', '\App\Http\Controllers\Admin\OrdersController@VirtualLinkEmail');
        Route::get('bussiness-request-change', '\App\Http\Controllers\Admin\BusinessPlanApplicationController@changeStatusRequest');
        Route::post('save-files-ajax/{libId}', '\App\Http\Controllers\Admin\LibraryController@saveFilesAjax');
        Route::resource('admins', '\App\Http\Controllers\Admin\AdminController');
        Route::resource('shipment-rate', '\App\Http\Controllers\Admin\ShipmentController');
        Route::post('update-pic', '\App\Http\Controllers\Admin\AdminController@profilePic');
        Route::get('profile', '\App\Http\Controllers\Admin\AdminController@profile');
        Route::post('profile', '\App\Http\Controllers\Admin\AdminController@profile');
        Route::resource('roles', '\App\Http\Controllers\Admin\RoleController');
        Route::resource('users', '\App\Http\Controllers\Admin\UserController');
        Route::get('user/get-subscription-details', [\App\Http\Controllers\Admin\UserController::class, 'getSubscriptionDetails'])->name('admin.users.subscription-details');
        Route::get('users/{user}/subscription', [\App\Http\Controllers\Admin\UserController::class, 'getSubscription'])->name('admin.users.subscription');
        Route::get('user/get-subscription-details-save', [\App\Http\Controllers\Admin\UserController::class, 'getSubscriptionDetailsSave'])->name('admin.users.subscription-details-save');
        Route::post('user/show', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.user.show');
        Route::post('user/show-subscription', [\App\Http\Controllers\Admin\UserController::class, 'showSubscription'])->name('admin.subscription.show');
        Route::post('filter-occupation', [\App\Http\Controllers\Admin\UserController::class, 'filterOccupation'])->name('admin.user.filter-occupation');
        Route::resource('events', '\App\Http\Controllers\Admin\EventController');
        Route::get('event-attendes/{id?}', [\App\Http\Controllers\Admin\EventController::class, 'listEventAttendes'])->name('event.attendes');
        Route::post('delete-event-attendes/{id}', [\App\Http\Controllers\Admin\EventController::class, 'deleteEventAttendes'])->name('delete-event-attendes');
        Route::resource('library', '\App\Http\Controllers\Admin\LibraryController');
        Route::resource('site-setting', '\App\Http\Controllers\Admin\SettingController');
        Route::resource('pages', '\App\Http\Controllers\Admin\PagesController');
        // Route::resource('thumbnails', '\App\Http\Controllers\Admin\ThumbnailController');
        Route::post('update-thumb-img/{id}', '\App\Http\Controllers\Admin\LibraryController@updateThumbImg');

        // slider
        Route::resource('slider', '\App\Http\Controllers\Admin\SliderController');
        // ceo message
        Route::resource('ceomessage', '\App\Http\Controllers\Admin\CeoMessageController');
        Route::resource('header-settings', '\App\Http\Controllers\Admin\HeaderSettingController');

        Route::resource('announcements', '\App\Http\Controllers\Admin\AnnouncementController');
        Route::resource('headlines', '\App\Http\Controllers\Admin\HeadlinesController');
        Route::resource('occupations', '\App\Http\Controllers\Admin\OccupationController');

        // library sections
        Route::resource('library-section', '\App\Http\Controllers\Admin\librarySectionsController');
        Route::post('create-directory', [\App\Http\Controllers\Admin\LibraryController::class, 'createDirectory'])->name('admin.create.directory');
        Route::get('dir-details', [\App\Http\Controllers\Admin\LibraryController::class, 'dirDetails'])->name('admin.dir.details');
        Route::post('dir-details', [\App\Http\Controllers\Admin\LibraryController::class, 'dirDetails'])->name('admin.save.dir.details');
        Route::get('open-dir', [\App\Http\Controllers\Admin\LibraryController::class, 'openDir'])->name('admin.open.dir');
        Route::get('remove-dir', [\App\Http\Controllers\Admin\LibraryController::class, 'removeDir'])->name('admin.remove.dir');

        Route::get('view-playlist', [\App\Http\Controllers\Admin\LibraryController::class, 'viewPlaylist'])->name('admin.view.playlist');
        // Route::post('dir-details', [\App\Http\Controllers\Admin\LibraryController::class, 'dirDetails'])->name('admin.dir.details');


        Route::resource('posts', '\App\Http\Controllers\Admin\PostController');
        Route::post('posts/show', [\App\Http\Controllers\Admin\PostController::class, 'show'])->name('admin.post.show');
        Route::post('posts/status/{id}', [\App\Http\Controllers\Admin\PostController::class, 'status'])->name('admin.post.status');
        Route::resource('products', '\App\Http\Controllers\Admin\ProductsController');
        Route::post('product-post', [\App\Http\Controllers\Admin\ProductsController::class, 'productPost'])->name('admin.product-post');
        Route::resource('categories', '\App\Http\Controllers\Admin\CategoriesController');
        Route::resource('magazine-categories', '\App\Http\Controllers\Admin\MagazineCategoryController');
        Route::resource('magazines', '\App\Http\Controllers\Admin\MagazineController');
        Route::resource('banks', '\App\Http\Controllers\Admin\BankController');
        Route::resource('bank-accounts', '\App\Http\Controllers\Admin\BankAccountController');
        Route::resource('donation-categories', '\App\Http\Controllers\Admin\DonationCategoryController');
        Route::resource('office-addresses', '\App\Http\Controllers\Admin\OfficeAddressController');
        Route::get('office-addresses/featured-address/{id}', '\App\Http\Controllers\Admin\OfficeAddressController@setFeaturedAddress');
        Route::resource('testimonials', '\App\Http\Controllers\Admin\TestimonialsController');

        //________________ featured testimonials_________//
        Route::get('testimonials/featured-testimonials/{id}', [App\Http\Controllers\Admin\TestimonialsController::class, 'setFeaturedtesTimonials'])->name('admin.featured.testimonials');


        // Editor Images
        Route::post('/uploadimage', '\App\Http\Controllers\Admin\CommonController@uploadEditoImage')->name('admin.uploadeditoimage');
        Route::post('/deleteimage', '\App\Http\Controllers\Admin\CommonController@deleteEditoImage')->name('admin.deleteeditorimage');

        //Employe assign Album
        Route::get('assign-album', [\App\Http\Controllers\Admin\EmployeeSectionController::class, 'assignAlbum'])->name('admin.assign.album');
        Route::post('assign-album', [\App\Http\Controllers\Admin\EmployeeSectionController::class, 'assignAlbum'])->name('admin.assign.save.album');

        Route::resource('employee-sections', '\App\Http\Controllers\Admin\EmployeeSectionController');
        Route::resource('sections', '\App\Http\Controllers\Admin\SectionController');
        Route::resource('book-receipts', '\App\Http\Controllers\Admin\BookReceiptController');

        Route::resource('countries', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('orders', '\App\Http\Controllers\Admin\OrdersController');
        Route::resource('provinces', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('divisions', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('districts', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('tehsils', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('cities', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('branches', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('union-councils', '\App\Http\Controllers\Admin\AddressController');
        Route::resource('vendors', '\App\Http\Controllers\Admin\VendorController');
        Route::resource('donors', '\App\Http\Controllers\Admin\DonorsController');
        Route::resource('donations', '\App\Http\Controllers\Admin\DonationController');
        Route::get('donations/reciepts/{id}', '\App\Http\Controllers\Admin\DonationController@reciepts');
        Route::any('donation-reciepts/show', [\App\Http\Controllers\Admin\DonationController::class, 'show'])->name('admin.donation-reciepts.show');
        Route::get('donations-show-transfer-payment', '\App\Http\Controllers\Admin\DonationController@showTransferPayment');
        Route::post('donations/reciept-delete/{id}', '\App\Http\Controllers\Admin\DonationController@recieptDelete');
        Route::get('donations/reciept-status/{id}', '\App\Http\Controllers\Admin\DonationController@recieptStatus');
        Route::get('products/featured-product/{id}', '\App\Http\Controllers\Admin\ProductsController@setFeaturedProduct');
        Route::get('pages/featured-product/{id}', '\App\Http\Controllers\Admin\PagesController@setFeaturedPage');

        Route::get('getproductdetails', '\App\Http\Controllers\Admin\ProductsController@getProductDetails');
        Route::post('get-product-info', [\App\Http\Controllers\User\ProductController::class, 'productDetails'])->name('product.shop')->withoutMiddleware('auth:admin');
        Route::post('shop-product', [\App\Http\Controllers\User\ProductController::class, 'orderProduct'])->name('order.product')->withoutMiddleware('auth:admin');
        Route::get('featured-donation/{id}', [App\Http\Controllers\Admin\DonationController::class, 'setFeaturedDonation'])->name('admin.featured.donation');
        Route::resource('subscriptions', '\App\Http\Controllers\Admin\SubscriptionController');
        Route::get('change-subscription-status/{id}', '\App\Http\Controllers\Admin\SubscriptionController@ChangeSubscriptionStatus');
        Route::resource('contacts', '\App\Http\Controllers\Admin\ContactController');
        Route::any('contacts/show', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('admin.contacts.show');

        // headline and testimonial order
        Route::post('/headline-order', [\App\Http\Controllers\Admin\HeadlinesController::class, 'updateOrder'])->name('headline.order');
        Route::post('/testimonial-order', [\App\Http\Controllers\Admin\TestimonialsController::class, 'updateOrder'])->name('testimonial.order');
        Route::post('/role-order', [\App\Http\Controllers\Admin\RoleController::class, 'updateOrder'])->name('role.order');
        Route::post('/subscription-charges', [\App\Http\Controllers\Admin\RoleController::class, 'subscriptionCharge'])->name('role.subscription-charges');


        // notifications
        Route::get('notifications', [\App\Http\Controllers\Admin\DashboardController::class, 'notifications'])->name('admin.notifications');
        Route::post('read-notification', [NotificationController::class, 'readNotification'])->name('admin-notification.read');
        Route::get('read-notifications', [NotificationController::class, 'readNotifications'])->name('admin-notifications.read');

        //user approve
        Route::get('user-approve-feature/{id}', '\App\Http\Controllers\Admin\UserController@changeUserLoginStatus');

        Route::resource('cabinets', '\App\Http\Controllers\Admin\CabinetController');
        Route::post('cabinetsaddress', '\App\Http\Controllers\Admin\CabinetController@addressFunction')->name('admin.cabinet-address');
        Route::get('cabientUsersSelect', '\App\Http\Controllers\Admin\CabinetController@cabientUsersSelect')->name('admin.cabinet-user-select');

        Route::post('list-cabinet-users', [CabinetController::class, 'cabinetUsers'])->name('cabinet.users');
        Route::post('cabinet-users-update', [CabinetController::class, 'updateCabinetUsers'])->name('cabinet.users.update');

        // Busines booster
        Route::get('business-plans/applications', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'index'])->name('admin.business.applications');
        Route::get('business-plans/requests', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'requests'])->name('admin.business.requests');
        Route::get('business-plans/invoices', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'showInvoices']);
        Route::resource('busines_plans', '\App\Http\Controllers\Admin\BusinesPlanController');
        Route::post('busines_plans/show', [\App\Http\Controllers\Admin\BusinesPlanController::class, 'show'])->name('admin.business.show');
        Route::post('business_application/show', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'show'])->name('admin.business.application.show');
        Route::post('business_application/status/{id}', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'status'])->name('admin.business.application.status');
        Route::get('business_application/invoices/status/{id}', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'invoiceStatus'])->name('admin.business.application.invoice.status');
        Route::post('business_application/relief-dates', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'reliefDates'])->name('admin.business.application.relief-dates');
        Route::post('business_application/relief-dates-add', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'addReliefDates'])->name('admin.business.application.relief-dates-add');

        Route::resource('business-heading', '\App\Http\Controllers\Admin\BusinessHeadingController');

        Route::get('busines_plans/invoices/{id}', [\App\Http\Controllers\Admin\BusinesPlanController::class, 'planInvoices'])->name('admin.business.invoices');

        Route::post('business_application/pay-now-details', [\App\Http\Controllers\Admin\BusinesPlanController::class, 'payNowDetails'])->name('admin.business.application.pay-now-details');
        Route::post('business_application/pay-now', [\App\Http\Controllers\Admin\BusinesPlanController::class, 'payNow'])->name('admin.business.application.pay-now');
        Route::post('send-reminder-email-for-invoice', [\App\Http\Controllers\Admin\BusinessPlanApplicationController::class, 'sendReminderEmail'])->name('invoice.reminder');

        Route::get('defaulter', [\App\Http\Controllers\Admin\BusinesPlanController::class, 'defaulter']);

        Route::resource('donation-payment-method', '\App\Http\Controllers\Admin\DonationPaymentMethodController');
        Route::resource('product-payment-method', '\App\Http\Controllers\Admin\ProductPaymentMethodController');

        Route::get('/get-payment-fields/{id}/{action}', '\App\Http\Controllers\Admin\DonationPaymentMethodController@getPaymentFields');
        Route::get('/delete-product-payment', [\App\Http\Controllers\Admin\ProductPaymentMethodController::class, 'deleteProductPayment'])->name('admin.delete-product-payment');

        Route::get('/delete-donation-payment', [\App\Http\Controllers\Admin\DonationPaymentMethodController::class, 'deleteDonationPayment'])->name('admin.delete-donation-payment');
        //libray routes
    });
});

Route::get('get-prayer-times', [App\Http\Controllers\Home\HomeController::class, 'getPrayerTimes']);
Route::get('after-register-message', [App\Http\Controllers\Home\HomeController::class, 'afterRegisterMessage']);

Route::POST('subscription', [App\Http\Controllers\Home\HomeController::class, 'subscription']);
Route::POST('/contact-form', [App\Http\Controllers\Home\HomeController::class, 'contactForm']);
Route::get('/library-tabs', [App\Http\Controllers\Home\HomeController::class, 'librarySections'])->name('library.tabs');
Route::post('/donate', [App\Http\Controllers\Home\DonationController::class, 'donate']);

Route::get('change-lang', '\App\Http\Controllers\Home\HomeController@setLanguageLocal')->name('changeLang');
Route::get('get-feature-donation-product', '\App\Http\Controllers\Home\HomeController@getFeatureDonationProduct');
Route::any('contact-us', '\App\Http\Controllers\Home\HomeController@contactForm');
Route::any('search', '\App\Http\Controllers\Home\SearchController@index')->name('search');

// Occupations
Route::any('professions/{slug}', '\App\Http\Controllers\Home\OccupationController@index')->where('slug', '^[a-z0-9-]+$')->name('occupations');
Route::get('/share-post', [\App\Http\Controllers\Home\HomeController::class, 'sharePostSocialMedia'])->name('user.post.share.social');
Route::get('api/flipbook-api', [\App\Http\Controllers\Home\HomeController::class, 'flipBookAPI']);

Route::get('/search-library', [\App\Http\Controllers\Home\Pages\LibraryController::class, 'getLibraryData'])->name('search.library');

Route::get('/cabinet-user-roles', [App\Http\Controllers\Home\HomeController::class, 'cabinetUserRole'])->name('cabinet.user.roles');
Route::get('{slug}', [App\Http\Controllers\Home\HomeController::class, 'page'])->where('slug', '^[a-z0-9-]+$')->name('page');

Route::any('/donor', [ProfileController::class, 'donor'])->name('guest.donor');

//user dashbaord routes
Route::prefix('user')->group(static function () {
    Route::middleware(['verified', 'is_profile_complete'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/terms-condition', [HomeController::class, 'termsCondition'])->name('user.terms.condition');
        Route::get('/dashboard/{id}', [HomeController::class, 'specificPost'])->name('user.specific-post');
        Route::get('/user-list', [HomeController::class, 'userList'])->name('user.list');
        Route::get('/blood-bank', [HomeController::class, 'bloodBank'])->name('user.blood-bank');

        Route::post('/blood-bank-post', [\App\Http\Controllers\User\BloodBankController::class, 'store'])->name('user.blood.post');
        Route::get('/user-library', [LibraryController::class, 'userLibrary'])->name('user.library');

        // Route::get('/library-load', [LibraryController::class, 'loadLibrary'])->name('user.library.load');
        Route::get('/job-bank/{isNotification?}', [HomeController::class, 'jobBank'])->name('user.job-bank');
        Route::get('/job-bank-hiring', [HomeController::class, 'hiringDataTable'])->name('user.job-bank-hiring');
        Route::get('/job-bank-resume', [HomeController::class, 'resumeDataTable'])->name('user.job-bank-resume');
        Route::post('/job-bank/view-applicants', [HomeController::class, 'applicants'])->name('user.job-bank-applicants');
        Route::post('/view-cabinet-users', [HomeController::class, 'cabinetUsers'])->name('user.view-cabinet-users');
        Route::post('/create-job-post', [JobBankController::class, 'createJobPost'])->name('user.create.job-post');
        Route::post('/apply-job', [JobBankController::class, 'applyJob'])->name('user.apply-job');
        Route::get('/user-tabs', [HomeController::class, 'userList'])->name('user.tabs');
        Route::get('/cabinent-list', [HomeController::class, 'cabinentList'])->name('user.cabinent-list');
        Route::get('/donate', [HomeController::class, 'donate'])->name('user.donate');
        Route::any('profile-settings', [\App\Http\Controllers\User\ProfileController::class, 'profileSettings'])->name('profile.settings');
        /*posts*/
        Route::post('/create-post', [\App\Http\Controllers\User\PostController::class, 'createPost'])->name('user.post.create');
        Route::post('/show-post', [\App\Http\Controllers\User\PostController::class, 'showPost'])->name('user.post.show');
        Route::post('/edit-post', [\App\Http\Controllers\User\PostController::class, 'updatePost'])->name('user.post.update');
        Route::post('/delete-post', [\App\Http\Controllers\User\PostController::class, 'deletePost'])->name('user.post.delete');

        /* search */
        Route::any('search', [\App\Http\Controllers\User\SearchController::class, 'index'])->name('user.search');

        /* activites */
        Route::get('/activity', [\App\Http\Controllers\User\ActivityController::class, 'index'])->name('user.activity');

        // notifications
        Route::get('/notifications', [HomeController::class, 'notifications'])->name('user.notifications');
        Route::get('/header-notifications', [NotificationController::class, 'headerNotifications'])->name('user.header-notifications');
        Route::post('read-notification', [NotificationController::class, 'readUserNotification'])->name('notification.read');
        Route::get('read-notifications', [NotificationController::class, 'readUserNotifications'])->name('notifications.read');

        // profile
        Route::withoutMiddleware('is_profile_complete')->group(function () {
            Route::get('/profile/{id?}', [ProfileController::class, 'index'])->name('user.profile');
            // addresses
            Route::post('filter-addresses', [HomeController::class, 'filterAddresses'])->name('user.addresses.filter');
            Route::post('/profile/address', [ProfileController::class, 'updateAddress'])->name('user.address');
            Route::get('/get-notifications-counter', [NotificationController::class, 'getNotificationCounter'])->name('user.get-notofications-counter');
            Route::get('/subscriptions', [UserSubscriptionController::class, 'index'])->name('user.subscriptions');
            Route::post('/profile/banner', [ProfileController::class, 'updateBanner'])->name('user.banner');
            Route::post('/profile/image', [ProfileController::class, 'updateProfileImage'])->name('user.image');
            Route::post('/profile/tagline', [ProfileController::class, 'updateTagline'])->name('user.tagline');
            Route::post('/profile/username', [ProfileController::class, 'updateUserName'])->name('user.username');
            Route::post('/profile/cnic', [ProfileController::class, 'updateUserCnic'])->name('user.cnic');
            Route::post('/profile/experience', [ProfileController::class, 'addExperience'])->name('user.experience');
            Route::post('/profile/experience/edit', [ProfileController::class, 'editExperience'])->name('user.experience-edit');
            Route::post('/profile/get-experience', [ProfileController::class, 'getExperience'])->name('user.experience-get');
            Route::post('/profile/experience/destroy', [ProfileController::class, 'destroyExperience'])->name('user.experience-destroy');
            Route::post('/profile/education', [ProfileController::class, 'addEducation'])->name('user.education');
            Route::post('/profile/education/edit', [ProfileController::class, 'editEducation'])->name('user.education-edit');
            Route::post('/profile/get-education', [ProfileController::class, 'getEducation'])->name('user.education-get');
            Route::post('/profile/education/destroy', [ProfileController::class, 'destroyEducation'])->name('user.education-destroy');
            Route::any('/profile/about', [ProfileController::class, 'about'])->name('user.about');
            Route::any('/profile/blood', [ProfileController::class, 'updateBlood'])->name('user.blood');
            Route::any('/donor', [ProfileController::class, 'donor'])->name('user.donor');
            Route::any('/create-resume', [ProfileController::class, 'editResume'])->name('user.create.resume');
            Route::any('/skills', [ProfileController::class, 'skills'])->name('user.skills');
            Route::post('/profile/visibility', [ProfileController::class, 'userProfileVisibility'])->name('user.visibility');
            Route::post('/profile/occupation', [ProfileController::class, 'addOccupation'])->name('user.occupation');
            Route::post('/profile/show-occupation', [ProfileController::class, 'userOccupation'])->name('user.show-occupation');
            Route::post('/profile/friend-request', [ProfileController::class, 'friendRequest'])->name('user.friend-request');
            Route::post('/store-branch', [ProfileController::class, 'storeBranch'])->name('user.store-branch');

            // routes without is-profile-complete middleware
            // Route::get('/get-notifications-counter', [NotificationController::class, 'getNotificationCounter'])->name('user.get-notofications-counter');


            Route::get('apply-filter-addresses', [HomeController::class, 'applyFilterAddresses'])->name('user.addresses.filter.apply');

            // change lang route
            Route::get('change-lang-user', '\App\Http\Controllers\User\HomeController@setLanguageLocal')->name('user.change-lang');
        });


        // Store
        Route::get('/mustafai-store', [\App\Http\Controllers\User\ProductController::class, 'getProducts'])->name('user.store');
        Route::any('/add-cart', [\App\Http\Controllers\User\ProductController::class, 'addCart'])->name('user.add-cart');
        Route::get('/get-cart', [\App\Http\Controllers\User\ProductController::class, 'getCart'])->name('user.get-cart');
        Route::post('/order-now', [\App\Http\Controllers\User\ProductController::class, 'orderNow'])->name('user.order-now');
        Route::get('/remove-cart', [\App\Http\Controllers\User\ProductController::class, 'removeCart'])->name('user.remove-cart');
        Route::any('/my-orders', [ProductController::class, 'myOrders'])->name('user.my-orders');

        // My Subscriptions
        Route::any('/my-subscriptions', [SubscriptionController::class, 'mySubscriptions'])->name('user.my-subscriptions');
        Route::get('pay-subscription/{id}', [SubscriptionController::class, 'paySubscription'])->name('user.pay-subscription');
        Route::post('pay-subscription', [SubscriptionController::class, 'payUserSubscription'])->name('user.pay-user-subscription');

        // Chats
        Route::any('/chats', [\App\Http\Controllers\User\ChatController::class, 'getChatScreen'])->name('user.chats');
        Route::get('/get-chats', [\App\Http\Controllers\User\ChatController::class, 'getChats'])->name('user.get-chats');
        Route::post('/send-message', [\App\Http\Controllers\User\ChatController::class, 'sendMessage'])->name('user.send-message');
        Route::get('/friend-requests', [\App\Http\Controllers\User\ChatController::class, 'getFriendRequests'])->name('user.friend-requests');
        Route::get('/response-friend-requests', [\App\Http\Controllers\User\ChatController::class, 'responseRequest'])->name('user.response-request');
        Route::post('/create-contact-group', [\App\Http\Controllers\User\ChatController::class, 'createContactGroup'])->name('user.create-request');
        Route::get('/get-contact-group', [\App\Http\Controllers\User\ChatController::class, 'getContactGroup'])->name('user.get-group');
        Route::post('/delete-group', [\App\Http\Controllers\User\ChatController::class, 'deleteGroup'])->name('user.delete-group');
        Route::post('/upload-junk-file', [\App\Http\Controllers\User\ChatController::class, 'saveFilesAjax'])->name('user.chat.attachment.upload');
        Route::post('/delete-conversation', [\App\Http\Controllers\User\ChatController::class, 'deleteConversation'])->name('user.delete-conversation');
        Route::put('/delete-select-message', [\App\Http\Controllers\User\ChatController::class, 'deleteSelectedMessage'])->name('user.delete-select-message');

        // Busines booster
        Route::get('/busines_plans', [App\Http\Controllers\User\BusinesPlanController::class, 'plans'])->name('user.busines-plan');
        Route::get('/get-plan-details', [App\Http\Controllers\User\BusinesPlanController::class, 'getPlanDetails'])->name('user.get-busines-plan-details');
        Route::post('/submit-application', [App\Http\Controllers\User\BusinesPlanController::class, 'submitApplication'])->name('user.submit.plan-application');
        Route::get('/busines_plans/download', [App\Http\Controllers\User\BusinesPlanController::class, 'downloadApplication'])->name('user.submit.plan-application-downlaod');
        Route::get('/busines_plans/download-test', [App\Http\Controllers\User\BusinesPlanController::class, 'downloadApplicationTest']);
        Route::post('/submit-application-date-request', [App\Http\Controllers\User\BusinesPlanController::class, 'submitDateChangeApplication'])->name('user.submit.plan-application-date-request');
        Route::post('/pay-invoices', [App\Http\Controllers\User\BusinesPlanController::class, 'payInvoice'])->name('user.invoice.pay');
        Route::get('/invoices/{id}', [App\Http\Controllers\User\BusinesPlanController::class, 'invoices'])->name('user.plan.invoices');
        Route::get('/donation-details', [DonationDetailController::class, 'showDonationDetails'])->name('user.donation.details');
        Route::get('/donation-partial', [DonationDetailController::class, 'getDonationPartial'])->name('user.donation.partial');
        Route::get('/donation-tables', [DonationDetailController::class, 'getDonationTables'])->name('user.donation.tables');
        //library load
        Route::get('/library-load', [App\Http\Controllers\User\LibraryController::class, 'loadLibrary']);
        Route::get('order-details', '\App\Http\Controllers\Admin\OrdersController@orderDetails');


    });
});
// Route::get('/donation-tables', [DonationDetailController::class, 'getDonationTables'])->name('user.donation.tables');

// guest routes

Route::post('/guest-apply-job', [JobBankController::class, 'guestApplyJob'])->name('guest.apply-job');

/*user posts*/
Route::post('/user-like-post', [\App\Http\Controllers\User\UserLikePostController::class, 'likePost'])->name('user.post.like');
Route::post('/user-comment-post', [\App\Http\Controllers\User\UserCommentPostController::class, 'commentPost'])->name('user.post.comment');
Route::post('/user-comment-post-delete', [\App\Http\Controllers\User\UserCommentPostController::class, 'deleteComment'])->name('user.post.comment.delete');
Route::post('/user-share-post', [\App\Http\Controllers\User\UserSharePostController::class, 'sharePost'])->name('user.post.share');
