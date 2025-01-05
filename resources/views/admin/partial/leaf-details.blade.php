@forelse($bookLeafs as $key=>$val)
<div class="col-md-3 leaf_class_{{$val->leaf_number}} dynamic_cards" id="dynamic_card_{{$val->id}}">
    <div class="card">
        <div class="card-header bg-primary">
            Receipt Number : {{$val->leaf_number}}
        </div>
        <div class="card-body">
            
            <form id="leaf_form_{{$val->id}}" class="form-horizontal label-left book-receipt-form"  enctype="multipart/form-data" method="POST">
                <div class="row">
                        {!! csrf_field() !!}  
                    <input type="hidden" id="id_{{$val->id}}" name="id" value="{{encodeDecode($val->id)}}" />
                    <div class="col-md-12">
                    <label class=" col-form-label"> Donar Name  <span class="text-red">*</span></label>
                    <div class="mb-1">
                        <input type="text" class="form-control" placeholder="Enter Donar Name " id="donar_name_{{$val->id}}" name="donar_name" value="{{ $val->donar_name }}" required="">
                    </div>	
                    </div>
                    <div class="col-md-12">
                    <label class=" col-form-label"> Donar Address  <span class="text-red">*</span></label>
                    <div class="mb-1">
                        <input type="text" class="form-control" placeholder="Enter Donar Address " id="donar_address_{{$val->id}}" name="donar_address" value="{{ $val->donar_address }}" required="">
                    </div>	
                    </div>
                    <div class="col-md-12">
                    <label class=" col-form-label"> Donation Amount  <span class="text-red">*</span></label>
                    <div class="mb-1">
                        <input type="number" class="form-control" placeholder="Enter Donation Amount " id="donation_amount_{{$val->id}}" name="donation_amount" value="{{ $val->donation_amount }}" required="">
                    </div>	
                    </div>
                    <div class="col-md-12">
                        <label class=" col-form-label"> Receipt Image  </label>
                        <div class="mb-1">
                            <input type="file" id="imgInp_{{$val->id}}" class="form-control" name="receipt_image" placeholder="Enter Donation Amount " id="receipt_image_{{$val->id}}"  onchange="previewFile($(this))" />
                        </div>	
                    </div>
                    <div class="col-md-12">
                            <img src="{{(empty($val->receipt_image)?asset('assets/home/images/reciept.png') : getS3File($val->receipt_image))}}" id="img_prev_{{$val->id}}" width="70" height="70" alt="">
                    </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-primary float-right" data-id="{{$val->id}}" onclick="updateReceiptLeaf($(this))">Update Record</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@empty 
@endforelse