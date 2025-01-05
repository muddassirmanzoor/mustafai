@extends('user.layouts.layout')

@push('styles')
<style>
    .bussiness-collapse-row.d-flex.justify-content-between.align-items-center.mt-3.field-error {
        color: red;
    }
    .fondation-pdf-form label.error{
        color: transparent;
        display: none;
    }
    .fondation-pdf-form label.error:after{
        content:'*';
        color: red;
        display: none;
    }
    input.invalid-field{
        border-bottom: 1px solid red !important;
        background-color: #e8c1c1;
    }
</style>
@endpush
@section('content')

<div class="d-flex mb-3 select-date-heading">
    <h4 class="text-green">{{ __('app.select-date-on-which-pay') }}</h4>
</div>

<div class="row approved-invoive dash-common-card business-plan">
    <input type="hidden" name="application_id" value="{{$application_id}}">

        @php
            $index=0;
            $tabsHeader = '';
            $tabsContent = '';
        @endphp

        @foreach($dates as $key => $tab)

            @php
                if($index == 0)
                {
                    $active = 'active';
                    $show = 'show';
                }
                else {
                    $active = '';
                    $show = '';
                }


                $tabsHeader .= '<button class="nav-link '. $active .'" id="v-pills-'.$index.'-tab" data-bs-toggle="pill" data-bs-target="#v-pills-'.$index.'" type="button" role="tab" aria-controls="v-pills-'.$index.'" aria-selected="true">'.$key.'</button>';

                $tabsContent .= '<div class="row mt-3 tab-pane fade '. $show . ' ' .$active .'" id="v-pills-'.$index.'" role="tabpanel" aria-labelledby="v-pills-'.$index.'-tab">';
                foreach($dates[$key] as $date)
                {
                    $class = 'active-date-lable';
                    if(in_array($date,$unpaid))
                    {
                        $class = 'pending-date-lable';
                    }
                    if(in_array($date,$paid))
                    {
                        $class = 'approved-date-lable';
                    }
                    else if(!empty($relief_date) && (strtotime($date) >= $relief_date->start_date && strtotime($date) <= $relief_date->end_date))
                    {
                        $class = 'non-active-date-lable';
                    }

                    $tabsContent .=
                    '   <div class="col-sm-2 date-boxes">
                            <label class="labl">
                                <input type="checkbox" name="date" class="bp-input do-not-ignore cls-'.$class.'" value="'.$date.'" required />
                                <div class="'.$class.'">'.$date.'</div>
                            </label>
                        </div>
                   ';
                }
                $tabsContent .= '</div>';

            @endphp
            @php $index++; @endphp

        @endforeach

        <div class="row">
           <div class="col-12">
            <div class="nav nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    {!! $tabsHeader !!}
                </div>
           </div>
            <div class="col-12">
                <div class="business-plan-calendar tab-content" id="v-pills-tabContent">
                    {!! $tabsContent !!}
                </div>
            </div>
        </div>

    {{-- @forelse($dates as $date)
        <div class="col-sm-2 date-boxes">
            <label class="labl">
                @php
                $class = 'active-date-lable';
                if(in_array($date,$paid))
                {
                    $class = 'non-active-date-lable';
                }
                else if(!empty($relief_date) && (strtotime($date) >= $relief_date->start_date && strtotime($date) <= $relief_date->end_date))
                {
                    $class = 'non-active-date-lable';
                }
                @endphp
                <input type="checkbox" name="date" class="bp-input do-not-ignore cls-{{$class}}" value="{{$date}}"/>
                <div class="{{ $class }}" >{{$date}}</div>
            </label>
        </div>
    @empty --}}
    {{-- <div class="dash-common-card mt-3">
        <p class="text-center">Not Found!</p>
    </div>
    @endforelse --}}
</div>

<div class="plantypes-icons d-flex justify-content-end mt-3">
    <a href="javascript:void(0)" class="btn add-more-btn mt-sm-0 mt-2" onclick="payNowModal()">{{ __('app.pay-now') }}</a>
</div>
<div class="modal fade common-model-style pay-now-invoice-modal" id="pay-now" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green" id="exampleModalLabel">{{ __('app.pay-now') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="">
                <form id="pay-now-form">
                  <div class="form-group">
                    <input type="hidden" value="{{$application_id}}" name="application_id" required></div>

                    <label for=""><b>{{ __('app.choose-invoice') }}</b></label>
                    <input type="file" name="invoice" required>

                    <br>
                    <label for="" class="my-2"><b>{{ __('app.plan-accounts') }}</b></label>
                    @foreach($planAccounts as $key => $account)
                        {{--  <div>
                            <input type="radio" name="plan_account_id" value="{{ hashEncode($account['id']) }}" required>
                            <label for="">{{$key}}</label>
                            <label for="">
                                {{ implode('--',$account['name']) }}
                            </label>
                        </div>  --}}
                        <label for="mustafai_account_id_{{ $key }}" style="width:100%;">

                            <div class="bank-radio-wrapper d-flex align-items-center " style="border: 1px solid #d9d9d9; padding: 13px 20px 14px 12px; background: #f0f0f0; cursor: pointer;">
                                <input type="radio" id="mustafai_account_id_{{ $key }}" name="plan_account_id" value="{{ hashEncode($account['id']) }}" required class="mx-2">
                                <b class="mx-2">{{$key}}</b>
                                <span class="bank-detail-wrap justify-content-between">
                                    {{ implode('--',$account['name']) }}
                                </span>
                            </div>
                        </label>

                    @endforeach
                    <label for="" class="my-2"><b>{{ __('app.your-accounts') }}</b></label>
                    @foreach($accounts as $key => $account)
                        {{--  <div>
                            <label for="">{{$key}}</label>
                            <input type="radio" name="account_id" value="{{ hashEncode($account['id']) }}" required>
                            <label for="">
                                {{ implode('--',$account['name']) }}
                            </label>
                        </div>  --}}
                        <label for="user_account_id_{{ $key }}" style="width:100%;">

                            <div class="bank-radio-wrapper d-flex align-items-center " style="border: 1px solid #d9d9d9; padding: 13px 20px 14px 12px; background: #f0f0f0; cursor: pointer;">
                                <input type="radio" id="user_account_id_{{ $key }}" name="account_id" value="{{ hashEncode($account['id']) }}" required class="mx-2">
                                <b class="mx-2">{{$key}}</b>
                                <span class="bank-detail-wrap justify-content-between">
                                    {{ implode('--',$account['name']) }}
                                </span>
                            </div>
                        </label>
                    @endforeach
                    <div class="form-header billing-header mt-3">
                        <label class="order-summaray">{{ __('app.invoice-summary') }}</label>
                    </div>
                    <div class="row px-3">
                        <table class="table invoices-table-wrap ">
                            <input type="hidden" id="amount" value="{{ $amount }}">
                            <tbody>
                                <tr>
                                    <th>{{ __('app.total-amount') }}</th>
                                    <td class="invoice_amount">0</td>
                                </tr>
                                <tr>
                                    <th>{{ __('app.selected-dates') }}</th>
                                    <td class="invoice_dates"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="green-hover-bg theme-btn" onclick="payInvoice()">{{ __('app.pay-invoice') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function payNowModal()
    {
        var lenght=$('.cls-active-date-lable:checked').length;
        var price =(lenght*$('#amount').val());
        if($('.cls-active-date-lable:checked').length)
        {
            $('.invoice_amount').html(price);
            dates = new Array();
            $('.cls-active-date-lable:checked').each(function() {
                    dates.push($(this).val());
                });
                var invoice_dates=dates.toString();
                items = invoice_dates.split(",");
                $('.invoice_dates').html(' ');
                items.forEach(function(item){
                    $(".invoice_dates").append("<span class='paynow-dates'>"+item+"</span>")
                })
                //$('.invoice_dates').html(invoice_dates);
                $('#pay-now').modal('show');
                //$("td").append("<span>"+item+"</span>")
        }
        else
        {
            Swal.fire({
                icon: 'error',
                text: '{{ __('app.atleast-on-date') }}',
                type: 'error'
            })
        }
    }

    function payInvoice()
    {
        if($('#pay-now-form').valid())
        {
            var selectedDateIds = $.map($('.cls-active-date-lable:checked'), function(c){return c.value; })

            var formData = new FormData( $('#pay-now-form')[0] );
            formData.append('selected_dates',selectedDateIds);

            $.ajax({
                type: "POST",
                url: "{{ route('user.invoice.pay') }}",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function (data) {
                    if(data.status)
                    {

                        $('#pay-now').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            text: data.message,
                            type: 'success'
                        })
                        $("#pay-now-form")[0].reset();
                        location.reload(true);

                    }
                    else
                    {
                        Swal.fire({
                            icon: 'error',
                            text: data.message,
                            type: 'error'
                        })
                    }
                    $('.preloader').hide();

                }

            });
        }
    }

    $(".concurrent_payment_checkbox").change(function() {
        if(this.checked) $('.end_date_div').css('display', 'block');
        if(! this.checked) $('.end_date_div').css('display', 'none');
    });

</script>

@endpush
