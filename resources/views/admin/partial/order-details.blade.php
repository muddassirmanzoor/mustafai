@if(Auth::User()->getTable() == 'admins')
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No#</th>
                <th>Product </th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>


            <tr>
        </thead>
        <tbody>
            @foreach($OrderItemdata as $key=>$val)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$val->product->name_english}}</td>
                <td>{{$val->quantity}}</td>
                <td>{{$val->unit_price}}</td>
                <td>{{$val->total}}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<div class="row mt-2">
    <h3 class="text-danger d-flex justify-content-center"><strong>Admin Account</strong></h3>
    <div class="col-md-12  table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Order Recived Account title </th>
                    <th>Payment Method </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> {{ (!empty($orderData->productPaymentMethod->account_title)) ? $orderData->productPaymentMethod->account_title : 'N/A'}} </td>
                    <td> {{ (!empty($orderData->productPaymentMethod)) ? $orderData->productPaymentMethod->paymentMethod->method_name_english : 'N/A'}} </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-md-12 mt-2 table-responsive ">

        <table class="table ">
            <thead>

            </thead>
            <tbody>

                <tr>
                    @if (!empty($orderData->productPaymentMethod->productPaymentMethodDetails))

                        @foreach($orderData->productPaymentMethod->productPaymentMethodDetails as $key=>$val)
                        <th style="width: 60%; border:none">{{$val->paymentMethodDetail->method_fields_english}}</th>
                        <td style="border:none">{{$val->payment_method_field_value}}</td>
                        @endforeach

                    @endif
                </tr>
            </tbody>
        </table>
    </div>

    <h3 class="text-danger mt-2 d-flex justify-content-center"><strong> User Account</strong> </h3>
    <div class="col-md-12  table-responsive">
        <table class="table">

            <tbody>
                <tr>
                    <th style="width: 60%; border:none">Payment Method </th>
                    <td style="border:none">{{!empty($orderData->orderPaymentDetails[0]->paymentMethod) ? $orderData->orderPaymentDetails[0]->paymentMethod->method_name_english : 'N/A'}}</td>
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
                    @if (!empty($orderData->orderPaymentDetails))

                        @foreach($orderData->orderPaymentDetails as $key=>$val)
                        <th style="width: 60%; border:none">{{$val->paymentMethodDetail->method_fields_english}}</th>
                        <td style="border:none">{{$val->payment_method_field_value}}</td>
                        @endforeach

                    @endif
                </tr>
            </tbody>
        </table>
    </div>


    <h3 class="text-danger mt-2 d-flex justify-content-center"><strong>User Detail</strong> </h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>{{__('app.user-name')}}</th>
                    <th> {{__('app.email')}}</th>
                    <th>{{__('app.Phone-no')}}</th>
                    <th>{{__('app.billing-dddress')}}</th>
                    <th>{{__('app.payment-receipt')}}</th>
    
    
                <tr>
            </thead>
            <tbody>
                
                <tr>
                    <td>{{!empty($orderData->billing_user_name) ? $orderData->billing_user_name : 'N/A'}}</td>
                    <td>{{!empty($orderData->billing_email) ? $orderData->billing_email : 'N/A'}}</td>
                    <td>{{!empty($orderData->billing_phone_number) ? $orderData->billing_phone_number : 'N/A'}}</td>
                    <td>{{!empty($orderData->billing_address) ? $orderData->billing_address : 'N/A'}}</td>
                    @if (!empty($orderData->receipt))
                    <td><a href="{{!empty($orderData->receipt) ? getS3File($orderData->receipt) : 'N/A'}}">{{__('app.payment-receipt')}}</a></td>
                    @endif
                  
                </tr>
             
            </tbody>
    
        </table>
    </div>

    
</div>
@else

{{-- For Users  --}}
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>{{__('app.no')}}</th>
                <th>{{__('app.product')}} </th>
                <th> {{__('app.quantity')}}</th>
                <th>{{__('app.price')}}</th>
                <th>{{__('app.total')}}</th>


            <tr>
        </thead>
        <tbody>
            @foreach($OrderItemdata as $key=>$val)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$val->product->name_english}}</td>
                <td>{{$val->quantity}}</td>
                <td>{{$val->unit_price}}</td>
                <td>{{$val->total}}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<div class="row mt-2">
    <h3 class="text-danger d-flex justify-content-center"><strong>{{__('app.admin-account')}}</strong></h3>
    <div class="col-md-12  table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>{{__('app.order-recieved-account-title')}}</th>
                    <th>{{__('app.payment-method')}} </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> {{!empty($orderData->productPaymentMethod->account_title) ? $orderData->productPaymentMethod->account_title : 'N/A'}}</td>
                    <td>{{!empty($orderData->productPaymentMethod) ? $orderData->productPaymentMethod->paymentMethod->method_name_english : 'N/A'}}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-md-12 mt-2 table-responsive ">

        <table class="table ">
            <thead>

            </thead>
            <tbody>

                <tr>
                    @if (!empty($orderData->productPaymentMethod->productPaymentMethodDetails ))
                    @foreach($orderData->productPaymentMethod->productPaymentMethodDetails as $key=>$val)
                    <th style="width: 60%; border:none">{{$val->paymentMethodDetail->method_fields_english}}</th>
                    <td style="border:none">{{$val->payment_method_field_value}}</td>
                    @endforeach
                    @endif
                </tr>
            </tbody>
        </table>
    </div>

    <h3 class="text-danger mt-2 d-flex justify-content-center"><strong>{{__('app.user-account')}}</strong> </h3>
    <div class="col-md-12  table-responsive">
        <table class="table">

            <tbody>
                <tr>
                    <th style="width: 60%; border:none">{{__('app.payment-method')}} </th>
                    <td style="border:none">{{!empty($orderData->orderPaymentDetails[0]->paymentMethod) ? $orderData->orderPaymentDetails[0]->paymentMethod->method_name_english :'N/A'}}</td>
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
                    @if (!empty($orderData->orderPaymentDetails))
                        @foreach($orderData->orderPaymentDetails as $key=>$val)
                        <th style="width: 60%; border:none">{{$val->paymentMethodDetail->method_fields_english}}</th>
                        <td style="border:none">{{$val->payment_method_field_value}}</td>
                        @endforeach
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif
