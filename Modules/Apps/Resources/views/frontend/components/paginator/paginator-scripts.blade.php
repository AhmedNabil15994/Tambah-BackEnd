
<script>

    var global_data = '';

    $(document).ready(function () {
        getPagination(1);
    });
    function getPagination(page , url = null ,  item = null,data = '') {

        item ? item.remove() : null;

        url = url ?? '{{$defaultRoute}}';
        data = data == '' ? global_data : data;
        var pageVue = page === 1 ? '' : '?page=' + page;

        $.ajax({
            url: url + pageVue,
            type: "get",
            data  : {
                req : data
            },
            beforeSend: function () {
                $('#load-more-content').remove();
                $('#ajax-load').show();
            },
            success: function (data) {
                $("#records_container").append(data.html);
                refreshSliders();
                paginationDataLoaded(data,'success');
                $('#ajax-load').hide();
            },
            error: function (data) {
                paginationDataLoaded(data,'error');
                $('#ajax-load').hide();
                var message = `
                        <div class="alert alert-danger col-lg-12 text-center" role="alert" style="margin-top: 10rem">
                          `+data.responseJSON.message+`
                        </div>`;
                $("#records_container").text('').append(message);

            },
        });
    }

    $(window).scroll(function () {
        var $loadMore = $('#load-more');
        if ($loadMore.length && $(window).scrollTop() + $(window).height() >= $loadMore.offset().top + $loadMore.height()) {
            var $pageNumber = $loadMore.attr('page-number');
            var $route = $loadMore.attr('page-route');
            getPagination($pageNumber, $route,$loadMore);
        }
    });

    function paginationDataLoaded(data,type) {

    }
</script>
