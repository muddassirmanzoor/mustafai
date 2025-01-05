@extends('admin.layout.app')

@push('header-scripts')
   <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/fontawesome-free/css/all.min.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/select2/css/select2.min.css')}}">
  <style>
    .setting-form-fields.form-invalid {
        border: 1px solid red;
    }
  </style>
@endpush

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Header Settings</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ URL('admin/header-settings') }}">Header Settings</a></li>
              <li class="breadcrumb-item active">Set Header Setting</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              {{-- <div class="card-header">
                <h3 class="card-title">Header Settings Form</h3>
              </div> --}}
              <!-- /.card-header -->
              <div class="card-body">
                <form id="header-setting-form" action="{{ URL('admin/header-settings') }}" enctype="multipart/form-data" method="POST">
                      {!! csrf_field() !!}

                      <div id="menue-rows">
                         @php
                          $rowCounter =0;
                          $parent_counter =0;
                         @endphp
                        @foreach($headerSettings as $key => $row)
                           @php
                            $rowCounter++;
                           @endphp
                          <div class="row menu-rows" id="menuRow_{{$rowCounter}}">
                              <div class="col-sm-1">
                                  <div class="form-group">
                                    <label>Order <span class="text-red">*</span></label>
                                    <input class="form-control setting-form-fields" min='1' type="number" value="{{ $row['order'] }}" name="order[]" required>
                                  </div>
                              </div>
                              @foreach (activeLangs() as $lang)
                              <div class="col-sm-2">
                                  <div class="form-group">
                                  <label>Name {{ $lang->name_english }} <span class="text-red">*</span></label>
                                  <input type="text" class="form-control setting-form-fields" placeholder="Name {{ $lang->name_english }}" name="name_{{ $lang->key }}[]" value="{{isset($row['name_'.$lang->key]) ? $row['name_'.$lang->key] : ''}}" required>
                                  </div>
                              </div>
                              @endforeach
                              <div class="col-sm-2 link-sec">
                                  <div class="form-group">
                                  <label>Link <span class="text-red">*</span></label>
                                  <input type="text" class="form-control setting-form-fields" placeholder="link" name="link[]" value="{{isset($row['link']) ? $row['link'] : ''}}" required>
                                  </div>
                              </div>
                              <div class="d-flex">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-danger form-control" style="margin-top: 32px;width:auto" onclick="removeRow($(this),{{$rowCounter}})">X</button>
                                </div>
                                <div class="d-flex ml-2" style="">
                                  <button type="button" class="btn btn-primary form-control child-add-btn" style="margin-top: 32px;" onclick="addChild($(this),{{$parent_counter}})">+</button>
                              </div>
                              </div>
                          </div>
                          <div class="sub-menues">
                              @foreach($row->getChilds as $child)
                                @php
                                 $rowCounter++;
                                @endphp
                                <div class="row" id="menuRow_{{$rowCounter}}">
                                  <div class="col-sm-1">
                                      <div class="form-group">
                                        <label>Order <span class="text-red">*</span></label>
                                        <input class="form-control setting-form-fields" min="1" type="number" value="{{$child->order}}" name="child_order[{{$key}}][]" required>
                                      </div>
                                  </div>
                                  @foreach (activeLangs() as $lang)
                                  <div class="col-sm-2">
                                      <div class="form-group">
                                      <label>Name {{ $lang->name_english }} <span class="text-red">*</span></label>
                                      <input type="text" class="form-control setting-form-fields" placeholder="Name {{ $lang->name_english }}"  name="child_name_{{ $lang->key }}[{{$key}}][]" value="{{$child->{'name_'.$lang->key} }}" required>
                                      </div>
                                  </div>
                                  @endforeach

                                  <div class="col-sm-2 link-sec">
                                      <div class="form-group">
                                      <label>Link <span class="text-red">*</span></label>
                                      <input type="text" class="form-control setting-form-fields" placeholder="link"  name="child_link[{{$key}}][]" value="{{$child->link}}" required>
                                      </div>
                                  </div>

                                  <div class="col-sm-2">
                                      <button type="button" class="btn btn-danger" style="margin-top: 32px;width:auto" onclick="removeRow($(this),{{$rowCounter}})">X</button>
                                  </div>
                                </div>
                              @endforeach
                          </div>
                          @php
                          $parent_counter++;
                          @endphp
                        @endforeach
                      </div>

                      <hr>
                      @if(have_right('Add-New-Row-Header-Setting'))
                      <button type="button" class="btn btn-primary" onclick="addNewMenuRow()">Add New Row</button>
                      @endif
                      <hr>
                      <input type="hidden" id="rowCounter" value="{{$rowCounter}}">
                      @if(have_right('Update-Header-Setting'))
                        <div class= "card-body">
                            <div class="form-group text-right">
                              <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary float-right" onclick="checkFormValidation()"> Update Header </button>
                              </div>
                            </div>
                        </div>
                      @endif
                  </form>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
      <input type="hidden"  id="parent_row" value="{{$parent_counter}}">
    </section>
    <!-- Main content -->
  </div>

  <div id="new-menue-row" style="display:none">
    <div class="row menu-rows" id="menuRow_data">
      <div class="col-sm-1">
          <div class="form-group">
            <label>Order</label>
            <input class="form-control setting-form-fields" min="1" type="number" value="" name="order[]" required>
          </div>
      </div>
      @foreach (activeLangs() as $lang)
      <div class="col-sm-2">
          <div class="form-group">
          <label>Name {{ $lang->name_english }}</label>
          <input type="text" class="form-control setting-form-fields" placeholder="Name {{ $lang->name_english }}" name="name_{{ $lang->key }}[]" value="" required>
          </div>
      </div>
      @endforeach

      <div class="col-sm-2 link-sec">
          <div class="form-group">
          <label>Link</label>
          <input type="text" class="form-control setting-form-fields" placeholder="link" name="link[]" value="" required>
          </div>
      </div>
      <div class="d-flex">
        <div class="d-flex">
            <button type="button" class="btn btn-danger form-control" style="margin-top: 32px;width:auto;" onclick="removeRow($(this))">X</button>
        </div>
        <div class="d-flex ml-2 addChildNum"  >
            <button type="button" class="btn btn-primary form-control child-add-btn" data-parent-num="{{$parent_counter}}" style="margin-top: 32px;" onclick="addChild($(this),{{$parent_counter}})">+</button>
        </div>
      </div>
    </div>

    <div class="sub-menues"></div>
  </div>

  <div id="new-menue-child-row"  style="display:none">
      <div class="row" id="childrow_data">
        <div class="col-sm-1">
            <div class="form-group">
              <label>Order</label>
              <input class="form-control setting-form-fields" min="1" type="number" value="" dataVal="child_order" name="" required>
            </div>
        </div>
        @foreach (activeLangs() as $lang)
        <div class="col-sm-2">
            <div class="form-group">
            <label>Name {{ $lang->name_english }}</label>
            <input type="text" class="form-control setting-form-fields" placeholder="Name {{ $lang->name_english }}" dataVal="child_name_{{ $lang->key }}" name="child_name_{{$lang->key}}[1]" value="" required>
            </div>
        </div>
        @endforeach

        <div class="col-sm-2 link-sec">
            <div class="form-group">
            <label>Link</label>
            <input type="text" class="form-control setting-form-fields" placeholder="link" dataVal="child_link" name="" value="" required>
            </div>
        </div>

        <div class="col-sm-2" style="">
            <button type="button" class="btn btn-danger" style="margin-top: 32px;" onclick="removeRow($(this))">X</button>
        </div>
      </div>
  </div>



@endsection

@push('footer-scripts')
  <!-- jQuery -->
  <script src="{{asset('assets/admin/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- jquery-validation -->
  <script src="{{asset('assets/admin/jquery-validation/jquery.validate.min.js')}}"></script>
  <script src="{{asset('assets/admin/jquery-validation/additional-methods.min.js')}}"></script>
  <!-- InputMask -->
  <script src="{{asset('assets/admin/moment/moment.min.js')}}"></script>
  <script src="{{asset('assets/admin/inputmask/jquery.inputmask.min.js')}}"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('assets/admin/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
  <!-- Page specific script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script>

    function addNewMenuRow()
    {
       var rowCounter=parseInt($('#rowCounter').val())+parseInt(1);
       var stringmenu=$('#new-menue-row').html();
       var newString=stringmenu.replace('menuRow_data', "menuRow_"+rowCounter)
       var finalString=newString.replace('removeRow($(this))',`removeRow('$(this)',${rowCounter})`)
       var finalString=newString.replace('removeRow($(this))',`removeRow('$(this)',${rowCounter})`)
      $('#menue-rows').append(finalString);
      $('#rowCounter').val(rowCounter);

      var parent_new_val=parseInt($("#parent_row").val())+parseInt(1);
      $(".addChildNum:last").html('<button type="button" class="btn btn-primary form-control child-add-btn"  style="margin-top: 32px;" onclick="addChild($(this),'+ parent_new_val +')">+</button>')

      $("#parent_row").val(parent_new_val)
    }

    function addChild(_this,parent_id='')
    {
      var parIndex = $('.child-add-btn:visible').length - 1;
      $( "#new-menue-child-row .row input" ).each(function( index ) {
        var name = $(this).attr('dataVal');

        $(this).attr('name',name+'['+parent_id+'][]')
      });
      var rowCounter=parseInt($('#rowCounter').val())+parseInt(1);
      var stringChild =$('#new-menue-child-row').html().replace('childrow_data', "menuRow_"+rowCounter);
      var finalString=stringChild.replace('removeRow($(this))',`removeRow('$(this)',${rowCounter})`);
      // var finalString=finalString.replaceAll('[]','[2]');

      $(_this).parents('.row').next('.sub-menues').append(finalString);
      $('#rowCounter').val(rowCounter);


    }

    function removeRow(_this,id)
    {
      // $(_this).parent().parent().next('.sub-menues').remove();
      // $(_this).parent().parent().remove();
      $('#menuRow_'+id).next('.sub-menues').remove();
      $('#menuRow_'+id).remove();
      $('#rowCounter').val(parseInt($('#rowCounter').val())+parseInt(1));
    }

    function removeChildRow(_this)
    {
      $(_this).parents('.row').remove();
    }

    function checkFormValidation()
    {
        // $('form').submit();
        // return false;
        $('.setting-form-fields').removeClass('form-invalid');

        var NAMES_ENGLISH = [];
        var NAMES_URDU = [];
        var NAMES_ARABIC = [];
        var ERROR = 0;
        if($( ".setting-form-fields:visible" ).length)
        {
          $( ".setting-form-fields:visible" ).each(function( index )
          {
              if($(this).val() == "")
              {
                $(this).addClass('form-invalid');
                toastr.options =
                {
                  "closeButton" : true,
                  "progressBar" : true
                }
                toastr.error("Fields are required.");
                ERROR = 1;
                return false;
              }

              if($(this).attr('name') == 'name_english[]' && jQuery.inArray($(this).val(), NAMES_ENGLISH) !== -1 )
              {
                $(this).addClass('form-invalid');
                toastr.options =
                {
                  "closeButton" : true,
                  "progressBar" : true
                }
                toastr.error("Names should not be the same.");

                $(this).val('');
                ERROR = 1;
                return false;
              }
              if($(this).attr('name') == 'name_urdu[]' && jQuery.inArray($(this).val(), NAMES_URDU) !== -1 )
              {
                $(this).addClass('form-invalid');
                toastr.options =
                {
                  "closeButton" : true,
                  "progressBar" : true
                }
                toastr.error("Names should not be the same.");

                $(this).val('');
                ERROR = 1;
                return false;
              }
              if($(this).attr('name') == 'name_arabic[]' && jQuery.inArray($(this).val(), NAMES_ARABIC) !== -1 )
              {
                $(this).addClass('form-invalid');
                toastr.options =
                {
                  "closeButton" : true,
                  "progressBar" : true
                }
                toastr.error("Names should not be the same.");

                $(this).val('');
                ERROR = 1;
                return false;
              }

              if($(this).attr('name') == 'name_english[]')
              {
                NAMES_ENGLISH.push($(this).val());
              }
              if($(this).attr('name') == 'name_urdu[]')
              {
                NAMES_URDU.push($(this).val());
              }
              if($(this).attr('name') == 'name_arabic[]')
              {
                NAMES_ARABIC.push($(this).val());
              }
          });
        }
        else
        {
          toastr.options =
          {
            "closeButton" : true,
            "progressBar" : true
          }
          toastr.error("At least one row required.");
          ERROR = 1;
          return false;
        }


        if(!ERROR)
        {
          $('form').submit();
        }
    }
  </script>
@endpush
