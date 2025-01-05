@if($action == 'add')
    @php
    if($payment_methods_details_count>1){
        $col ='col-md-6';
    }else{
        $col ='col-md-6';
    }
    @endphp
    
    <div class="col-md-6 ">
        <div class="form-group">
            <label > Mustafai Account Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="account_title" name="account_title" placeholder="Enter Account Title" required>
        </div>
    </div>
    @foreach($payment_methods_details as $key=>$val)
    <div class="{{$col}} mb-1 {{'div_'.$val->payment_method_id}} common_div" style="" >
        <div class="form-group">
            <label > {{$val->method_fields_english}} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="dynamic_fields_{{$val->id}}" name="method_details[{{$val->id}}]" placeholder="Enter {{$val->method_fields_english}}" required>
        </div>
    </div>
    @endforeach
  @else
  @foreach($donationPaymentMethodDetails as $key=>$val)
   <div class="col-md-12 card-header">
       <h6>{{$val->account_title}}</h6>
   </div>
    {{-- <div class="{{$col}} mb-xxl-3 mb-1 {{'div_'.$val->payment_method_id}} common_div" style="" >
        <div class="form-group">
            <label >{{$val->method_fields_english}} *</label>
            <input type="text" class="form-control" id="dynamic_fields_{{$val->id}}" name="method_details[{{$val->id}}]" placeholder="Enter {{$val->method_fields_english}}" required>
        </div>
    </div> --}}
  @endforeach

@endif
