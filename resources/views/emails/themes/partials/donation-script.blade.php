<script>
    $(function() {
        getDonations('', 0);

        $("select.category-filter").on("change", function() {
            $("input[name='donation_ids[]']").remove();
            var catId = $(this).val();
            $('#selected_category_filter').val(catId);
            getDonations(catId);
        });
    });

    function getDonations(catId,loadMore = 0) {
        var donationIds = $("input[name='donation_ids[]']")
            .map(function() {
                return $(this).val();
            }).get();

        if (!donationIds.length) {
            donationIds.push('');
        }
        $.ajax({
            type: "get",
            url: "{{ route('user.donate') }}",
            data: {
                'category': catId,
                donationIds: donationIds,
            },
            success: function(result) {
                //  result = JSON.parse(result);
                // alert(result);
                if (loadMore) {
                    $('#donation-section').append(result.html);
                } else {
                    $('#donation-section').html(result.html);
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
        getDonations(catId, 1);
    }
</script>
