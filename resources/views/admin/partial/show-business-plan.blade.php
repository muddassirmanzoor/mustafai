<div>
    <h2>Business Plan</h2>
    <br>
    <div class="row">
        <div class="col-6">
            <b>Plan Name</b>: <span>{{ $business->name_english }}</span>
        </div>
        <div class="col-6">
            <b>Plan Type</b>:
            @switch($business->type)
                @case(1) <span>Monthly</span> @break;
                @case(2) <span>Weekly</span> @break;
                @case(3) <span>Daily</span> @break;
            @endswitch
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Total Invoices</b>: <span>{{ $business->total_invoices }}</span>
        </div>
        <div class="col-6">
            <b>Invoice Amount</b>: {{ $business->invoice_amount }}
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Total Users</b>: <span>{{ $business->total_users }}</span>
        </div>
        <div class="col-6">
            <b>Description</b>: {{ $business->description_english }}
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Start Date</b>: <span>{{ date("d-m-Y", $business->start_date) }}</span>
        </div>
        <div class="col-6">
            <b>End Date</b>: {{ date("d-m-Y", $business->end_date) }}
        </div>
    </div>

</div>
