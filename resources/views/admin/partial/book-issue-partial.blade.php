<div class="col-sm-12 " id="">
    <label class=" "> Issue To sub-admin (Wasool kunida)  <span class="text-red">*</span></label>
    <div class="w-100 selectparent" id="selectparent">
        <select id="issued_to" name="issued_to" class="select2 user-select-2" required>
            {{-- <option value="">--select user</option> --}}
             @forelse($adminList as $key=>$val)
                <option value="{{$val->id}}" {{($bookData->issued_to == $val->id)?'selected':''}}>{{$val->first_name .' '.$val->last_name}}</option>
               @empty
             @endforelse
         </select>
    </div>		
</div>