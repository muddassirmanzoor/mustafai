<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var isDropDownCLicked = false;
    var lastMessageID = 0;
    $(function() 
    {
        uploadJunk();
        // window.history.pushState({}, 'chat', "/" + "user/chats");
        let c_id = $("#contact_id").val();
        var data_type =$("#contact_id").attr('data-attr');

        if (c_id != null) {
            setTimeout(function() {
                if(data_type=='user'){
                    $('#pills-home-tab').trigger('click');
                    $("#contact_"+c_id).trigger('click');
                }if(data_type=='group'){
                    $('#pills-profile-tab').trigger('click');
                    $("#group_"+c_id).trigger('click');
                }
                window.history.pushState({},'',"/"  + "user/chats");
                window.history.replaceState({},'',"/"  + "user/chats");
            }, 1000);
        }

        window.setInterval(function() {
            openChatBox();
        }, 2000);

        $('.contact-group-sec').on('click', function() 
        {
            $('.dropdown-menu-actions').css('display','none');
            $('.contacts-groups-list').addClass('read').removeClass('unread');
            $('#message-box').css('display', 'none');
            $('#chat-box-inputs').remove();
            $('#empty-message-box').css('display', 'block');
        });

        $("#search-users").select2({
            dropdownParent: $("#add-contact-group"),
            width: 'resolve',
            placeholder: "Search a user"
        });

        $("#add-participant").select2({
            dropdownParent: $("#add-contact-group"),
            width: 'resolve',
            placeholder: "Add participant"
        });

        $(document.body).bind("keypress", "#send-msg-frm", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });

        $(document.body).on('keypress', '#chat-msg', function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var button = $('#send-msg-btn');
                button.click();
            }
        });

    });

    function openChatBox(type = '', id = '') {
        var go = 0;

        var async = true;
        var scroll = 0;

        var last_message = lastMessageID;
        if (type) 
        {
            last_message = 0;
            setInterval(function() {
                timeChange();
            }, 60 * 1000);
            
            if(!isDropDownCLicked)
            {
                $('.dropdown-menu-actions').css('display','none');
            }
            isDropDownCLicked = false;

            $('.contacts-groups-list').addClass('read').removeClass('unread');

            $('#'+((type == 'friend') ? 'contact_':type+'_')+id).addClass('unread');

            $(this).remove();

            async = false;
            scroll = 1;
            $('#messages-list').empty();
            $('#message-box').css('display', 'block');
            $('#empty-message-box').css('display', 'none');
        }


        var openType = $('#open_type').val();
        
        if((openType == 'friend'))
        {
            let contactElement = $(`#contact_${id}`);
            contactElement.find("span.sms-counts").css('display','none')
        }
        else
        {
            let groupElement = $(`#contact_${id}`);
            groupElement.find("span.sms-counts").css('display','none')
        }

        var openFriendId = '';
        var openGroup = '';

        if (!type && typeof openType !== "undefined") 
        {
            type = openType;
            openGroup = $('#open_group_id').val();
            openFriendId = $('#open_friend_id').val();
            id = (openType == 'friend') ? openFriendId : openGroup;
           
            go = 1;
        } 
        else if (type) 
        {
            go = 1;
        }
        
        if (go) 
        {
            clearInterval(myInterval);
            $.ajax({
                type: "get",
                url: "{{ route('user.get-chats') }}",
                data: {
                    type: type,
                    id: id,
                    last_message:last_message
                },
                async: async,
                success: function(result) 
                {
                    result = JSON.parse(result);

                    $("span.sms-counts").css('display','none');
                    for(let i = 0; i<result.notification_contacts.length; i++) 
                    {
                        let count = result.notification_contacts[i].unread_messages_count > 0 ? result.notification_contacts[i].unread_messages_count : '';
                        let contactElement = $(`#contact_${result.notification_contacts[i].id}`);
                        contactElement.find("span.sms-counts").text(`${count}`)
                        if(count == '')
                        {
                            contactElement.find("span.sms-counts").css('display','none');
                        }
                        else
                        {
                            contactElement.find("span.sms-counts").css('display','block');
                        }
                    }

                    for(let i = 0; i<result.notification_groups.length; i++) 
                    {
                        let count = result.notification_groups[i].count > 0 ? result.notification_groups[i].count : '';
                        let groupElement = $(`#group_${result.notification_groups[i].id}`);
                        groupElement.find("span.sms-counts").text(`${count}`)
                        if(count == '')
                        {
                            groupElement.find("span.sms-counts").css('display','none');
                        }
                        else
                        {
                            groupElement.find("span.sms-counts").css('display','block');
                        }
                    }

                    $('ul li#no-conversation').remove();

                    lastMessageID = result.last_id;

                    if($('#'+result.req_type_id).hasClass('unread'))
                    {
                        $('#chat-box-inputs').remove();
                        $('#messages-list').append(result.html);
                    }

                    if(result.readed.length)
                    {
                        for(let i = 0; i<result.readed.length; i++) 
                        {
                            $('.tick-'+result.readed[i]).find('span').removeClass('message-unreaded').addClass('message-readed');
                        }
                    }

                    if (scroll) 
                    {
                        $('#messages-list').animate({scrollTop: $('#messages-list').prop("scrollHeight")}, 500);
                        // $('#messages-list').scrollTop( $('#messages-list')[0].scrollHeight );
                    }

                    if (result.notification > 0) 
                    {
                        $('span.notifications-count').text((result.notification > 99) ? '99+' : result
                        .notification);
                        $('span.notifications-count').css('display', 'block');
                    } 
                    else 
                    {
                        $('span.notifications-count').css('display', 'none');
                    }

                    if (result.chat > 0) 
                    {
                        $('span.chat-count').text((result.chat > 99) ? '99+' : result.chat);
                        $('span.chat-count').css('display', 'block');
                    } 
                    else 
                    {
                        $('span.chat-count').css('display', 'none');
                    }

                    if (result.friend_request > 0) 
                    {
                        $('span.friend-request-count').text((result.friend_request > 99) ? '99+' : result
                        .friend_request);
                        $('span.friend-request-count').css('display', 'block');
                    } 
                    else 
                    {
                        $('span.friend-request-count').css('display', 'none');
                    }
                    // $('span.notifications-count').text((result.notification > 99) ? '99+' : result
                    //     .notification);
                    // $('span.chat-count').text((result.chat > 99) ? '99+' : result.chat);
                    // $('span.friend-request-count').text((result.friend_request > 99) ? '99+' : result
                    //     .friend_request);
                }
            });
        }
    }

    function addContactGroup(_this,groupID='') 
    {
        $('#del-group-btn').css('display','none');
        $('#group-id').val(groupID);
        var type = $('.contact-group-sec.active').text();
        $('#add-contact-group').modal('show');
        console.log(type);
        // if(){

        // }else{

        // }
        $('#add-contact-group-heading').text( ' {{  __('app.add') }} ' + type);

        if (type == 'Friends' || type == 'دوست') 
        {
            $('#type').attr('value', 1);
            $('#group-fields').css('display', 'none');
            $('#contact-fields').css('display', 'block');
        } 
        else 
        {
            $('#type').attr('value', 2);
            $('#group-fields').css('display', 'block');
            $('#contact-fields').css('display', 'none');
        }
        if(groupID)
        {
            getGroupData(groupID);
            $('#del-group-btn').css('display','block');
        }
    }

    function getGroupData(groupID)
    {
        $.ajax({
            type: "get",
            url: "{{ route('user.get-group') }}",
            data: {groupID:groupID},
            success: function(result) {
                var data = JSON.parse(result);

                var group = data.group;

                $('#group-name').val(group.name);
                $('#grp-description').val(group.description);

                $('#group-icon').attr('src',"https://mustafaipks3bucket.s3.ap-southeast-1.amazonaws.com/"+group.icon);
                var groupUsers = data.group_users;

                var selectedValues = new Array();

                $.each(groupUsers, function(index, value) {
                    selectedValues[index] = value.user_id;
                });

                $('#add-participant').val(selectedValues).change();
            }
        });
    }

    function searchSection()
    {
        // Declare variables
        $('#empty-li').remove();
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('search-section');
        filter = input.value.toUpperCase();
        ul = $('ul.friends-list');
        li = $('ul.friends-list li');

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++)
        {
            a = li.eq(i).find("div h6");

            txtValue = a.text();

            if(txtValue.toUpperCase().indexOf(filter) > -1)
            {
                li[i].style.display = "";
            }
            else
            {
                li[i].style.display = "none";
            }

        }

        ul = $('ul.friends-list');
        li = $('ul.friends-list li:visible');

        if(!li.length)
        {
            ul.append('<li id="empty-li">No record found</li>');
        }
    }

    function attachAsset(type=1)
    {
        if(type == 1)
        {
            $('#attach-file').val('');
            $('#attach-modal').modal('show');
        }
        else if(type == 2)
        {
            if($('#attach-form').valid())
            {
                // $('#chat-msg').val(1);
                // sendMessage(1);
                // $('#attach-modal').modal('hide');
            }
        }
    }

    function filterAsset(input)
    {
        var file = $(input).get(0).files[0];
		if(file)
		{
            var bytes = file.size;

            const sufixes = ['B', 'kB', 'MB', 'GB', 'TB'];
            var i = Math.floor(Math.log(bytes) / Math.log(1024));
            var size = (bytes / Math.pow(1024, i)).toFixed(2);

            if(i > 2)
            {
                Swal.fire("{{ __('app.mb-check-chat') }}", '', 'error');
                input.val('');
                return false;
            }
            else if(i == 2 && size > 26)
            {
                Swal.fire("{{ __('app.mb-check-chat') }}", '', 'error');
                input.val('');
                return false;
            }

        }
    }

    function uploadJunk()
    {
        let browseFile = $('#attach-file');
        // var allowTypes = ['gif','png','jpg','jpeg','webp'];

		let resumable = new Resumable({
			target: "{{ route('user.chat.attachment.upload')}}",
			query:{_token:'{{ csrf_token() }}'} ,// CSRF token
			// fileType: allowTypes,
			data: {},
			chunkSize: 10*1024*1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
			headers: {
				'Accept' : 'application/json'
			},
			testChunks: false,
			throttleProgressCallbacks: 1,
		});

        resumable.assignBrowse(browseFile[0]);
		// trigger when file picked
		resumable.on('fileAdded', function (file) {
			showProgress();
			resumable.upload() // to actually start uploading.
		});

		resumable.on('fileProgress', function (file) { // trigger when file progress update
			updateProgress(Math.floor(file.progress() * 100));
		});

        resumable.on('fileSuccess', function (file, response) {
            response = JSON.parse(response);
            $('#chat-msg').val(response.path);
            sendMessage(1);
            $('#attach-modal').modal('hide');
            hideProgress();
            // Swal.fire(data.message, '', 'success');
        })

        resumable.on('fileError', function (file, response) {
            Swal.fire("{{ __('app.something-went-wrong') }}", '', 'error');
		});
    }

    let progress = $('.progress');
    function showProgress() {
        progress.find('.progress-bar').css('width', '0%');
        progress.find('.progress-bar').html('0%');
        progress.find('.progress-bar').removeClass('bg-success');
        progress.show();
    }

    function updateProgress(value) {
        progress.find('.progress-bar').css('width', `${value}%`)
        progress.find('.progress-bar').html(`${value}%`)
    }

    function hideProgress() {
        progress.hide();
    }

    function createContactGroup(_this) {
        $("#create-group-contact").validate({
            ignore: [],
        });
        $("#create-group-contact").valid();


        if (!$("#create-group-contact").find('.error:visible').length) {

            var formData = new FormData($("#create-group-contact")[0]);
            var typeCon = $('.contact-group-sec.active').text();

            typeCon = (typeCon == 'Friends' || typeCon == 'دوست') ? 1 : 2;

            formData.append('type',typeCon);
            formData.append('_token', "{{ csrf_token() }}");
            $.ajax({
                type: "post",
                url: "{{ route('user.create-request') }}",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                $('.preloader').show();
                },
                success: function(result) {
                    var data = JSON.parse(result);

                    if (data.status) {
                        if (data.type == 1) {
                            var userOptions = '';
                            $.each(data.users, function(key, value) {
                                userOptions = userOptions + '<option value="' + value.id + '">' +
                                    value.user_name + '</option>';
                            });
                            $("#search-users").html(userOptions);

                            $("#search-users").select2({
                                dropdownParent: $("#add-contact-group"),
                                width: 'resolve',
                                placeholder: "Search a user"
                            });
                        } else if (data.type == 2) {
                            if(data.groupID == 0)
                            {
                                $('#groups-sidebar').append(data.html);
                                // $('#groups-sidebar').prepend(data.html);
                            }
                            else
                            {
                                $('#groups-sidebar').find('li#group_'+data.groupID).replaceWith(data.html);
                            }
                        }

                        $('#add-contact-group').modal('hide');
                        Swal.fire(data.message, '', 'success');
                        // alert(data.message);
                    } else {
                        Swal.fire(`{{__('app.something-wrong')}}`, '', 'error');
                        // alert('something went wrong.');
                    }
                    $('.preloader').hide();

                }
            });
        }
    }

    function responsRequest(id, _this) {
        var type = $(_this).attr('data-type');
        $.ajax({
            type: "get",
            url: "{{ route('user.response-request') }}",
            data: {
                type: type,
                id: id
            },
            success: function(result) {
                result = JSON.parse(result);
                $(_this).parents('li').remove();
                Swal.fire(result.message, '', 'success');
                var count = $(".requset-list li").length;
                if (count == 0) {
                    $('.requset-list').html('<li><h3 class="text-center">{{__('app.no-data')}}</h3></li>');
                }

            }
        });
    }

    function sendMessage(attach=0) 
    {
        if ($('#send-msg-form').valid()) {
            var data = new FormData($('#message-section-fields')[0]);
            data.append('_token', "{{ csrf_token() }}");
            data.append('message', $('#chat-msg').val());
        
            if ($('#recorded-voice:visible').length) {
                data.append("voice_data", $('#recorded-voice:visible').attr('src'));
            }

            if(attach)
            {
                // data.append('attachment', $('#attach-file')[0].files[0]);
                data.append('attachment', 1);
            }

            $.ajax({
                type: "post",
                url: "{{ route('user.send-message') }}",
                data: data,
                processData: false,
                contentType: false,
                async: false,
                success: function(result) {
                    // var data = JSON.parse(result);
                    // $('#messages-list').prepend(data.html);
                    setTimeout(() => {
                        $('#messages-list').animate({scrollTop: $('#messages-list').prop("scrollHeight")}, 500);
                    }, 1000)
                    
                    $('#chat-msg').val('');
                    $('#chat-msg').focus();
                    $('ul li#no-conversation').remove();
                }
            });
        }
    }

    function openChatVoiceSections(_this) {
        var calssVar = '';

        var attrClass = _this.attr('data-attr-sec');

        if (attrClass == 'voice') {

            $('#send-sms-btn').css('display','none');
	        $('#send-voice-btn').css('display','block');

            $('#attach-section').css('display','none');
            _this.find('i').removeClass('fa fa-microphone').addClass('fa fa-commenting');
            calssVar = 'chat';
            _this.attr('data-attr-sec', 'chat')
        } else {
            $('#send-sms-btn').css('display','block');
	        $('#send-voice-btn').css('display','none');

            $('#attach-section').css('display','block');
            _this.find('i').removeClass('fa-commenting').addClass('fa fa-microphone');
            calssVar = 'voice';
            _this.attr('data-attr-sec', 'voice')
        }


        $('.' + calssVar + '-section').removeClass('enable');
        $('.' + calssVar + '-section').addClass('disable');

        $('.' + attrClass + '-section').addClass('enable');
        $('.' + attrClass + '-section').removeClass('disable');
    }

    function downloadAttach(_this)
    {
        var FILEURL = $(_this).attr('href');
        var request = new XMLHttpRequest();
        var _OBJECT_URL;

        var progCount = 1;

        request.addEventListener('readystatechange', function(e) {
            if(request.readyState == 2 && request.status == 200) {
                $(_this).css('display','none');
                $('#attach-file').css('display','none');
            }
            else if(request.readyState == 3) {
                $(_this).next('.download-progress-container').css('display','block');
                $('#attach-file').css('display','none');
            }
            else if(request.readyState == 4) {
                _OBJECT_URL = URL.createObjectURL(request.response);

                const a = document.createElement('a')
                a.href = _OBJECT_URL
                a.download = _OBJECT_URL.split('/').pop()
                document.body.appendChild(a)
                a.click()
                document.body.removeChild(a)

                $(_this).next('.download-progress-container').css('display','none');
                $(_this).next('.download-progress-container').find('.download-progress').css('width','0%');
                $('#attach-file').css('display','block');
                window.URL.revokeObjectURL(_OBJECT_URL);
                $(_this).css('display','inline-block');
            }
        });

        request.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                var percent_complete = (e.loaded / e.total)*100;
            }
            else
            {
                var percent_complete = progCount = progCount + 0.2;
            }
            if(percent_complete <= 100)
            {
                $(_this).next('.download-progress-container').find('.download-progress').css('width',percent_complete + '%');
            }
        });

        request.responseType = 'blob';
        request.open('get', FILEURL);
        request.send();
    }

    function deleteGroup()
    {
        var groupID = $('#group-id').val();

        if(groupID)
        {
            $.ajax({
                type: "post",
                url: "{{ route('user.delete-group') }}",
                data: {groupID:groupID},
                success: function(result) {
                    var data = JSON.parse(result);

                    if(data.status)
                    {
                        $('#group_'+groupID).remove();
                        Swal.fire("Done!", data.message, 'success');
                        $('#add-contact-group').modal('hide');
                    }
                    else
                    {
                        Swal.fire("Oop!", data.message, 'error');
                    }
                }
            });
        }
    }

    function openActionsSection(_this,e)
    {
        $(_this).next('ul').removeClass('dropdown-menu-actions');
        $('.dropdown-menu-actions').css('display','none');

        if($(_this).next('ul:visible').length != 0)
        {
            $(_this).next('ul').css('display','none');
        }
        else
        {
            isDropDownCLicked = true;
            $(_this).next('ul').css('display','block');
        }
        $(_this).next('ul').addClass('dropdown-menu-actions');
    }

    function deleteConversation(actionID,type)
    {
        if(actionID)
        {
            Swal.fire({
                title: '{{ __('app.want-to-delete') }}',
                showCancelButton: true,
                cancelButtonText: '{{ __('app.cancle') }}',
                confirmButtonText: '{{ __('app.yes') }}',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('.preloader').fadeIn(500, function() {
                        $('.preloader').css('display','flex');
                    });

                    $.ajax({
                        type: "post",
                        url: "{{ route('user.delete-conversation') }}",
                        data: {actionID:actionID,type:type},
                        success: function(result) {

                            var data = JSON.parse(result);

                            $('#'+type+'_'+actionID).trigger('click');
                            if(data.status)
                            {
                                Swal.fire(AlertMessage.done, data.message, 'success');
                            }
                            else
                            {
                                Swal.fire("Oop!", data.message, 'info');
                            }

                            $('.preloader').fadeIn(500, function() {
                                $('.preloader').css('display','none');
                            });
                        }
                    });
                }
            })
        }
    }

    function timeChange()
    {
        $('p.noticed-time').each(function(i, obj) 
        {
            var time = $(this).attr('data-time');
            time = moment(time).fromNow()
            $(this).find('.time-sec').text(time)
        });
    }

</script>
