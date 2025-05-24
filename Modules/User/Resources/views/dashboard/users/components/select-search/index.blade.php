{!! field('search')->select('user_id', __('apps::dashboard.datatable.form.search_by_user_id'), [], null)!!}
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#user_id').select2({
                width: "off",
                ajax: {
                    url: "{{route('dashboard.users.select.search')}}",
                    data: function (params) {
                        var query = {
                            search: {'value': params.term},
                        };

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data, page) {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

        });
    </script>
@endpush