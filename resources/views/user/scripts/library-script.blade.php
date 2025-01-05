<script>

$(function() {
        var type = $('.lib-tab-headers.active').attr('data-val');
        var tabClass = $('.lib-tab-headers.active').attr('data-cl');
        getLibrarySections(type,tabClass);
    });

    function getLibrarySections(type='',tabClass='')
    {
        $('.lib-tab-headers').removeClass('active');
        $('.lib-tab-headers.'+tabClass).addClass('active');

        $.ajax({
            type: 'get',
            url: '{{ URL("library-tabs") }}',
            data: { type: type, platform:'dashboard' },
            dataType: 'JSON',
            success: function (data) {
                $('#lib-tab-content').html(data.html);
                if($(".image-galary").length == 8){
                    $("#load-lib").css('display','block')
                }else{
                    $("#load-lib").css('display','none')
                    

                }
            }
        });
    }
    
    function getImageModel(_this) 
    {

        if(_this.attr('data-val') == 'img'){
            // alert('img');
            var last_id=$(_this).parent('.image-galary').attr('data-last-id');
            var data_type=$(_this).parent('.image-galary').attr('data-type');
            
            $('.move-lib').css('display','block');
            $(".desc-model-div").show()
            $("#next").attr('last_id',last_id);
            $("#next").attr('type_id',data_type);
            $("#prev").attr('last_id',last_id);
            $("#prev").attr('type_id',data_type);
            var img = $(_this).prev().prev('img').attr('src');
            var desc = $(_this).prev('p').text();
            $("#lib-modal-pic").attr('src',img)
            $("#lib-modal-desc").text(desc)
            $('#libImageModal').modal('show');
            $("#img-model-div").css('display','block ') 
            $('#video_1').hide();
            $('#audio_1').hide();
        }
        if(_this.attr('data-val') == 'video'){
            // alert("ok");
            var desc = $(_this).prev('p').text();
            $("#lib-modal-desc").text(desc)
            $('#audio_1').hide();
            $('#video_1').show();
             $('#img-model-div').attr("style", "display: none !important");     
            $("#video_tag").attr('src',_this.attr('data-src'))
            $("#video_tag").css('display','block')
            $('#libImageModal').modal('show');
        }
        if(_this.attr('data-val') == 'audio'){
            var desc = $(_this).prev('p').text();
            $("#lib-modal-desc").text(desc)
            $('#audio_1').show();
            $('#video_1').hide();
            $('#img-model-div').attr("style", "display: none !important");     
            $("#audio_tag").attr('src',_this.attr('data-src'))
            $('#libImageModal').modal('show');
        }
    }
    function viewMore(limit){
        var lastId=$('.image-galary:last').attr('data-last-id');
        var libType=$('.image-galary:last').attr('data-type');
        $.ajax({
            type: 'get',
            url: '{{ URL("user/library-load") }}',
            data: { type: libType,lastId:lastId,limit:limit },
            success: function (data) {
                if( data.length > 0){
                    // $('.image-galary:last').parent().after(data);
                    $('.image-galary:last').parent().parent().after(data);

                }else{
                    $("#load-lib").css('display',"none");
                }
            }
        });
    }


</script>