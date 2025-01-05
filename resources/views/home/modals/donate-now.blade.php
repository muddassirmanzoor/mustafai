@php

$query_method = getQuery(App::getLocale(), ['method_name']);
$query_method[] = 'id';
$payment_methods = \App\Models\Admin\PaymentMethod::select($query_method)->get();

$query_method_details = getQuery(App::getLocale(), ['method_fields']);
$query_method_details[] = 'id';
$query_method_details[] = 'payment_method_id';
$payment_methods_details =\App\Models\Admin\PaymentMethodDetail::select($query_method_details)->get();

@endphp

<div class="modal fade common-model-style" tabindex="-1" role="dialog" id="donate-now">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green">{{__('app.donate-now')}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-12">
                        <div class="row mt-4">
                            <div class="col-lg-8">

                                <form id="donate-now-form" enctype="multipart/form-data">
                                    <div class="row ">
                                        <div class="col-lg-6 mb-xxl-3 mb-1">
                                            <input type="hidden" class="form-control" name="donation_id" id="donation-id">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><b>{{__('app.your-name')}}</b></label>
                                                <input type="text" class="form-control" name="name" placeholder="{{__('app.your-name')}}"  @auth value="{{auth()->user()->user_name}}" @endauth>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-xxl-3 mb-1">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1"><b>{{__('app.Phone-no')}}</b></label>
                                                <input type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="phone" placeholder="{{__('app.enter_phone_number')}}" @auth value="{{optional(auth()->user()->countryCode)->phonecode .  auth()->user()->phone_number}}" @endauth>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-xxl-3 mb-1">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1"><b>{{__('app.address')}}</b></label>
                                                @auth
                                                  <input type="text" class="form-control" name="address" placeholder="{{__('app.enter_address')}}" value="{{optional(auth()->user()->permanentAddress)->{'permanent_address_'.APP::getLocale().''} }}">
                                                @endauth
                                                @guest
                                                <input type="text" class="form-control" name="address" placeholder="{{__('app.enter_address')}}" value="">
                                                @endguest
                                            </div>
                                        </div>
                                        @guest
                                        <div class="col-lg-6 mb-xxl-3 mb-1">
                                            <div class="form-group">
                                                <label for="email"><b>{{__('app.enter-email')}} <span class="text-red">*</span></b></label>
                                                <input type="email" class="form-control" name="email" placeholder="{{__('app.your-email')}}" required>
                                            </div>
                                        </div>
                                        @endguest
                                        @auth
                                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}" >
                                        @endauth
                                        <div class="col-lg-6 mb-xxl-3 mb-1" >
                                            <div class="form-group">
                                                <label for="payment_method_mustafai"><b>{{__('app.mustafai-payment-account')}} <span class="text-red">*</span></b></label>
                                                <select name="payment_method_mustafai_id" id="payment_method_mustafai_id" class="form-control"  placeholder="Enter Payment Method" required onchange="get_payment_Donations($(this))">
                                                    <option value="">-{{__('app.select_mustafai_payment_method')}}--</option>
                                                    @foreach($payment_methods as $key=>$val)
                                                    <option value="{{$val->id}}">{{$val->method_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-xxl-3 mb-1 d-flex flex-column ms-3" id="donation_payments_method_sections">

                                        </div>
                                        <div class="col-lg-12 mb-xxl-3 mb-1 " id="donation_payments_method_details_sections">

                                        </div>
                                        <div class="col-lg-12 mb-xxl-3 mb-1" >
                                            <div class="form-group">
                                                <label for="payment_method"><b>{{__('app.payment-method')}} <span class="text-red">*</span></b></label>
                                                <select name="payment_method_id" id="payment_method" class="form-control"  placeholder="Enter Payment Method" required onchange="get_payment_data($(this))">
                                                    <option value="">--{{__('app.select_your_payment_method')}}--</option>
                                                    @foreach($payment_methods as $key=>$val)
                                                    <option value="{{$val->id}}" data-attr="{{$val->id}}">{{$val->method_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        @foreach($payment_methods_details as $key=>$val)
                                            <div class="col-lg-12 mb-xxl-3 mb-1 {{'div_'.$val->payment_method_id}} common_div" style="display:none">
                                                <div class="form-group">
                                                    <label ><b>{{$val->method_fields}} <span class="text-red">*</span></b></label>
                                                    <input type="text" class="form-control" id="dynamic_fields_{{$val->id}}" name="method_details[{{$val->id}}]" placeholder=" {{__('app.enter') .$val->method_fields}}" required>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-12 mb-xxl-3 mb-1">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1"><b>{{__('app.amount')}} <span class="text-red">*</span></b></label>
                                                <input type="number" class="form-control" name="amount" placeholder="{{__('app.enter_amount')}}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="receipt-amount"><b>{{__('app.amount-reciept')}} <span class="text-red">*</span></b></label>
                                            <div class="form-group ">
                                                <input type="file" id="receipt-amount" class="form-control custom-file-input" name="receipt" required onchange="loadRecipet($(this))" >
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="receipt-amount"><b>{{__('app.summary')}}</b></label>
                                            <div class="form-group ">
                                                <div class="form-outline">
                                                    <textarea class="form-control" name="note" id="note" rows="8"></textarea>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-center align-items-center">
                                <div class="form-group" id="reciept-img-sec">
                                    <img loading = "lazy" src="{{ asset('assets/home/images/reciept.png') }}" alt="" class="img-fluid" id="default-img-reciept">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="green-hover-bg theme-btn" onclick="donate($(this))">{{__('app.donate-now')}}</button>
                <!-- <button type="button" class="blue-hover-bg theme-btn" data-bs-dismiss="modal" aria-label="Close">Close</button> -->
            </div>
        </div>
    </div>
</div>
