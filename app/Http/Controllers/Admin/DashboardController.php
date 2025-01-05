<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\BusinessPlan;
use App\Models\Admin\Cabinet;
use App\Models\Admin\Donation;
use App\Models\Admin\Event;
use App\Models\Admin\Product;
use App\Models\Posts\Post\Post;
use App\Models\Store\Order;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * get dashboard tiles count information.
    */
    public function index()
    {
        $data = [];
        $data['products'] = Product::all();
        $data['donations'] = Donation::all();
        $data['users'] = User::all();
        $data['events'] = Event::all();
        $data['posts'] = Post::all();
        $data['orders'] = Order::all();
        $data['business_plans'] = BusinessPlan::all();
        $data['cabinets'] = Cabinet::all();
        return view('admin.dashboard')->with($data);
    }

    /**
     * dashboard notifications.
    */
    public function notifications(Request $request)
    {
        $admin = \App\Models\Admin\Admin::find(1);
        $admin->notifications()->update(['is_read' => 1]);
        if ($request->ajax()) {
            $notifications = $admin->notifications()->paginate(20);
            $view = view('admin.notifications.load-more-notifications', compact('notifications'))->render();
            return response()->json(['status' => 200, 'html' => $view]);
        }
        $notifications = $admin->notifications()->paginate(20);
        return view('admin.notifications.index', get_defined_vars());
    }
}
