<form id="pay-subscription-form" enctype="multipart/form-data" method="post" action="{{ route('user.pay-user-subscription') }}">

<div class="row justify-content-center align-items-center">

   <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">

       <label class="bank-heading-label"><b>{{__('app.mustafai-payment-account')}} <span class="text-red">*</span></b></label>

       <hr>

        @foreach($bankAccounts as $key => $bankAccount)

            <div class="col-lg-12 mb-xxl-3 mb-1">

                <div class="form-group">

                 <label for="mustafai_account_id_{{ $key }}" style="width:100%;">

                        <div class="bank-radio-wrapper d-flex align-items-center justify-content-between" style="border: 1px solid #d9d9d9; padding: 13px 20px 14px 12px; background: #f0f0f0; cursor: pointer;">

                            <input type="radio" name="mustafai_account_id" id="mustafai_account_id_{{ $key }}" value="{{ $bankAccount->id }}" required>

                            <span class="bank-detail-wrap">   <b>{{ __('app.bank-name') }}: </b> {{ $bankAccount->bank->name ?? '---' }} <br></span>

                           <span class="bank-detail-wrap">  <b>{{ __('app.account-title') }}: </b> {{ $bankAccount->account_title ?? '---' }} <br></span>

                          <span class="bank-detail-wrap">   <b>{{ __('app.account-number') }}: </b> {{ $bankAccount->account_number ?? '---' }} <br></span>

                           <span class="bank-detail-wrap">  <b>{{ __('app.branch-number') }}: </b> {{ $bankAccount->branch_number ?? '---' }} <br></span>

                           <span class="bank-detail-wrap">  <b>{{ __('app.iban-number') }}: </b> {{ $bankAccount->iban_number ?? '---' }} <br></span>

                        </div>

                 </label>

                </div>

            </div>

        @endforeach

    <div class="col-lg-12">

        <div class="row mt-4">

            <div class="col-lg-8">

                    @csrf

                    <div class="row">

                        {{-- user detials --}}



                            <div class="col-lg-12 mb-xxl-3 mb-1">

                                <div class="form-group">

                                    <label ><b>{{ __('app.bank-name') }} <span class="text-red">*</span></b></label>

                                    <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="{{ __('app.bank-name') }}" value="{{ $subscription->user_account ? $subscription->user_account->bank_name : '' }}" required>

                                    {{-- <label for="floatingPassword">{{ __('app.password') }}</label> --}}

                                </div>

                            </div>

                        <div class="col-lg-12 mb-xxl-3 mb-1">

                            <div class="form-group">

                                <label ><b>{{ __('app.account-title') }} <span class="text-red">*</span></b></label>

                                <input type="text" class="form-control" name="account_title" id="account_title" placeholder="{{ __('app.account-title') }}" value="{{ $subscription->user_account ? $subscription->user_account->account_title : '' }}" required>

                            </div>

                        </div>

                            <div class="col-lg-12 mb-xxl-3 mb-1">

                                <div class="form-group">

                                    <label ><b>{{ __('app.account-number') }} <span class="text-red">*</span></b></label>

                                    <input type="text" class="form-control" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="account_number" id="account_number" placeholder="{{ __('app.account-number') }}" value="{{ $subscription->user_account ? $subscription->user_account->account_number : '' }}" required>

                                </div>

                            </div>

                            <div class="col-lg-12 mb-xxl-3 mb-1">

                                <div class="form-group">

                                    <label ><b>{{ __('app.branch-number') }} <span class="text-red">*</span></b></label>

                                    <input type="text" class="form-control" name="branch_code" placeholder="{{ __('app.branch-number') }}" value="{{ $subscription->user_account ? $subscription->user_account->branch_code : '' }}" required>

                                </div>

                            </div>

                            <div class="col-lg-12 mb-xxl-3 mb-1">

                                <div class="form-group">

                                    <label ><b>{{ __('app.iban-number') }} <span class="text-red">*</span></b></label>

                                    <input type="text" class="form-control" name="iban_number" placeholder="{{ __('app.iban-number') }}" value="{{ $subscription->user_account ? $subscription->user_account->iban_number : '' }}" required>

                                </div>

                            </div>

                        <div class="col-lg-12 mb-xxl-3 mb-1">

                            <div class="form-group">

                                <label ><b>{{ __('app.amount') }}</b><span class="text-red">*</span></label>

                                <input type="number" placeholder="{{ __('app.amount') }}" value="{{ $subscription->amount }}" class="form-control" required>

                            </div>

                        </div>

                        <div class="col-lg-12 mb-xxl-3 mb-1">

                            <div class="form-group">

                                <label ><b>{{ __('app.upload-reciept') }} <span class="text-red">*</span></b></label>

                                <input type="file" name="reciept" id="reciept" class="form-control" accept="image/*" required>

                            </div>

                        </div>



                        <div class="col-lg-12 mb-xxl-3 mb-1">

                            <div class="form-group">

                                <label ><b>{{ __('app.want-save-detail') }}</b></label>

                                <input type="checkbox" name="is_allow" id="is_allow" value="1">

                            </div>

                        </div>



                    </div>

                </form>

            </div>

            <div class="col-lg-4 d-flex justify-content-center align-items-center">

                <div class="form-group" id="reciept-img-sec">

                    <img src="{{ asset('assets/home/images/reciept.png') }}" alt="" class="img-fluid" id="default-img-reciept">

                </div>

            </div>

        </div>

    </div>

</div>

