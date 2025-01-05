@if($product == '')
    <p class="text-muted"> {{ __('app.select-any-account') }}: </p>
    @forelse($donationPaymentMethods as $key=>$val)
        <label for="donationPayment_{{$val->id}}">
        <input type="radio" class="form-group d-inline-block" id="donationPayment_{{$val->id}}" name="donation_payment_method_id" value="{{$val->id}}" required @if (count($donationPaymentMethods) < 2)
        checked
    @endif >
        <b class="p-2">{{ __('app.account-title') }}:</b> {{$val->account_title}}</label>
        @foreach($val->donationPaymentMethodDetails as $donationDetailKey=>$donationDetailVal)
                @php
                $query_method_details = getQuery(App::getLocale(), ['method_fields']);
                $query_method_details[] = 'id';
                $payment_method_details = \App\Models\Admin\PaymentMethodDetail::select($query_method_details)->where('id',$donationDetailVal->payment_method_detail_id)->first();
                @endphp
                    <b>{{ __('app.account-number') }}:</b> {{($payment_method_details->method_fields)}}
                        ({{($donationDetailVal->payment_method_field_value)}})
        @endforeach
        <br>
        @empty
            <input type="text" class="form-group" placeholder="Please Add Account First :" style="border: none;
            display: block;
            margin-bottom: 1rem;" readonly name="chk_required" required>
    @endforelse

@else
    <p class="text-muted"> {{ __('app.select-any-account') }} :</p>
    @forelse($productPaymentMethods as $key=>$val)
        <label for="productPayment_{{$val->id}}">
        <input type="radio" class="form-group" id="productPayment_{{$val->id}}" name="product_payment_method_id" value="{{$val->id}}" required
        @if (count($productPaymentMethods) < 2) checked @endif>
        <b class="pt-2">{{ __('app.account-title') }}:</b> {{$val->account_title}}
        </label>

        @foreach($val->productPaymentMethodDetails as $donationDetailKey=>$donationDetailVal)
                @php
                $query_method_details = getQuery(App::getLocale(), ['method_fields']);
                $query_method_details[] = 'id';
                $payment_method_details = \App\Models\Admin\PaymentMethodDetail::select($query_method_details)->where('id',$donationDetailVal->payment_method_detail_id)->first();
                @endphp
                    <b class="pt-2">{{ __('app.account-number') }}:</b> {{($payment_method_details->method_fields)}}
                        ({{($donationDetailVal->payment_method_field_value)}})
        @endforeach
        <br>
        @empty
            <input type="text" class="form-group" placeholder="Please Add Account First :" style="border: none;
            display: block;
            margin-bottom: 1rem;" readonly name="chk_required" required>
    @endforelse

@endif

