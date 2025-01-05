<script>
	//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var input; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);

function startRecording() {
	var upload = $('#send-voice-btn').find('button');
	upload.unbind( "click" );
	
	$('#recordingsList').empty();
	$('.recording-container').css('visibility','visible');
	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/

    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia()
	*/

	recordButton.disabled = true;
	stopButton.disabled = false;
	pauseButton.disabled = false

	/*
    	We're using the standard promise based getUserMedia()
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		audioContext = new AudioContext();
		gumStream = stream;
		input = audioContext.createMediaStreamSource(stream);
		rec = new Recorder(input,{numChannels:1})
		rec.record()

	}).catch(function(err) {
    	recordButton.disabled = false;
    	stopButton.disabled = true;
    	pauseButton.disabled = true
	});
}

function pauseRecording(){
	if (rec.recording){
		rec.stop();
		pauseButton.innerHTML="{{__('app.resume-sound')}}";
		$('.recording-container .bar').css("animation-play-state", "paused");
	}else{
		rec.record()
		pauseButton.innerHTML="{{__('app.pause')}}";
		$('.recording-container .bar').css("animation-play-state", "running");
	}
}

function stopRecording() {
	stopButton.disabled = true;
	recordButton.disabled = false;
	pauseButton.disabled = true;

	//reset button just in case the recording is stopped while paused
	pauseButton.innerHTML="{{__('app.pause')}}";

	//tell the recorder to stop the recording
	rec.stop();

	//stop microphone access
	gumStream.getAudioTracks()[0].stop();

	//create the wav blob and pass it on to createDownloadLink
	rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
	$('.recording-container').css('visibility','hidden');
	var url = URL.createObjectURL(blob);
	var au = document.createElement('audio');
	au.setAttribute('id', 'recorded-voice');
	var li = document.createElement('li');

	//name of .wav file to use during upload and download (without extendion)
	var filename = new Date().toISOString();

	//add controls to the <audio> element
	au.controls = true;
	au.src = url;

	//add the new audio element to li
	li.appendChild(au);

	var upload = $('#send-voice-btn').find('button');
	upload.removeAttr('disabled');
	$(upload).bind('click', function()
	{	
		if( $('#stopButton:disabled').length > 0 && $('#pauseButton:disabled').length > 0)
		{
			upload.attr('disabled','disabled');
			var data = new FormData($('#message-section-fields')[0]);
			data.append('_token',"{{csrf_token()}}");
			data.append("voice_data",blob, filename);

			$.ajax({
				type: "post",
				url: "{{route('user.send-message')}}",
				data: data,
				processData: false,
				contentType: false,
				success: function(result)
				{
					// alert("ok");
					$("#recordingsList").css('display','none');
					// var data = JSON.parse(result);
					// $('#messages-list').prepend(data.html);
					setTimeout(() => {
						$('#messages-list').animate({scrollTop: $('#messages-list').prop("scrollHeight")}, 500);
					}, 1000)

					$('#chat-msg').val('');
					$('#chat-msg').focus();
					$('#send-sms-btn').css('display','block');
					$('#send-voice-btn').css('display','none');
					$('#recordingsList').empty();
					$('#chat-mike-btn').click();
					
				}
			});
		}
	});
	recordingsList.appendChild(li);
}
</script>
