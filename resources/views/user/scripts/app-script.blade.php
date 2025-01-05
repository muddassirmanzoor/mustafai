<script>

    var isTimeout;
    var isLoaded;

    function success() {
        if (isTimeout) {
        return;
        }
        $('#loading').hide();
        $('iframe').show();
        isLoaded = true;
    };

    setTimeout(function() {
        if (isLoaded) {
        return;
        }
        $('#loading').hide();
        $('iframe').hide();
        $('#error').show();
        isTimeout = true;
    }, 5000);
    $(function() {
        var route = "{{ \Illuminate\Support\Facades\Route::currentRouteName() }}";
        myInterval = setInterval(getNotificationsCounter, 1000);
    });

    function getNotificationsCounter() {
        var url = $(location).attr('href').split("/").splice(0, 5).join("/");
        var segments = url.split( '/' );
        var urlAction = segments[4];

        $.ajax({
            type: "get",
            url: "{{ route('user.get-notofications-counter') }}",
            data: {urlAction:urlAction},
            async: true,
            success: function(result) {
                let response = JSON.parse(result);
                // update each contact unread message coutner

                if(urlAction == 'chats')
                {
                    for(let i = 0; i<response.contacts.length; i++) {
                        let count = response.contacts[i].unread_messages_count > 0 ? response.contacts[i].unread_messages_count : '';
                        let contactElement = $(`#contact_${response.contacts[i].id}`);
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

                    for(let i = 0; i<response.group_counts.length; i++) {
                        let count = response.group_counts[i].count > 0 ? response.group_counts[i].count : '';
                        let groupElement = $(`#group_${response.group_counts[i].id}`);
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
                }

                var counters = JSON.parse(result);
                if (counters.notification > 0) {
                    $('span.notifications-count').text((counters.notification > 99) ? '99+' : counters
                        .notification);
                    $('span.notifications-count').css('display', 'block');
                } else {
                    $('span.notifications-count').css('display', 'none');
                }
                if (counters.chat > 0) {
                    $('span.chat-count').text((counters.chat > 99) ? '99+' : counters.chat);
                    $('span.chat-count').css('display', 'block');
                } else {
                    $('span.chat-count').css('display', 'none');
                }
                if (counters.friend_request > 0) {
                    $('span.friend-request-count').text((counters.friend_request > 99) ? '99+' : counters
                        .friend_request);
                    $('span.friend-request-count').css('display', 'block');
                } else {
                    $('span.friend-request-count').css('display', 'none');
                }
            }
        });
    }

    //______________for Iframes Load____________//
    function openiframe(data) {
        $('.view-document-modal').remove()
        $('body').append('<div  class="modal fade common-model-style view-document-modal" id="view-model" tabindex="-1" role="dialog" aria-labelledby="updateProfileModal" aria-hidden="true"> <div class="modal-header d-none"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> </div> <div class="modal-dialog modal-dialog-centered modal-xl" role="document"> <div class="modal-content"> <div class="modal-body"></div></div></div></div>')
        $('#view-model').find('.modal-body').html('<iframe loading="eager" class="" sandbox="allow-same-origin allow-scripts allow-pointer-lock" id="iframe" width="100%" height="500px" src="https://docs.google.com/gview?url=' + data + '&embedded=true" frameborder="0"  ></iframe>')
        $('#view-model').modal('show');
    }

    $("#libImageModal").on('hide.bs.modal', function () {
        // alert("ok")
        $(this).find('iframe').attr('src', '')
        $(this).find('audio').attr('src', '')
    });
</script>
