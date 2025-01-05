<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ShipmentCharge;

class ShipmentController extends Controller
{
    /**
     * listing the Shipment
    */
    public function index()
    {
        if (!have_right('View-Shipment-Rate')) {
            access_denied();
        }
        $shipment = ShipmentCharge::first();
        return view('admin.shipment-charges.form', compact('shipment'));
    }

    public function create()
    {
    }

    /**
     * storing the Shipment
    */
    public function store(Request $request)
    {
        if (!have_right('Update-Shipment-Rate')) {
            access_denied();
        }
        $shipment = ShipmentCharge::find($request->id);
        $shipment->shipment_rate = $request->shipment_rate;
        $shipment->update();
        return back()->with('message', 'Data updated Successfully');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
