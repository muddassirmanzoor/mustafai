<?php



namespace App\Http\Controllers\Home\Pages;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App;

use App\Models\Admin\Donation;

use App\Models\Admin\EmployeeSection;

use App\Models\Admin\Event;

use App\Models\Admin\Library;

use App\Models\Admin\LibraryType;

use App\Models\Admin\Page;

use App\Models\Admin\Section;

use App\Models\Admin\Testimonial;

use App\Models\Admin\DonationCategory;



class PagesController extends Controller

{

    /**

     *Get all testimonials

    */

    public function allTestimonials()

    {

        $data = [];

        $data['testimonials'] = $this->getTestimonials();

        return view('home.home-pages.testimonial-pages.all-testimonials', $data);

    }



    /**

     *Get all team

    */

    public function allTeam()

    {

        $data = [];

        $data['sections'] = $this->getSections();

        return view('home.home-pages.our-team-pages.all-team', $data);

    }



    /**

     *Get donations list

    */

    public function donations(Request $request)

    {

        if ($request->ajax()) {

            $catId = (isset($_GET['category'])) ? $_GET['category'] : '';



            $donation_ids = (isset($_GET['donationIds'])) ? $_GET['donationIds'] : [];

            $where = '1';

            if ($catId != '') {

                $where .= ' AND donation_category_id=' . $catId;

            }

            if (count($donation_ids) && $donation_ids[0] != "") {

                $donation_ids = implode(',', $donation_ids);

                $where .= ' AND id NOT IN (' . $donation_ids . ')';

            }



            $query = getQuery(App::getLocale(), ['title', 'description']);

            $query[] = 'id';

            $query[] = 'price';

            $query[] = 'file';

            $donations = Donation::select($query)->whereRaw($where)->where(['status' => 1, 'donation_type' => 1])->limit(9)->get();



            $data = [];

            $data['donations'] = $donations;

            $html = (string) View('home.partial.donations-partial', $data);



            $response = [];

            $response['html'] = $html;

            $response['loadMore'] = count($donations);

            echo json_encode($response);

            exit();

        }



        $data = [];

        $query = getQuery(App::getLocale(), ['name']);

        $query[] = 'id';

        $data['donation_categories'] = DonationCategory::select($query)->where('status', 1)->get();

        return view('home.home-pages.donation-pages.donation-details', $data);

    }



    /**

     *view specific library type

    */

    public function viewLibrary($type)

    {

        $data = [];

        $data['libData'] = $this->getLibData($type);

        $data['sections'] = $this->getSections();

        $data['libraryTypes'] = $this->getLibraryTypes();

        $data['events'] = $this->getEvents();

        $data['libtypee'] = $type;

        $libraryType = LibraryType::where('id', $type)->firstOrFail();

        $data['libStatus'] = $libraryType->status;

        $data['libalbumCount'] = $libraryType->libraryAlbum()->whereNull('parent_id')->count();





        return view('home.home-pages.library-pages.view-library', $data);

    }



    /**

     *get Testimonials list

    */

    function getTestimonials()

    {

        $query = getQuery(App::getLocale(), ['name', 'message', 'title']);

        $query[] = 'id';

        $query[] = 'image';

        $query[] = 'date_time';

        $query[] = 'link';

        return Testimonial::select($query)->where('status', 1)->get();

    }



    /**

     *get donations list

    */

    function getDonations()

    {

        $query = getQuery(App::getLocale(), ['title', 'description']);

        $query[] = 'id';

        $query[] = 'price';

        $query[] = 'file';

        return Donation::select($query)->where(['status' => 1, 'donation_type' => 1])->get();

    }



    /**

     *get cms page detail

    */

    function getPageData($pageName)

    {

        $query = getQuery(App::getLocale(), ['title', 'short_description', 'description']);

        $query[] = 'id';

        return Page::select($query)->where('status', 1)->where('title_english', 'like', '%' . $pageName . '%')->first();

    }



    /**

     *get library types

    */

    function getLibData($type_id)

    {

        $query = getQuery(App::getLocale(), ['title', 'content']);

        $query[] = 'id';

        $query[] = 'status';

        return LibraryType::select($query)->where('status', 1)->where('status', 1)->where('id', $type_id)->first();

    }



    /**

     *get employee section members

    */

    function getEmployeeSections()

    {

        $query = getQuery(App::getLocale(), ['name', 'designation']);

        $query[] = 'image';

        return EmployeeSection::select($query)->where('status', 1)->get();

    }



    /**

     *get employee section

    */

    function getSections()

    {

        $query = getQuery(App::getLocale(), ['name']);

        $query[] = 'id';

        return Section::select($query)->where('status', 1)->get();

    }



    /**

     *get library types

    */

    function getLibraryTypes()

    {

        $query = getQuery(App::getLocale(), ['title']);

        $query[] = 'id';

        return LibraryType::select($query)->where('status', 1)->get();

    }



    /**

     *get events list

    */

    function getEvents()

    {

        $query = getQuery(App::getLocale(), ['title', 'content', 'location']);

        $query[] = 'image';

        $query[] = 'id';

        $query[] = 'start_date_time';

        $query[] = 'end_date_time';

        return Event::select($query)->where('status', 1)->orderByDesc('created_at')->take(3)->get();

    }



    /**

     *get events list to pass home page

    */

    function events(Request $request)

    {

        $data = [];

        $data['id'] = !empty($request->id) ? $request->id : '';

        $data['events'] = $this->getEvents();

        return view('home.home-pages.events', $data);

    }



    /**

     *get  specific event detail

    */

    public function event($id = null)

    {

        if ($id === null) return abort(500, 'Something goes wrong!');



        $query = array_merge(getQuery(App::getLocale(), ['title', 'content', 'location']), ['id', 'start_date_time', 'end_date_time']);

        $eventSessionCols = array_merge(getQuery(App::getLocale(), ['session', 'description']), ['id', 'event_id', 'session_start_date_time', 'session_end_date_time']);



        $event = Event::query()->select($query)->with(['images', 'sessions' => fn ($q) => $q->select($eventSessionCols)])->findOrFail(hashDecode($id));



        return view('home.event', get_defined_vars());

    }



    /**

     *destroye cart of products

    */

    function orderNowHome(Request $request)

    {

        \Cart::clear();

        // dd("ok");

        exit;

    }

}

