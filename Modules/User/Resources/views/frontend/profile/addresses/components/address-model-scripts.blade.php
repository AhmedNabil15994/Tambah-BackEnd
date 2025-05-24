<script>

    function openAddressModal(id){

        $('.select-detail, .select2').select2({

            dropdownParent: $('#'+id)
        });

        $('#'+id).modal('show');
    }

    function getStatesByCityId(state_id_selector, city_id_selector) {

        state_id_selector = $('#' + state_id_selector);
        state_id_selector.empty();

        var container = state_id_selector.closest('.state_container');
        container.find('.state_selector_content').hide();
        container.find('.state_selector_content_loader').show();
        var id = $('#' + city_id_selector).val();

        $.ajax({
            method: "GET",
            url: '{{route('frontend.area.get_child_area_by_parent')}}?type=state&parent_id=' + id,
            success: function (data) {
                console.log(data);
                var option = '';
                $.each(data.data, function (index, state) {
                    option = '<option value="' + state.id + '">' + state.title + '</option>';
                    state_id_selector.append(option);
                });
                container.find('.state_selector_content').show();
                container.find('.state_selector_content_loader').hide();
            }
        });
    }

    function getCitiesByCountryId(country) {

        country = $(country);
        var container = country.closest('.address_selector');
        var area_selector = container.find('.area_selector');
        var id = country.val();

        $.ajax({
            method: "GET",
            url: '{{route('frontend.area.get_child_area_by_parent')}}?type=city&parent_id=' + id,
            beforeSend: function () {
                area_selector.empty();
                container.find('.state_selector_content').hide();
                container.find('.state_selector_content_loader').show();
            },
            success: function (data) {
                {{-- area_selector.append('<option selected>{{__('user::frontend.addresses.form.states')}}</option>') --}};
                area_selector.append('<option selected value=""></option>');
                var optgroup = '';
                $.each(data.data, function (index, city) {
                    var options = '';
                    $.each(city.states, function (index, state) {
                        options += '<option value="' + state.id + '">' + state.title + '</option>';
                    });

                    optgroup = '<optgroup label="'+city.title+'">'+options+'</optgroup>';
                    area_selector.append(optgroup);
                });
                container.find('.state_selector_content').show();
                container.find('.state_selector_content_loader').hide();
                var form = container.closest("form");
                var block_container = form.find(".block_container");
                if(data.country === 'KW'){
                    block_container.show();
                }else{

                    block_container.hide();
                }
            }
        });
    }
</script>