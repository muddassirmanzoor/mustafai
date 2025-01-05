<div class="row mt-2">
    <h3 class="text-danger d-flex justify-content-center"><strong>Admin Account</strong></h3>
    <div class="col-md-12  table-responsive">
        <table class="table">
            <thead >
                <tr>
                    <th>Donation Recived Account title </th>
                    <th>Payment Method </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td> {{$donationRecieptData->donationPaymentMethod->account_title}}</td>
                <td>{{$donationRecieptData->donationPaymentMethod->paymentMethod->method_name_english}}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-md-12 mt-2 table-responsive">

        <table class="table ">
            <thead>

            </thead>
            <tbody>
                
                <tr>
                @foreach($donationRecieptData->donationPaymentMethod->donationPaymentMethodDetails as $key=>$val)
                <th>{{$val->paymentMethodDetail->method_fields_english}}</th>
                <td>{{$val->payment_method_field_value}}</td>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>

    <h3 class="text-danger mt-2 d-flex justify-content-center"><strong> User Account</strong> </h3>
    <div class="col-md-12  table-responsive">
        <table class="table">

            <tbody>
                <tr>
                <th>Payment Method </th>
                <td>{{$donationRecieptData->donationRecieptDetails[0]->paymentMethod->method_name_english}}</td>
                </tr>

            </tbody>
        </table>
    </div>
     <div class="col-md-12 mt-2 table-responsive">

        <table class="table ">
            <thead>

            </thead>
            <tbody>
                
            <tr>
                @foreach($donationRecieptData->donationRecieptDetails as $key=>$val)
                <th>{{$val->paymentMethodDetail->method_fields_english}}</th>
                <td>{{$val->payment_method_field_value}}</td>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div> 
</div>