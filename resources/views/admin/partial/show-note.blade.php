<div>
    <form method="post" id="noteForm">
        @csrf
        <input type="hidden" class="contact-id" name="contact_id" id="contact_id" value="{{ $contact->id }}">
        <div class="form-outline">
            <label class="form-label" for="textAreaExample2">Note <span class="text-danger">*</span></label>
            <textarea class="form-control note-des" name="note" id="note" rows="8">{{ !empty( $contact->note)? $contact->note:'' }}</textarea>
        </div>
    </form>
</div>
