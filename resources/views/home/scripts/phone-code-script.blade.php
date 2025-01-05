{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}

<script type="text/javascript">

// $('.id_select2_header').select2();

function custom_template(obj)
{
    var data = $(obj.element).data();
    var text = $(obj.element).text();
    if(data && data['img_src']){
        img_src = data['img_src'];
        template = $("<div class='country-code-wrap'><img src=\"" + img_src + "\" style=\"width:29%;height:24px;object-fit: contain;\"/><p style=\"font-weight: 500;margin-left: 8px;font-size:9pt;text-align:center;\">" + text + "</p></div>");
        return template;
    }
}

var options = {
    'templateSelection': custom_template,
    'templateResult': custom_template,
}

$('.id_select2_example').select2(options);
$('.profile-setting').select2(options);

$('.id_select2_header').select2({
    'templateSelection': custom_template,
    'templateResult': custom_template,
    'dropdownParent': $("#offcanvasRight"),

    
});

$('.select2-container--default .select2-selection--single').css({'border': '1px solid #ccd4da !important;'});
$(".select2-container--default .select2-selection--single").addClass('select__user')
// $("#select22").next(".select2-container").addClass('select__user')

</script>
