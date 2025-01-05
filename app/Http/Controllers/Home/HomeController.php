<?php

namespace App\Http\Controllers\Home;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\CeoMessage;
use App\Models\Admin\Donation;
use App\Models\Admin\EmployeeSection;
use App\Models\Admin\Event;
use App\Models\Admin\HeaderSetting;
use App\Models\Admin\Headline;
use App\Models\Admin\Library;
use App\Models\Admin\LibraryAlbum;
use App\Models\Admin\LibraryType;
use App\Models\Admin\Page;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\Product;
use App\Models\Admin\Role;
use App\Models\Admin\Designation;
use App\Models\Admin\Section;
use App\Models\Admin\Setting;
use App\Models\Admin\Slider;
use App\Models\Admin\Testimonial;
use App\Models\ContactForm\ContactRecord;
use App\Models\Posts\Post\Post;
use App\Models\Subscription\NewSubscription;
use App\Models\User;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Nette\Utils\DateTime;
use Session;
use Validator;
use View;
use App\Helper\namazHelper;
use App\Models\Admin\Cabinet;
use App\Models\Admin\CabinetUser;
use App\Models\Admin\City;
use App\Models\Admin\LibraryAlbumDetails;
use App\Models\Admin\Magazine;
use File;
use LanguageDetection\Language;


class HomeController extends Controller
{
    public function __construct()
    {
    }

    /**
     *Get Home Page data
    */
    public function index(Request $request)
    {
        $data = [];
        $data['testimonials'] = $this->getTestiMonials();
        $data['pages'] = $this->getFeaturePages();
        $data['sliders'] = $this->getSliders();
        $data['ceoMessage'] = $this->getCeoMessage();
        $data['sections'] = $this->getSections();
        $data['employeeSections'] = $this->getEmployeeSections();
        $data['libraryTypes'] = $this->getLibraryTypes();
        $data['events'] = $this->getEvents();
        $data['getHeaderMenu'] = $this->getHeaderMenu();
        $data['posts'] = $this->getPosts($request);
        $data['video'] = $this->getVideo();
        $data['cities'] = $this->getCities();
        return view('home.index', $data);
    }

    /**
     *Get video for banner
    */
    public function getVideo()
    {
        $video = Setting::where('option_name', 'video')->first();
        if (!empty($video)) {
            return $video;
        }
    }

    /**
     *Get cms page
    */
    public function page($slug)
    {
        $query = getQuery(App::getLocale(), ['title', 'description']);
        $page = Page::select($query)->where('url', $slug)->first();
        return view('home.cms-page', compact('page'));
    }

    /**
     *Get cms featured page
    */
    public function getFeaturePages()
    {
        $query = array_merge(getQuery(App::getLocale(), ['title', 'short_description']), ['image']);
        $query[] = 'url';
        $pages = Page::select($query)->where(['status' => 1, 'is_feature' => 1])->get();
        return $pages;
    }

    /**
     *Get home testimonials
    */
    public function getTestiMonials()
    {
        $query = getQuery(App::getLocale(), ['name', 'message']);
        $query[] = 'image';
        $query[] = 'testimonial_order';
        return Testimonial::select($query)->where('is_featured', 1)
            ->orderBy(DB::raw('ISNULL(testimonial_order), testimonial_order'))
            ->limit(5)
            ->get();
    }

    /**
     *Get headlines for homepage
    */
    public function getHeadLines()
    {
        $query = getQuery(App::getLocale(), ['title']);
        return Headline::select($query)->where('status', 1)->orderBy('headline_order')->get();
    }

    /**
     *Get sliders for homepage
    */
    public function getSliders()
    {
        $query = getQuery(App::getLocale(), ['content']);
        $query[] = 'image';
        $lang = lang();
        return Slider::select($query)->addSelect(DB::raw("REPLACE(content_{$lang}, '*', ' ') AS content"))
            ->where('status', 1)
            ->orderBy('order_rows', 'ASC')
            ->get();
    }

    /**
     *Get ceo message for homepage
    */
    public function getCeoMessage()
    {
        $query = getQuery(App::getLocale(), ['message']);
        $query[] = 'image';
        return CeoMessage::select($query)->where('status', 1)->first();
    }

    /**
     *Get section for homepage
    */
    public function getSections()
    {
        $query = getQuery(App::getLocale(), ['name']);
        $query[] = 'id';
        return Section::select($query)->where('status', 1)->get();
    }

    /**
     *Get employee section for homepage
    */
    public function getEmployeeSections()
    {
        $query = getQuery(App::getLocale(), ['name', 'designation', 'short_description']);
        $query[] = 'image';
        $query[] = 'id';
        return EmployeeSection::select($query)->where('status', 1)->get();
    }

    /**
     *Get library types for homepage
    */
    public function getLibraryTypes()
    {
        $query = getQuery(App::getLocale(), ['title']);
        $query[] = 'id';
        return LibraryType::select($query)->where('status', 1)->get();
    }

    /**
     *Get posts for homepage
    */
    public function getPosts(Request $request)
    {
        $cols = array_merge(getQuery(App::getLocale(), ['title']), ['id', 'admin_id', 'user_id', 'post_type', 'job_type', 'hiring_company_name', 'product_id', 'city', 'hospital', 'address', 'occupation', 'skills', 'resume', 'experience', 'description_english']);
        $cityCols =  array_merge(getQuery(\Illuminate\Support\Facades\App::getLocale(), ['name']), ['id']);
        $posts = Post::select($cols)->with(['user', 'images', 'comments.user', 'likes', 'admin', 'citi' => fn ($q) => $q->select($cityCols)])
            ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
            ->active()
            ->latest()
            ->paginate(5);

        $posts->each(function ($post) {
            $post->lang ='urdu';
            if(!empty($post->title) || isset($post->title)){
                $ld = new Language(['en', 'ur']);
                $textD = $ld->detect($post->title);
                $post->lang = $textD['en'] === 0.0 ? 'urdu' : 'english';
            }
        });
        return $posts->withPath('/more-posts');
    }

    /**
     *Get load more posts for homepage
    */
    public function getDynamicPosts(Request $request)
    {
        $cols = array_merge(getQuery(App::getLocale(), ['title']), ['id', 'admin_id', 'user_id', 'post_type', 'job_type', 'hiring_company_name', 'product_id', 'city', 'hospital', 'address', 'occupation', 'skills', 'resume', 'experience', 'description_english']);
        $cityCols =  array_merge(getQuery(App::getLocale(), ['name']), ['id']);
        if ($request->ajax()) {
            $posts = Post::select($cols)->with(['images', 'comments.user', 'likes', 'admin', 'user', 'citi' => fn ($q) => $q->select($cityCols)])
                ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
                ->active()
                ->latest()
                ->paginate(5);
            $posts->each(function ($post) {
                $post->lang ='urdu';
                if(!empty($post->title) || isset($post->title)){
                    $ld = new Language(['en', 'ur']);
                    $textD = $ld->detect($post->title);
                    $post->lang = $textD['en'] === 0.0 ? 'urdu' : 'english';
                }
            });
            $posts = $posts->withPath('/more-posts');

            $view = view('home.partial.posts-partial', compact('posts'))->render();
            return response()->json(['html' => $view]);
        }
    }

    /**
     *Get header menu for homepage
    */
    public function getHeaderMenu()
    {
        $headerData = HeaderSetting::orderBy('order')->get();
        $pagesArray = [];
        $pages = [];
        foreach ($headerData as $key => $val) {
            if ($val->type == 2) {
                $pagesArray['header_data'] = $val;
                $pagesArray['header_data']['pagesdata'] = Page::whereIn('id', explode(",", $val->pages_ids))->get();
                $pages[] = $pagesArray;
            } else {
                $pagesArray['header_data'] = $val;
                $pages[] = $pagesArray;
            }
        }
        return $pages;
    }

    /**
     *Get events for homepage
    */
    public function getEvents()
    {

        $query = getQuery(App::getLocale(), ['title', 'content', 'location']);
        $query[] = 'start_time';
        $query[] = 'end_time';
        $query[] = 'image';
        $query[] = 'start_date_time';
        $query[] = 'end_date_time';
        $query[] = 'id';
        //        $today = new \Nette\Utils\DateTime('today');
        $today = date('Y-m-d H:i:s');
        return Event::with('images')->select($query)->where('status', 1)->where('end_date_time', '>', $today)->get();
    }

    /**
     *Set Locale for multi languages
    */
    public function setLanguageLocal(Request $request)
    {
        session()->put('locale', $request->lang);
        return redirect()->back();
    }

    /**
     *Store Subscription email
    */
    public function subscription(Request $request)
    {
        $email = $request->email;
        $email_count = DB::table('new_subscriptions')->where('email', '=', $email)->count();
        if ($email_count > 0) {
            echo 0;
        } else {
            $model = new NewSubscription();
            $model->email = $email;
            $model->status = 1;
            $details = [
                'subject' =>  "User Subscription",
                'user_name' =>  "Super Admin",
                'content'  => "<p>A new user subscribed to the mustafai portal successfully.</p>",
                'links'    =>  "<a href='" . url('admin/subscriptions') . "'>Click here </a> to log in and view user subscribes users.",
            ];
            // $adminEmail = Admin::find(1)->value('email');
            $adminEmail = settingValue('emailForNotification');
            // sendEmail($adminEmail, $details);
            saveEmail($adminEmail, $details);
            $details["subject"] = 'Subscribed to Mustafai Portal';
            $details["user_name"] = $email;
            $details["content"] = "<p>Thank you for subscribing mustafai portal. Now you will get to hear from us on regular basis. </p>";
            $details["links"] = "<a href='" . url('register') . "'>Click here</a> to register to the mustafai portal today!";
            // sendEmail($email, $details);
            saveEmail($email, $details);
            echo $model->save();
        }
    }

    /**
     *Store contact us form details
    */
    public function contactForm(Request $request)
    {

        if ($request->ajax()) {
            $response = [];
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'subject' => 'required|min:3',
                'message' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                $response['title'] = 'Validation';
                $response['status'] = 'info';
                $response['message'] = 'Validation failed.';
                echo json_encode($response);
                exit();
            }

            $model = new ContactRecord();
            $model->fill($input);
            $model = $model->save();

            if ($model) {
                $response['title'] = 'Congrats!';
                $response['status'] = 'success';
                $response['message'] = 'Your query submitted.';
                $details = [
                    'subject' =>  "Query Received",
                    'user_name' =>  "Super Admin",
                    'content'  => "<p>A new user submitted a query via the contact us form.</p>",
                    'links'    =>  "<a href='" . url('admin/contacts') . "'>Click here </a> to log in and view all queries. ",
                ];
                // $adminEmail = Admin::find(1)->value('email');
                $adminEmail = settingValue('emailForNotification');
                // sendEmail($adminEmail, $details);
                saveEmail($adminEmail, $details);
                $details['subject'] = "Query Submitted Successfully";
                $details['user_name'] = $input['name'];
                $details['content'] = "<p>Your query has been received successfully. One of our staff members will contact you in next couple of hours.</p><p>Query details are as follows: <br> <b>Title </b> :" . $input['subject'] . "  <br> </p><p> <b>Query </b> : " . $input['message'] . "</p";
                $details['links'] = "";
                sendEmail($input['email'], $details);
                // saveEmail($input['email'], $details);
            } else {
                $response['title'] = 'Oops!';
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong.';
            }

            echo json_encode($response);
            exit();
        } else {
            $data = [];
            return view('home.home-pages.contact-us', $data);
        }
    }

    /**
     *get client details
    */
    public function getClientDetails()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = "Unknown OS Platform";
        $os_array = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows phone 8/i' => 'Windows Phone 8',
            '/windows phone os 7/i' => 'Windows Phone 7',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        );
        $found = false;

        $device = '';
        foreach ($os_array as $regex => $value) {
            if ($found) {
                break;
            } else if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
                $device = !preg_match('/(windows|mac|linux|ubuntu)/i', $os_platform) ? 'MOBILE' : (preg_match('/phone/i', $os_platform) ? 'MOBILE' : 'SYSTEM');
            }
        }
        $device = !$device ? 'SYSTEM' : $device;

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser = "Unknown Browser";

        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser',
        );

        foreach ($browser_array as $regex => $value) {
            if ($found) {
                break;
            } else if (preg_match($regex, $user_agent, $result)) {
                $browser = $value;
            }
        }

        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        $response = array();
        $response['ip_address'] = $ipaddress;
        $response['browser'] = $browser;
        $response['device'] = $device . " - " . $os_platform;
        return $response;
    }

    /**
     *get site setting data
    */
    public function getSettingData()
    {
        $settings = DB::table('settings')
            ->where('option_name', 'like', '%facebook%')
            ->orWhere('option_name', 'like', '%linkedin%')
            ->orWhere('option_name', 'like', '%pinterest%')
            ->orWhere('option_name', 'like', '%twitter%')
            ->orWhere('option_name', 'like', '%youtube%')
            ->get();

        return $settings;
    }

    /**
     *get feature product for homepage
    */
    public function getFeatureDonationProduct()
    {
        $data = [];
        $query = [];
        $query = getQuery(App::getLocale(), ['title', 'description']);
        $query[] = 'id';
        $query[] = 'price';
        $data['donation'] = Donation::select($query)->where(['status' => 1, 'donation_type' => 1, 'is_featured' => 1])->first();

        $query = [];
        $query = getQuery(App::getLocale(), ['name']);
        $query[] = 'id';
        $query[] = 'file_type';
        $query[] = 'file_name';
        $query[] = 'price';
        $data['product'] = Product::select($query)->where(['featured' => 1, 'status' => 1])->first();
        $response = [];
        $response['donation'] = (empty($data['donation'])) ? '' : (string) View('home.partial.feature-donation-partial', $data);
        $response['product'] = (empty($data['product'])) ? '' : (string) View('home.partial.feature-product-partial', $data);

        return json_encode($response, true);
        exit();
    }

    /**
     *get feature product for homepage
    */
    public function librarySections(Request $request)
    {
        $data = [];

        $isTypeMatched = false;

        if (!isset($_GET['all'])) {
//            if ($_GET['type'] == 1 && !have_permission('View-Image-Library')) {
            if ($_GET['type'] == 1) {
//                $isTypeMatched = true;
//                $data['results'] = collect([]);
                $data['results'] = $this->getLibraryData($request);
            }
            elseif ($_GET['type'] == 2 ) {
                $data['results'] = $this->getLibraryData($request);
            }
            elseif ($_GET['type'] == 3) {
                $data['results'] = $this->getLibraryData($request);
            }
            elseif ($_GET['type'] == 4) {
                $data['results'] = $this->getLibraryData($request);
            }
            elseif ($_GET['type'] == 5) {
                $data['results'] = $this->getLibraryData($request);
            }
        } else {
            $data['results'] = Library::where('type_id', $_GET['type'])->get();
        }
        $data['type'] = $_GET['type'];
        if (isset($request->empId)) {
            // $data['empId'] = 'employee';
            $data['empId'] = $request->empId;
        }
        $queryLibType = [];
        $queryLibType = getQuery(App::getLocale(), ['content']);
        $queryLibType[] = 'id';
        $data['libratype'] =  LibraryType::select($queryLibType)->where('id', $data['type'])->first();
        if (!isset($request->platform) && !$request->platform == 'dashboard') {
            $html = (string) View('home.partial.library-tabs-partial', $data);
        } else {
            $html = (string) View('user.partials.library-tabs-partial', $data);
        }
        $response = [];
        $response['html'] = $html;
        echo json_encode($response);
        exit();
    }

    /**
     *get prayer times for homepage
    */
    public function getPrayerTimes(Request $request)
    {
        $ip = $request->ip();
        $ip = ($ip == '127.0.0.1') ? '182.179.185.30' : $ip;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $ipdat='';
        if (($request->has('latitude') && !empty($request->latitude)) && ($request->has('longitude') && !empty($request->longitude))) {
            $url = "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$latitude}&longitude={$longitude}&localityLanguage=en";
            $ipdat = @json_decode(file_get_contents(
                $url
            ));
        }
        $ipdata = @json_decode(file_get_contents(
            "http://ip-api.com/json/" . $ip
        ));
        $city = !empty($ipdat->city) ? $ipdat->city : $ipdata->city;
        $country = !empty($ipdat->countryName) ? $ipdat->countryName : $ipdata->country;
        $date = date("d");
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = $now->format('Y');
        $file_name = $month . '-' . $year . '-' . $city . '.json';
        $path = '/files/json/' . $file_name;
        // dd($file_name);
        $file_exsist = namazHelper::fileExists($file_name);
        $data['daily']['city'] = $city;
        $data['daily']['country'] = $country;
        $data['monthly']['type'] = $_GET['type'];
        if ($file_exsist) {
            $namazData = json_decode(File::get(public_path($path)));
            if (!empty($namazData)) {
                $data['daily']['namazTime'] = $namazData->data[($date - 1)];
                $data['monthly']['namazTime'] = $namazData->data;
            }
        } else {
            $params = array('city' => $city, 'country' => $country, 'month' => $month, 'year' => $year, 'file_name' => $file_name, 'path' => $path);
            $createFile = namazHelper::createFile($params);
            if (!empty($createFile)) {
                $data['daily']['namazTime'] = $createFile->data[($date - 1)];
                $data['monthly']['namazTime'] = $createFile->data;
            }
        }
        // dump('overall', $data);

        if ($_GET['type'] == 'daily') {
            $html = (string) View('home.partial.prayer-time-partial', $data['daily']);
        } else if ($_GET['type'] == 'monthly' || $_GET['type'] == 'nawafil' || $_GET['type'] == 'sehar_aftar') {
            $html = (string) view('home.partial.prayer-monthly', $data['monthly']);
        }
        $res = [];
        $res['html'] = $html;
        echo json_encode($html);
        die();
    }

    /**
     *Return view after register message
    */
    public function afterRegisterMessage()
    {
        return view('home.info-messages.after-register-message');
    }

    /**
     *for check before login user role
    */
    public function cabinetUserRole(Request $request)
    {
        $login =  $request->userField;
        $field = '';
        if (is_numeric($login)) {
            $field = 'phone_number';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'user_name';
        }
        $user =   User::where($field, '=', $request->userField)->first();
        $cabinetUsers = (empty($user)) ? '' : $user->cabinetUsers;
        $cabinet = Cabinet::whereIn('id', $cabinetUsers->pluck('cabinet_id'))->where('status', 1)->pluck('id')->toArray();
        if (!empty($cabinet) && isset($cabinet)) {
            $cabinetUsers = CabinetUser::whereIn('cabinet_id', $cabinet)->where('user_id', $user->id)->get();
        } else {
            $cabinetUsers = '';
        }
        if (!empty($cabinetUsers) &&  !$user->cabinetUsers->isEmpty()) {
            $designations_id = [];
            foreach ($cabinetUsers as $val) {
                array_push($designations_id, $val->designation_id);
            }
            $query = getQuery(App::getLocale(), ['name']);
            $query[] = 'id';
            $data['desigantions'] = Designation::select($query)->WhereIn('id', $designations_id)->get();

            // if ($data['roles']->count() > 0) {
            // echo '0';
            // }
            $html =  (string) View('home.partial.cabinetRoles', $data);
            echo $html;
            // echo json_encode($roles);
        } else {
            echo '0';
        }
    }

    /**
     *Get library data
    */
    public function getLibraryData($request)
    {

        if (!isset($request->empId)) {
            $query = getQuery(App::getLocale(), ['title', 'content']);
            $query[] = 'id';
            $query[] = 'parent_id';
            $query[] = 'img_thumb_nail';
            $query[] = 'file';
            $query[] = 'status';
            $query[] = 'type_id';
            return LibraryAlbum::select($query)->where('type_id', $_GET['type'])->whereNull('parent_id')->where('status', 1)->limit(8)->get();
        } else {
            $empId    = hashDecode($request->empId);
            $employee = EmployeeSection::find($empId);
            $select = getQuery(App::getLocale(), ['title', 'content']);
            $select[] = 'library_albums.id';
            $select[] = 'library_albums.parent_id';
            $select[] = 'library_albums.img_thumb_nail';
            $select[] = 'library_albums.file';
            $select[] = 'library_albums.status';
            $select[] = 'library_albums.type_id';
            return $employee->libraryAlbums($select, $_GET['type'])->get();
            // return LibraryAlbum::select($query)->where('type_id', $_GET['type'])->whereNull('parent_id')->where('status', 1)->limit(8)->get();
        }
    }

    /**
     *Get tanslated headlines for homepage
    */
    public function headlines(Request $request)
    {
        $cols = getQuery(App::getLocale(), ['title', 'content']);
        $cols[] = 'headline_order';
        $cols[] = 'reporter_name';
        $cols[] = 'reporting_city';
        $cols[] = 'reporting_date_time';
        $cols[] = 'id';

        $headline = Headline::query()
            ->select($cols)
            ->with('city')
            ->findOrFail($request->id);

        //  ->orderBy(DB::raw('ISNULL(headline_order), headline_order'))

        return view('home.headlines', get_defined_vars());
    }

    /**
     *Get cities list
    */
    public function getCities()
    {
        $name_query = array_merge(getQuery(\Illuminate\Support\Facades\App::getLocale(), ['name']), ['id']);
        return City::select($name_query)->where('status', 1)->get();
    }

    /**
     *get detail for Share Post on social media
    */
    public function sharePostSocialMedia(Request $request)
    {
        $input = $request->all();

        $data = [];

        if (isset($input['id']) && !empty($input['id'])) {
            $file = LibraryAlbumDetails::find(encodeDecode($input['id']));
            // dd($file);
            if (!empty($file)) {
                if (!empty($file->libraryAlbum)) {
                    $type = $file->libraryAlbum->type_id;
                    $data['type'] = $type;
                    $data['type_video'] = $file->type_video;
                    switch ($type) {
                        case 1:
                            $data['filePath'] = getS3File($file->file);
                            $data['og_title'] = ($file->{'title_' . App::getLocale()}) ? $file->{'title_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_description'] = ($file->{'content_' . App::getLocale()}) ? $file->{'content_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_image'] = (empty($file->img_thumb_nail)) ? getS3File($file->file) : getS3File($file->img_thumb_nail);
                            break;
                        case 2:
                            $data['filePath'] = ($file->type_video == 0) ? getS3File($file->file) : $file->file;
                            $data['og_title'] = ($file->{'title_' . App::getLocale()}) ? $file->{'title_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_description'] = ($file->{'content_' . App::getLocale()}) ? $file->{'content_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_image'] = (!empty($file->img_thumb_nail)) ? getS3File($file->img_thumb_nail) :  getS3File($file->libraryAlbum->libraryType->icon);
                            break;
                        case 3:
                            $data['filePath'] = getS3File($file->file);
                            $data['og_title'] = ($file->{'title_' . App::getLocale()}) ? $file->{'title_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_description'] = ($file->{'content_' . App::getLocale()}) ? $file->{'content_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_image'] = (!empty($file->img_thumb_nail)) ? getS3File($file->img_thumb_nail) :  getS3File($file->libraryAlbum->libraryType->icon);
                            break;
                        case 4:
                            $data['filePath'] = getS3File($file->file);
                            $data['og_title'] = ($file->{'title_' . App::getLocale()}) ? $file->{'title_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_description'] = ($file->{'content_' . App::getLocale()}) ? $file->{'content_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_image'] = (!empty($file->img_thumb_nail)) ? getS3File($file->img_thumb_nail) :  getS3File($file->libraryAlbum->libraryType->icon);
                            break;
                        default:
                            $data['filePath'] = getS3File($file->file);
                            $data['og_title'] = ($file->{'title_' . App::getLocale()}) ? $file->{'title_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_description'] = ($file->{'content_' . App::getLocale()}) ? $file->{'content_' . App::getLocale()} : 'Mustafai Asset';
                            $data['og_image'] = (!empty($file->img_thumb_nail)) ? getS3File($file->img_thumb_nail) :  getS3File($file->libraryAlbum->libraryType->icon);
                            break;
                    }
                } else {
                    abort(404, 'No record found');
                }
            } else {
                abort(404, 'No record found');
            }
        } else {
            abort(404, 'No record found');
        }

        return view('home.social-share', $data);
    }

    /**
     *show file in flipbook for Mobile App
    */
    public function flipBookAPI(Request $request)
    {
        $input = $request->all();
        $data = [];

        if (isset($input['id']) && !empty($input['id'])) {
            if (isset($input['type']) && $input['type'] == 'magazine') {
                $file = Magazine::find($input['id']);
                if (!empty($file)) {
                    $data['filePath'] = getS3File($file->file);
                } else {
                    abort(404, 'No record found');
                }
            } else {
                $file = LibraryAlbumDetails::find($input['id']);

                if (!empty($file)) {
                    if (!empty($file->libraryAlbum)) {
                        $type = $file->libraryAlbum->type_id;
                        $data['type'] = $type;
                        $data['type_video'] = $file->type_video;
                        switch ($type) {
                            case 4:
                                $data['filePath'] = getS3File($file->file);
                                $data['og_title'] = ($file->{'title_' . App::getLocale()}) ? $file->{'title_' . App::getLocale()} : 'Mustafai Asset';
                                $data['og_description'] = ($file->{'content_' . App::getLocale()}) ? $file->{'content_' . App::getLocale()} : 'Mustafai Asset';
                                $data['og_image'] = (!empty($file->img_thumb_nail)) ? getS3File($file->img_thumb_nail) :  getS3File($file->libraryAlbum->libraryType->icon);
                                break;
                            default:
                                $data['filePath'] = getS3File($file->file);
                                $data['og_title'] = ($file->{'title_' . App::getLocale()}) ? $file->{'title_' . App::getLocale()} : 'Mustafai Asset';
                                $data['og_description'] = ($file->{'content_' . App::getLocale()}) ? $file->{'content_' . App::getLocale()} : 'Mustafai Asset';
                                $data['og_image'] = (!empty($file->img_thumb_nail)) ? getS3File($file->img_thumb_nail) :  getS3File($file->libraryAlbum->libraryType->icon);
                                break;
                        }
                    } else {
                        abort(404, 'No record found');
                    }
                } else {
                    abort(404, 'No record found');
                }
            }
        } else {
            abort(404, 'No record found');
        }

        return view('home.flipbook-app', $data);
    }
}
