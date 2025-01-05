<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    let receipt = '';

    function shopProduct(_this)
    {
        let productId = $(_this).attr('data-product-id');

        // before request send
        $('.loading_product').css('display', 'block');
        $('.product_information').html('');

        $.ajax({
            type: "POST",
            url: "{{route('product.shop')}}",
            data: {
                '_token': "{{csrf_token()}}",
                'id': productId,
            },
            success: function(result) {
                if(result.status == 200)
                {
                    $('.loading_product').css('display', 'none');
                    $('.product_information').html(result.html);
                }
            }
        });
    }

    function changeQuantity(value)
    {
        var val = parseInt($('#quantity-input').val());
        if(value)
        {
            $('#quantity-input').val(val+1);
            let productActualPrice = $('.product_actual_price').val();
            $('.product_total_price_val').val(productActualPrice * (val+1))
        }
        else
        {
            if(val != 1)
            {
                let productActualPrice = $('.product_actual_price').val();
                $('.product_total_price_val').val(productActualPrice * (val-1))
                $('#quantity-input').val(val-1);
            }
        }
    }

    // fill receipt input dynamically
    $(document).on('change', '#receiptFile', function() {
        receipt = this.files[0];
    })

    // place order
    $(document).on('click', '.order_product', function() {
        if(document.getElementById("receiptFile").files.length == 0)
        {
            swal.fire('please upload receipt','', "error");
            // alert('please upload receipt');
            return;
        }

        // disble the buuton while ajax request
        $('.order_product').prop('disabled', true);

        // collect form data
        let formdata = new FormData();
        formdata.append('_token', "<?php echo e(csrf_token()); ?>");
        formdata.append('price', $('.product_actual_price').val());
        formdata.append('product_id', $('.product_id').val());
        formdata.append('quantity', $('.product_quantity').val());
        formdata.append('total', $('.product_total_price_val').val());
        formdata.append('receipt', receipt);
        $.ajax({
            type: "POST",
            url: "{{route('order.product')}}",
            processData: false,
            contentType: false,
            data: formdata,
            success: function(result) {
                if(result.status == 200)
                {
                    $('.order_product').prop('disabled', false);
                    $('#shopNowModal .close').click();
                    swal.fire(`{{__('app.order_successfully_placed')}}`,``, "success")
                    // alert('order successfully placed!')
                }
            }
        });
    });

</script>
