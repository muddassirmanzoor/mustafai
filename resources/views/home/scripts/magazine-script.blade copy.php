<script>
    $(function() {
        getMagazines('', 0);

        $("select.category-filter").on("change", function() {
            $("input[name='magazine_ids[]']").remove();
            var catId = $(this).val();
            // $('#selected_category_filter').val(catId);
            getMagazines(catId);
        });

        $("select.sort-filter").on("change", function() {
            $("input[name='magazine_ids[]']").remove();
            var catId = $('#selected_category_filter').val();
            var sortBy = $(this).val();
            $('#selected_sort_filter').val(sortBy);
            getMagazines(catId,sortBy,0);
        });
    });

    function getMagazines(catId,loadMore = 0) {
        var magazineIds = $("input[name='magazine_ids[]']")
            .map(function() {
                return $(this).val();
            }).get();

        if (!magazineIds.length) {
            magazineIds.push('');
        }
        var sortBy= $('#sortBy').val();
        $.ajax({
            type: "get",
            url: "{{ route('magazines') }}",
            data: {
                'category': catId,
                magazineIds: magazineIds,
                sortBy: sortBy,
            },
            success: function(result) {
                result = JSON.parse(result);
                if (loadMore) {
                    $('#magazine-section').append(result.html);
                } else {
                    $('#magazine-section').html(result.html);
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
        getMagazines(catId, 1);
    }
</script>
