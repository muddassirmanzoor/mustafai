<div>
    <form method="post" id="noterecieptForm">
        @csrf
        <input type="hidden" class="reciept-id" name="reciept_id" id="reciept_id" value="{{ $reciept->id }}">
        <div class="form-outline">
            <label class="form-label" for="textAreaExample2">Note</label>
            <textarea class="form-control note-des" name="note" id="note" rows="8">{{ !empty( $reciept->note)? $reciept->note:'' }}</textarea>
        </div>
    </form>
</div>
