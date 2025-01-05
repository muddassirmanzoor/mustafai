<script>
    $(function() {
        getMagazines();

        $("select.category-filter").on("change", function() {
            $("input[name='magazine_ids[]']").remove();
            var catId = $(this).val();
            $('#selected_category_filter').val(catId);
            var sortBy = $('#selected_sort_filter').val();
            var searchStr= $('#selected_search_filter').val();

            getMagazines(catId,sortBy,searchStr);
        });

        $("select.sort-filter").on("change", function() {
            $("input[name='magazine_ids[]']").remove();
            var catId = $('#selected_category_filter').val();
            var searchStr= $('#selected_search_filter').val();
            var sortBy = $(this).val();
            $('#selected_sort_filter').val(sortBy);
            getMagazines(catId,sortBy,searchStr);
        });


        $("input.search-input-magzine").on("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                console.log('data');
                var searchStr = $(this).val();
                console.log(searchStr);
               $("input[name='magazine_ids[]']").remove();
                $('#selected_search_filter').val(searchStr);
                var catId = $('#selected_category_filter').val();
                var sortBy = $('#selected_sort_filter').val();
                getMagazines(catId,sortBy,searchStr);
            }

        });
    });

    function getMagazines(catId='',sortBy='',searchStr='',loadMore = 0) {
        var magazineIds = $("input[name='magazine_ids[]']")
            .map(function() {
                return $(this).val();
            }).get();

        if (!magazineIds.length) {
            magazineIds.push('');
        }
        // var sortBy= $('#sortBy').val();
        $.ajax({
            type: "get",
            url: "{{ route('magazines') }}",
            data: {
                'category': catId,
                magazineIds: magazineIds,
                sortBy: sortBy,
                searchStr:searchStr
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
        var sortBy = $('#selected_sort_filter').val();
        var searchStr= $('#selected_search_filter').val();
        getMagazines(catId,sortBy,searchStr,1);
    }
</script>
