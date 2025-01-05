<script>
    $(function() {
        getProducts('', 'id DESC','', 0);

        $("input.search-prod-input").on("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $("input[name='product_ids[]']").remove();
                var searchStr = $(this).val();
                $('#selected_search_filter').val(searchStr);
                var catId = $('#selected_category_filter').val();
                var sortId = $('#selected_sort_filter').val();
                var product_type = $('#type_filter').val();
                getProducts(catId, sortId,product_type);
            }

        });

        $("span.search-now").on("click", function() {
            $("input[name='product_ids[]']").remove();
            var searchStr = $("input.search-prod-input").val();
            $('#selected_search_filter').val(searchStr);
            var catId = $('#selected_category_filter').val();
            var sortId = $('#selected_sort_filter').val();
            var product_type = $('#type_filter').val();
            getProducts(catId, sortId,product_type);
        });

        $("select.category-filter").on("change", function() {
            $("input[name='product_ids[]']").remove();
            var catId = $(this).val();
            $('#selected_category_filter').val(catId);
            var sortId = $('#selected_sort_filter').val();
            var product_type = $('#type_filter').val();
            getProducts(catId, sortId,product_type);
        });

        $("select.sorting-filter").on("change", function() {
            $("input[name='product_ids[]']").remove();
            var sortId = $(this).val();
            $('#selected_sort_filter').val(sortId);
            var catId = $('#selected_category_filter').val();
            var product_type = $('#type_filter').val();
            getProducts(catId, sortId,product_type, 0);
        });
        $("select.type_filter").on("change", function() {
            $("input[name='product_ids[]']").remove();
            var product_type = $(this).val();
            $('#type_filter').val(product_type);
            var sortId = $('#selected_sort_filter').val();
            var catId = $('#selected_category_filter').val();
            getProducts(catId, sortId,product_type);
        });
    });

    function getProducts(catId,sortId,product_type,loadMore=0)
    {
        var productIds = $("input[name='product_ids[]']")
              .map(function(){return $(this).val();}).get();

        if(!productIds.length)
        {
            productIds.push('');
        }
        var searchFilter = $('#selected_search_filter').val();
        $.ajax({
            type: "get",
            url: "{{route('guest.store')}}",
            data: {'category': catId,sortId:sortId,productIds:productIds,product_type: product_type,searchFilter:searchFilter},
            success: function(result)
            {
                result = JSON.parse(result);
                if(loadMore)
                {
                    $('#store-section').append(result.html);
                }
                else
                {
                    $('#store-section').html(result.html);
                }

                if(result.loadMore < 9)
                {
                    $('#load-more-sec').css('visibility','hidden');
                }
                else
                {
                    $('#load-more-sec').css('visibility','visible');
                }
            }
        });
    }

    function loadMore()
    {
        var catId = $('#selected_category_filter').val();
        var sortId = $('#selected_sort_filter').val();
        var product_type = $('#type_filter').val();
        getProducts(catId,sortId,1);
    }

    function openOrderModal(prodId,_this)
    {
        $("#quanity-input").val('1');
        var desciption=$(_this).attr('data-desc');
        var img = $('#product-img-'+prodId).attr('src');
        var name = $('#product-name-'+prodId).text();
        var price = $('#product-price-'+prodId).text();
        $('#add-cart-img-box').attr('src',img);
        $('#prod-mod-name').text(name);
        $('#prod-mod-price').text(price);
        $('#prod-mod-price-base').text(price);
        $("#desc").html(desciption);
        $('#product_id').val(prodId);
        // $("#add-cart-session").attr('onclick',(prodId))
        var product_type=parseInt($("#product_type_"+prodId).val());

        $('#order-model').modal('show');
        if(product_type == 1){
            // $('.quantity, .buttons_added').hide();
            $('.minus').hide()
            $('.plus').hide()
        }else{
            $('.minus').show()
            $('.plus').show()

        }
    }

    function changeQunaity(isIncrease)
    {
        var val = parseInt($('#quanity-input').val());
        if(isIncrease)
        {
            $('#quanity-input').val(val+1);
        }
        else
        {
            if(val != 1)
            {
                $('#quanity-input').val(val-1);
            }
        }

        var price = $('#prod-mod-price-base').text().replace(/[^0-9]/gi, '');
        var price = parseInt(price, 10);

        $('#prod-mod-price').text('RS:'+price*$('#quanity-input').val());
    }

    $(document).ready(function(){
        $("#quanity-input").keyup(function(){
            var price = $('#prod-mod-price-base').text().replace(/[^0-9]/gi, '');
            var price = parseInt(price, 10);
            $('#prod-mod-price').text('RS:'+price*$('#quanity-input').val());
        });
    });

    function orderNow(_this)
    {
        // alert('ho');
        if($('#order-form').valid())
        {
            var formData = new FormData($('#order-form')[0]);
            formData.append('_token',"{{csrf_token()}}");
            $.ajax({
                type: "POST",
                url: "{{route('order-now')}}",
                data:formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function(result)
                {
                    result = JSON.parse(result);
                    if(result.status)
                    {
                        swal.fire(AlertMessage.done, AlertMessage.order_place , "success");
                    }
                    $('#order-model').modal('hide');
                    $('.preloader').hide();

                }
            });
        }
    }


 function addCartSession(product_id){
     var formData = new FormData($('#order-form')[0]);
     formData.append('_token',"{{csrf_token()}}");
    if($('#auth').val() == 1){
        var urll="{{route('user.add-cart')}}";
    }else{

        var urll = "{{url('add-cart-guest')}}";
    }
    $.ajax({
        type: "POST",
        url: urll,
        data:formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('.preloader').show();
        },
        success: function(result)
        {
            // alert($('#auth').val());
            if($('#auth').val() == 1){
                window.location.href = "{{url('user/mustafai-store')}}";
                return false;
            }
            if(result.status == 200){
                swal.fire(AlertMessage.done, AlertMessage.addCart, 'success');
                $('.cart-count').css('visibility','visible');
                $("#cart-count-home").html(result.data);
            }
            $('#order-model').modal('hide');
            $('#quanity-input').val(1);
            $('.preloader').hide();

        }
    });

 }

function getHomeCart()
{
    let cart_count= $("#cart-count-home").html();
        if(cart_count <=0){
            swal.fire(AlertMessage.oops, AlertMessage.empty_cart , "warning");
            return false;
        }
    $.ajax({
        type: "get",
        url: "{{url('/get-cart-home')}}",
        success: function(result)
        {
            // result = JSON.parse(result);
            $('#home-cart-partial').html(result);
            $('#home-cart-items').modal('show');
        }
    });
}

function checkAddress(_this){
        if(_this.checked) {
            $('.ship-address').css('display','none');
            return;
        }
        $('.ship-address').css('display','block')
    }
    function getShipmentCharges(){
        // alert("ok");
        var totalPrice = 0;
        var count = 0;
        var totalQuantity=0
        $(".all-check-product").each(function(){
            if($(this).is(":checked")){
                totalPrice = parseInt(totalPrice)+ parseInt($(this).attr('data-total-price'));
                if($(this).attr('data-attr')==1){

                    count = parseInt(count+1);
                }
                totalQuantity = totalQuantity+ parseInt($(this).attr('data-quantity'));
            }

        })
        if(count > 0){
            var gradTotal = parseInt(totalPrice) + parseInt($("#shipment_rate").val());
            var shipment_charges = parseInt($("#shipment_rate").val());
        }else{
            var gradTotal =totalPrice;
            var shipment_charges = 0;
        }
        if (gradTotal > 0) {
            $('#home-payment-section').css('display', 'block');
        } else {
            $('#home-payment-section').css('display', 'none');
        }
        $(".cart_count_td").html(totalQuantity);
        $(".total_td").html(totalPrice);
        $(".shipment_td").html(shipment_charges);
        $(".grand_total_td").html(gradTotal);
        $(".cart_count_td").val(totalQuantity);
        $(".total_td").val(totalPrice);
        $(".shipment_td").val(shipment_charges);
        $(".grand_total_td").val(gradTotal);

    }

    function removeFromCart(cartId,_this)
    {
        // var count=$("#cart-count-home").html();
        $.ajax({
            type: "get",
            url: "{{url('home.remove-cart')}}",
            data:{cartId:cartId},
            success: function(result)
            {
                // result = JSON.parse(result);
                if(result.status == 'deleted')
                {
                    _this.parents('tr').remove();
                    // var update_count=count-1;
                    $( "#cart-count-home" ).html( result.data.cart_count);
                    if(result.data.cart_count==0){
                        $('#cart-count-home').css('visibility','hidden');
                    }
                    $('.all-check-product').prop('checked',false);
                    $(".cart_count_td").html('0');
                    $(".total_td").html('0');
                    $(".shipment_td").html('0');
                    $(".grand_total_td").html('0');

                    $(".cart_count_td").val(0);
                    $(".total_td").val(0);
                    $(".shipment_td").val(0);
                    $(".grand_total_td").val(0);
                }
            }
        });
    }
    function orderNowHome(_this)
    {
        $('#order-reciept-form-home').validate({
            rules: {
                    billing_phone_number: {
                        phoneNumber: true
                    },
                    shipping_phone_number: {
                        phoneNumber: true
                    },

                },
        });

        var cartIds = $("input[name='cart_ids[]']:checked")
              .map(function(){return $(this).val();}).get();

        if(!cartIds.length)
        {
            swal.fire(AlertMessage.oops, `{{__('app.select-atleast-one-item-to-proceed-further')}}` , "info");
        }
        else if(!$('#order-reciept-form-home').valid())
        {
            swal.fire(AlertMessage.oops, `{{__('app.fill-all-required')}}` , "info");
        }
        else
        {
            var formData = new FormData($('#order-reciept-form-home')[0]);
            formData.append('cartIds',cartIds);
            formData.append('_token',"{{csrf_token()}}");
            $.ajax({
                type: "POST",
                url: "{{url('order-now')}}",
                data:formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function(result)
                {
                    result = JSON.parse(result);
                    if(result.status)
                    {
                        swal.fire(AlertMessage.done, AlertMessage.order_place , "success");
                    }
                    $('#home-cart-items').modal('hide');
                    $('#cart-count-home').text(result.ccount);
                    $('.preloader').hide();
                    cartIds.filter(id=>{
                        removeFromCart(id,_this);
                    })


                }
            });
        }
    }
    if($("#cart-count-home").html()!=0){
        $('#cart-count-home').css('visibility','visible');
    }
    else{
        $('#cart-count-home').css('visibility','hidden');
    }
</script>
