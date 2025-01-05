<form id="send-msg-form">
    <input class="form-control form-control-lg chat-section enable" type="text" name="message"
        aria-label=".form-control-lg example" id="chat-msg" required autocomplete="off" />

    <button type="button" id="recordButton" class="voice-section voice-section-btn disable">{{ __('app.record') }}</button>
    <button type="button" id="pauseButton" class="voice-section voice-section-btn disable"
        disabled>{{ __('app.pause') }}</button>
    <button type="button" id="stopButton" class="voice-section voice-section-btn disable"
        disabled>{{ __('app.stop') }}</button>
    <ol id="recordingsList" class="voice-section disable"></ol>
    <div id="is-recording" class="voice-section disable">
        <div class="recording recording-container" style="visibility:hidden;">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </div>
</form>

<span class="link-assets" style="right: 140px;" id="attach-section">
    <button type="button" class="link-btn btn" onclick="attachAsset()">
        <i class="fa fa-paperclip" aria-hidden="true"></i>
    </button>
</span>

<span class="link-assets send-sms-btn" id="send-sms-btn">
    <button type="button" class="link-btn btn" id="send-msg-btn" onclick="sendMessage()">
        <i class="fa fa-paper-plane" aria-hidden="true"></i>
    </button>
</span>

<span class="link-assets" id="send-voice-btn" style="display:none;">
    <button type="button" class="link-btn btn">
        <i class="fa fa-paper-plane" aria-hidden="true"></i>
    </button>
</span>

<div class="say-something">
    <button id="chat-mike-btn" type="button" class="btn mike-btn" data-attr-sec="voice"
        onclick="openChatVoiceSections($(this))">
        <i class="fa fa-microphone" aria-hidden="true"></i>
    </button>
</div>
