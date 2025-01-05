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
        $("select.type_filter").on("change", function() {
            $("input[name='product_ids[]']").remove();
            var product_type = $(this).val();
            $('#type_filter').val(product_type);
            var sortId = $('#selected_sort_filter').val();
            var catId = $('#selected_category_filter').val();
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
    });

    function getProducts(catId, sortId,product_type, loadMore = 0) {
        var productIds = $("input[name='product_ids[]']")
            .map(function() {
                return $(this).val();
            }).get();

        if (!productIds.length) {
            productIds.push('');
        }
        var searchFilter = $('#selected_search_filter').val();
        $.ajax({
            type: "get",
            url: "{{ route('user.store') }}",
            data: {
                'category': catId,
                sortId: sortId,
                product_type: product_type,
                productIds: productIds,
                searchFilter: searchFilter
            },
            success: function(result) {
                result = JSON.parse(result);
                if (loadMore) {
                    $('#store-section').append(result.html);
                } else {
                    $('#store-section').html(result.html);
                }

                if (result.loadMore < 9) {
                    $('#load-more-sec').css('visibility', 'hidden');
                } else {
                    $('#load-more-sec').css('visibility', 'visible');
                }
            }
        });
    }

    function loadMore() {
        var catId = $('#selected_category_filter').val();
        var sortId = $('#selected_sort_filter').val();
        var product_type = $('#type_filter').val();
        getProducts(catId, sortId,product_type, 1);
    }

    function addCartModal(_this, prodId) {
        $("#quanity-input").val('1');
        var desciption = $(_this).attr('data-desc');
        var desciption_less = $(_this).attr('data-less-desc');
        var img = $(_this).parents('.wrap-item-info').prev().find('img').attr('src');
        var price = $(_this).parent().prev('.product-price-info').text();
        var name = $(_this).parent().parent().prev('.product-name-info').text();
        $('#add-cart-img-box').attr('src', img);
        $('#product_id').val(prodId);
        $('#prod-name-mod').text(name);
        $('#prod-price-mod').text(price);
        $('#prod-price-base-mod').text(price);
        $("#desc").html(desciption);
        $('#add-to-cart').modal('show');
        var product_type=parseInt($("#product_type_"+prodId).val());
        if(product_type == 1){
            $('.minus').hide()
            $('.plus').hide()
        }else{
            $('.minus').show()
            $('.plus').show()
        }

    }

    function addToCart() {
        var quantity = $('#quanity-input').val();
        var product_id = $('#product_id').val();

        var formData = new FormData($('#cart-form')[0]);
        $.ajax({
            type: "get",
            url: "{{ route('user.add-cart') }}",
            data: {
                quantity: quantity,
                product_id: product_id
            },
            beforeSend: function(msg) {
                $('.preloader').show();

            },
            success: function(result) {
                result = JSON.parse(result);
                $('.cart-count').css('visibility', 'visible');
                $('.cart-count').text(result.ccount);

                if (result.status) {
                    $('#quanity-input').val(1);
                    $('#product_id').val('');
                    $('#add-to-cart').modal('hide');
                    swal(AlertMessage.done, result.message, "success");
                } else {
                    swal(AlertMessage.info, result.message, "info");
                }
                $('.preloader').hide();

            }
        });
    }

    function getCart(_this) {
        let cart_count = $("#cart-count").html();
        if (cart_count <= 0) {
            swal(AlertMessage.oops, AlertMessage.empty_cart, "warning");
            return false;
        }
        $.ajax({
            type: "get",
            url: "{{ route('user.get-cart') }}",
            success: function(result) {
                result = JSON.parse(result);
                $('#cart-items-detail').html(result.html);
                $('#cart-deatails').modal('show');
            }
        });
    }

    function changeQunaity(isIncrease) {
        var val = parseInt($('#quanity-input').val());
        if (isIncrease) {
            $('#quanity-input').val(val + 1);
        } else {
            if (val != 1) {
                $('#quanity-input').val(val - 1);
            }
        }

        var price = $('#prod-price-base-mod').text().replace(/[^0-9]/gi, '');
        var price = parseInt(price, 10);

        $('#prod-price-mod').text('RS:' + price * $('#quanity-input').val());
    }

    $(document).ready(function(){
        $("#quanity-input").keyup(function(){
            var price = $('#prod-price-base-mod').text().replace(/[^0-9]/gi, '');
            var price = parseInt(price, 10);

            $('#prod-price-mod').text('RS:' + price * $('#quanity-input').val());
        });
    });

    function removeFromCart(cartId, _this) {
        var count = $("#cart-count").html();
        $.ajax({
            type: "get",
            url: "{{ route('user.remove-cart') }}",
            data: {
                cartId: cartId
            },
            success: function(result) {
                result = JSON.parse(result);
                if (result.status == 'deleted') {
                    _this.parents('tr').remove();
                    var update_count = count - 1;
                    $("#cart-count").html(update_count);
                    if (update_count == 0) {
                        $('.cart-count').css('visibility', 'hidden');
                    }
                    $('.all-check-product').prop('checked', false);
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

    function orderNow(_this) {
        $('#order-reciept-form').validate({
            rules: {
                billing_phone_number: {
                    phoneNumber: true
                },

            },
        });
        var cartIds = $("input[name='cart_ids[]']:checked")
            .map(function() {
                return $(this).val();
            }).get();

        if (!cartIds.length) {
            swal(AlertMessage.oops, `{{ __('app.select-atleast-one-item-to-proceed-further') }}`, "info");
        }
        else if (!$('#order-reciept-form').valid()) {
            swal(AlertMessage.oops, `{{ __('app.fill-all-required') }}`, "info");
        }
         else {
            var formData = new FormData($('#order-reciept-form')[0]);
            formData.append('cartIds', cartIds);
            formData.append('_token', "{{ csrf_token() }}");
            $.ajax({
                type: "POST",
                url: "{{ route('user.order-now') }}",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.preloader').show();
                },
                success: function(result) {
                    result = JSON.parse(result);
                    if (result.status) {
                        swal(AlertMessage.done, AlertMessage.order_place, "success");
                    }
                    $('#cart-deatails').modal('hide');
                    $('.cart-count').text(result.ccount);
                    if (result.ccount == 0) {
                        $('#cart-count').css('visibility', 'hidden');
                    }
                    $('.preloader').hide();

                }
            });
        }
    }

    if ($('#queryString').val() !== '') {
        $('.search-prod-input').val($('#queryString').val());
        setTimeout(() => {
            $('.search-now').click()
        }, 1000)
    }
    if ($("#cart-count").html() != 0) {
        $('.cart-count').css('visibility', 'visible');
    }
</script>
