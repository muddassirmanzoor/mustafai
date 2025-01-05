@extends('user.layouts.layout')
<style>
    .country-code-wrap{
    display: flex;
    padding: 9px;
    }
    .select2-container .select2-selection--single {
    height: 45px !important;
    border: 1px solid #aaa !important;
    border-right: 0 !important;
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}

</style>

@section('content')

<div class="row user-profile-setting">

    <!-- /.col -->
    <div class="col-md-12">
        <div class="card">
        <div class="card-header p-2 pr0-set-btn">
            <ul class="nav nav-pills plantypes-icons">
            <li class="nav-item"><a class="nav-link active add-more-btn" data-toggle="collapse" data-target="#collapseSettting" aria-expanded="true" aria-controls="collapseSettting">{{__('app.settings')}}</a></li>
            </ul>
        </div><!-- /.card-header -->
        <div class="collapse show" id="collapseSettting">
        <div class="card-body">
            <div class="tab-content">

            <div class="tab-pane active" id="settings">
                <form class="form-horizontal profile--setting--from" id="profile_setting" action="{{ URL('user/profile-settings') }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" value="1" />
                <div class="form-group row">
                    <label for="user_name" class="col-form-label">{{__('app.user-name')}}</label>
                    <div class="">
                    <input type="text" class="form-control" readonly value="{{auth()->user()->user_name}}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="user_name" class="col-form-label">{{__('app.name')}}</label>
                    <div class="">
                    <input type="text" class="form-control" id="user_name" placeholder="{{__('app.enter') . __('app.name')}}" name="user_name" value="{{auth()->user()->user_name_english}}" required>
                    </div>
                </div>
                <div class="form-group row " id="to_append_phone_number_div_1">
                    <label for="phone_number" class="col-form-label">{{__('app.phone-number')}}</label>
                    <div class="d-flex ">
                    {{-- <select name="country_code_id" class="form-control country-code-select" name="" id="" style="max-width: 90px">
                        @foreach ($country_codes as $code)
                        <option value="{{ $code->id }}" {{auth()->user()->country_code_id == $code->id ? 'selected' :'' }}>{{ $code->phonecode }}</option>
                        @endforeach
                    </select> --}}
                    <select id="profile-setting" class="profile-setting country-code-select country_code_select" name="country_code_idd" style="width: 150px;">
                        @foreach ($country_codes as $code)
                        <option value="{{ $code->id }}" {{auth()->user()->country_code_id == $code->id ? 'selected' :'' }} data-img_src="{{ getS3File('flags/'.$code->country_code.'.png') }}">({{ $code->phonecode }})</option>
                        @endforeach
                    </select>
                    <input type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" id="phone_number" placeholder="{{__('app.enter') . __('app.phone-number')}}" name="phone_number" value="{{auth()->user()->phone_number}}" required>
                        <button onclick="addPhoneNumberRow()" class="btn btn-success ms-sm-2  ms-1">+</button>
                    </div>

                </div>
                <div class="to_append_phone_number_div">
                    @foreach(auth()->user()->secondaryPhones as $secondaryPhone)
                        <div class="d-flex mt-3 align-items-center">
                            <select id="profile-setting-{{ $secondaryPhone->id }}" class="profile-setting country-code-select country_code_select" name="country_code_id[]" style="width: 150px;">
                                @foreach ($country_codes as $code)
                                    <option value="{{ $code->id }}" {{ $secondaryPhone->country_code_id == $code->id ? 'selected' :'' }} data-img_src="{{ getS3File('flags/'.$code->country_code.'.png') }}">({{ $code->phonecode }})</option>
                                @endforeach
                            </select>
                            <input type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control input-phone"  name="secondary_phone_number[]" value="{{ $secondaryPhone->phone_number }}" required>
                            <button class="btn btn-danger ms-sm-2 ms-1" onclick="removePhoneNumberRow(this)">-</button>
                        </div>
                    @endforeach
                </div>
                <div class="form-group row">
                    <label for="email" class="col-form-label">{{__('app.email')}}</label>
                    <div class="">
                    <input type="email" class="form-control" id="email" placeholder="{{__('app.enter') . __('app.email')}}" name="email" value="{{auth()->user()->email}}" required>
                    </div>
                </div>
                {{-- <div class="form-group row">
                    <label for="address" class="col-form-label">{{__('app.address')}}</label>
                    <div class="">
                        <input type="text" class="form-control" id="address" placeholder="{{__('app.enter') . __('app.address')}}" name="address" value="{{auth()->user()->address}}" required>
                    </div>
                </div> --}}
                <div class="form-group row dash-pass-sh-hi">
                    <label for="old_password" class="col-form-label">{{__('app.old-password')}}</label>
                    <div class="show-hide-pass-eye div-custom">
                    <input type="password" class="form-control" id="old_password" placeholder="{{__('app.enter') . __('app.old-password')}}" name="old_password" required >
                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                    </div>
                </div>
                <div class=" d-flex justify-content-end mt-2" >

                    <p class="">
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" onclick="requiredFrom()">
                        {{__('app.change-password')}}
                    </button>
                    </p>
                </div>
                <div class="collapse mt-3" id="collapseExample">
                    <div class="card card-body">
                    <div class="row dash-pass-sh-hi ">
                        <div class="col-md-6">
                        <label for="password" class="">{{__('app.new-password')}}</label>
                        <div class="form-group show-hide-pass-eye div-custom">
                            <input type="password" passwordCheck_setting="passwordCheck_setting" class="form-control" id="password" placeholder="{{__('app.enter') . __('app.new-password')}}" name="password"   required>
                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                        </div>

                        </div>
                        <div class="col-md-6">
                        <label for="confirm_password" class="">{{__('app.confirm-password')}}</label>
                        <div class="form-group show-hide-pass-eye div-custom">
                            <input type="password" class="form-control" id="confirm_password" placeholder="{{__('app.enter') . __('app.confirm-password')}}" name="confirm_password"  required>
                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password eye-custom" id="eye-custom"></span>
                        </div>

                        </div>

                    </div>

                    </div>
                </div>
                <div class="form-group row mt-2">
                    <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">{{__('app.update')}}</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div><!-- /.card-body -->
        </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->



@endsection
@push('scripts')
 <script>

jQuery.validator.addMethod("signUpPhone", function (phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length < 13 &&
        phone_number.match(/^[1-9][0-9]*$/);
}, "Please specify a valid phone number start with positive integer");
// jQuery.validator.addMethod("allRequired", function(value, elem){
//         // Use the name to get all the inputs and verify them
//         var name = elem.name;
//         return  $('input[name="'+name+'"]').map(function(i,obj){return $(obj).val();}).get().every(function(v){ return v; });
//     });
     //___________Validation Of Profile Setting form________________//

     setTimeout(function() {
        $('.input-phone').each(function(e) {
            $(this).rules('add', {
                required: true,
                signUpPhone: true,
            });
        })
}, 0);

jQuery.validator.addMethod("passwordCheck_setting",
        function(value, element, param) {
            if (this.optional(element)) {
                return true;
            } else if (!/[a-z]/.test(value)) {
                return false;
            } else if (!/[0-9]/.test(value)) {
                return false;
            }

            return true;
        },
        "Password must contain characters and digits");

     $('#profile_setting').validate({
        ignore:":not(:visible)",
        rules:
        {
          password: {
              minlength: 8
          },
          confirm_password: {
              minlength: 8,
              equalTo: "#password"
          },


          phone_number: {
            required: true,
                    signUpPhone: true,
            },



        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
        //   $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },

	});
 </script>
    {{-- @include('user.scripts.library-script') --}}
    @include('home.scripts.phone-code-script')

 <script>
     var idCounter = 100;

     function addPhoneNumberRow() {
        var dynamicData =$(`
                <div class="form-group row ">

                        <div class=" d-flex mt-3 align-items-center">
                        <select id="profile-setting-${idCounter}" class=" profile-setting country-code-select country_code_select" name="country_code_id[]" style="width: 150px;">
                                                @foreach ($country_codes as $code)
                                <option value="{{ $code->id }}" data-img_src="{{ getS3File('flags/'.$code->country_code.'.png') }}">({{ $code->phonecode }})</option>
                                                @endforeach
                            </select>
                            <input type="text" inputmode="numeric" pattern="[0-9]*"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control input-phone"  name="secondary_phone_number[]" required>
                            <button class="btn btn-danger ms-sm-2 ms-1" onclick="removePhoneNumberRow(this)">-</button>
                        </div>
                </div>
        `);

        $(".to_append_phone_number_div").append(dynamicData)



         idCounter++;

         var options = {
             'templateSelection': custom_template,
             'templateResult': custom_template,
         }

         $('.profile-setting').select2(options);


     }

     function removePhoneNumberRow(_this) {
         $(_this).parent().remove()
     }
 </script>
@endpush
