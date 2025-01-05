@php

$query_method = getQuery(App::getLocale(), ['method_name']);
$query_method[] = 'id';
$payment_methods = \App\Models\Admin\PaymentMethod::select($query_method)->get();

$query_method_details = getQuery(App::getLocale(), ['method_fields']);
$query_method_details[] = 'id';
$query_method_details[] = 'payment_method_id';
$payment_methods_details =\App\Models\Admin\PaymentMethodDetail::select($query_method_details)->get();
@endphp
<div class="">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>{{__('app.product')}}</th>
                <th>{{__('app.name')}}</th>
                <th>{{__('app.quantity')}}</th>
                <th>{{__('app.price')}}</th>
                <th>{{__('app.total')}}</th>
                <th>{{__('app.action')}}</th>
            </thead>
            <tbody>

                @foreach($items as $item)
                @php
                if((isset($item->product->productImages[0]))){
                if(file_exists(public_path($item->product->productImages[0]->file_name))){
                $image = (isset($item->product->productImages[0])) ? $item->product->productImages[0]->file_name : 'images/products-images/default.png';
                }
                else {
                $image ='images/products-images/default.png';
                }
                }
                else{
                $image ='images/products-images/default.png';
                }
                @endphp
                <tr>
                    <td class="d-flex align-items-center">
                        <input type="checkbox" name="cart_ids[]" value="{{$item->id}}" class="selected_cart_ids form-check-input all-check-product" data-total-price="{{ $item->total }}" data-attr="{{ ($item->product->product_type == 1) ? false : $item->is_chargeable  }}" data-quantity="{{ $item->quantity }}" onclick="getShipmentCharges()">
                        <img src="{{getS3File($image)}}" alt="" width="40" height="40">
                    </td>
                    @php
                    $query = getQuery(\App::getLocale(), ['name']);
                    $productData = App\Models\Admin\Product::select($query)->where(['id' => $item->product_id])->first();
                    @endphp
                    <td>{{ $productData->name }}</td>
                    <td>{{$item->quantity}}</td>
                    <td>{{$item->unit_price}}</td>
                    <td>{{$item->total}}</td>
                    <td class="d-flex align-items-center">

                        <span class="btn" onclick="removeFromCart({{$item->id}},$(this))">
                            <i class="fa fa-trash-o text-red" aria-hidden="true"></i>
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="reciept-section">
        <form id="order-reciept-form">
            <input type="hidden" value="" name="sub_total" class="total_td">
            <input type="hidden" value="" name="shipment_amount" class="shipment_td">
            <input type="hidden" value="" name="grand_total" class="grand_total_td">
            <div class="">
                <div class="form-header billing-header">
                    <label>{{ __('app.billing-dddress') }}</label>
                </div>
                <div class="row">

                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <label class="d-flex d-block">{{ __('app.email') }} <span class="text-red">*</span></label>
                            <input class="form-control mt-2" type="email" name="billing_email" placeholder="{{ __('app.enter') }}{{ __('app.email') }}" id="billing_email" value="{{ auth()->user()->email }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <label class="d-flex d-block">{{ __('app.phone-number') }} <span class="text-red">*</span></label>
                            <input class="form-control mt-2" type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="billing_phone_number" id="billing_phone_number" placeholder="{{ __('app.enter') }}{{ __('app.phone-number') }}" value="{{ optional(auth()->user()->countryCode)->phonecode .  auth()->user()->phone_number }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <label class="d-flex d-block">{{ __('app.name') }} <span class="text-red">*</span></label>
                            <input class="form-control mt-2" type="text" name="billing_user_name" placeholder="{{ __('app.enter') }}{{ __('app.name') }}" id="billing_user_name" value="{{ auth()->user()->user_name }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <label class="d-flex d-block">{{ __('app.street-address') }} <span class="text-red">*</span></label>
                            <input class="form-control mt-2" type="text" name="billing_address" placeholder="{{ __('app.enter') }}{{ __('app.street-address') }}" id="billing_address" value="{{ auth()->user()->address_english }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <label class="d-flex d-block">{{ __('app.city') }}</label>
                            <select class="form-select" name="billing_city_id">
                                <option value="">{{ __('app.select-city') }}</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <label class="d-flex d-block">{{ __('app.country') }}</label>
                            <select class="form-select" name="billing_country_id">
                                <option value="">{{ __('app.select-country') }}</option>
                                @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 mt-3 mb-3">
                        <div class="form-check d-flex align-items-center">
                            <input class="form-check-input" name="is_billing_address_is_shipping" value="1" id="address-check" type="checkbox" value="" onclick="checkAddress(this)" checked>
                            <label class="form-check-label">
                                {{ __('app.ship-to-address') }}
                            </label>
                        </div>
                    </div>

                    <div class="ship-address mb-3" >
                        <hr>
                        <div class="form-header billing-header">
                            <label>{{ __('app.shipping-address') }}</label>
                        </div>
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group mt-2">
                                    <label class="d-flex d-block">{{ __('app.email') }}</label>
                                    <input class="form-control mt-2" type="email" name="shipping_email" id="shipping_email" placeholder="{{ __('app.enter') }}{{ __('app.email') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mt-2">
                                    <label class="d-flex d-block">{{ __('app.phone-number') }}</label>
                                    <input class="form-control mt-2" type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="shipping_phone_number" id="shipping_phone_number" placeholder="{{ __('app.enter') }}{{ __('app.phone-number') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mt-2">
                                    <label class="d-flex d-block">{{ __('app.name') }}</label>
                                    <input class="form-control mt-2" type="text" name="shipping_user_name" id="shipping_user_name" placeholder="{{ __('app.enter') }}{{ __('app.name') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mt-2">
                                    <label class="d-flex d-block">{{ __('app.street-address') }}</label>
                                    <input class="form-control mt-2" type="text" name="shipping_address" id="shipping_address" placeholder="{{ __('app.enter') }}{{ __('app.street-address') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mt-2">
                                    <label class="d-flex d-block">{{ __('app.city') }}</label>
                                    <select class="form-select" name="shipping_city_id">
                                        <option value="">{{ __('app.select-city') }}</option>
                                        @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mt-2">
                                    <label class="d-flex d-block">{{ __('app.country') }}</label>
                                    <select class="form-select" name="shipping_country_id">
                                        <option value="">{{ __('app.select-country') }}</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-header billing-header">
                        <label class="order-summaray">{{ __('app.order-summary') }}</label>
                    </div>
                    <div class="row px-3">
                        <table class="table">

                            <input type="hidden" id="shipment_rate" value="{{ $shipment_rate->shipment_rate }}">


                            <tbody>
                                <tr>
                                    <td><span class="cart_count_td">0</span>{{ __('app.item-price') }}</td>
                                    <td class="total_td">0</td>
                                </tr>
                                <tr>
                                    <td>{{ __('app.flat-rate-shipping') }}</td>
                                    <td class="shipment_td">0</td>
                                </tr>
                                <tr>
                                    <th>{{ __('app.grand-total') }}</th>
                                    <th> <span class="grand_total_td">0</span>{{__('app.rs')}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="payment-section" >
                        <div class="col-lg-6 mb-xxl-3 mb-1">
                            <div class="form-header billing-header">
                                <label class="d-flex d-block">{{ __('app.payment-receipt') }}</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control mt-2" type="file" name="order_reciept" id="order-reciept" required>
                            </div>
                        </div>

                        {{-- details of of payment  --}}

                        <div class="col-lg-6 mb-xxl-3 mb-1">
                            <div class="form-group">
                                <label for="payment_method_mustafai">{{__('app.mustafai-payment-account')}} <span class="text-red">*</span></label>
                                <select name="payment_method_mustafai_id" id="payment_method_mustafai_id" class="form-control" placeholder="Enter Payment Method" required onchange="get_payment_Donations($(this),'product')">
                                    <option value="">-{{__('app.select').__('app.mustafai-payment-account')}}--</option>
                                    @foreach($payment_methods as $key=>$val)
                                    <option value="{{$val->id}}">{{$val->method_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-xxl-3 mb-1 d-flex flex-column ms-3" id="donation_payments_method_sections">

                        </div>
                        <div class="col-lg-12 mb-xxl-3 mb-1 ms-3" id="donation_payments_method_details_sections">

                        </div>
                        <div class="col-lg-6 mb-xxl-3 mb-1">
                            <div class="form-group">
                                <label for="payment_method">{{__('app.payment-method')}} <span class="text-red">*</span></label>
                                <select name="payment_method_id" id="payment_method" class="form-control" placeholder="Enter Payment Method" required onchange="get_payment_data($(this))">
                                    <option value="">--{{__('app.select').__('app.payment-method')}}--</option>
                                    @foreach($payment_methods as $key=>$val)
                                    <option value="{{$val->id}}" data-attr="{{$val->id}}">{{$val->method_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        @foreach($payment_methods_details as $key=>$val)
                        <div class="col-lg-6 mb-xxl-3 mb-1 {{'div_'.$val->payment_method_id}} common_div ms-3" style="display:none">
                            <div class="form-group">
                                <label>{{$val->method_fields}} <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="dynamic_fields_{{$val->id}}" name="method_details[{{$val->id}}]" placeholder=" {{__('app.enter') .$val->method_fields}}" required>
                            </div>
                        </div>
                        @endforeach

                    </div>

                </div>
        </form>
    </div>
</div>
