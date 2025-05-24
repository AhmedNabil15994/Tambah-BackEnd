<script>
    

    function formatRepoSelection(repo) {
        return repo.name || repo.text;
    }

    function formatRepo(repo) {
        if (repo.loading) return repo.text;

        var markup = repo.response;

        return markup;
    }
    $(document).ready(function () {
        $('#user_id').select2({
            width: "off",
            ajax: {
                url: "{{route('dashboard.users.select.search')}}",
                data: function (params) {
                    var query = {
                        search: {'value': params.term},
                    };
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