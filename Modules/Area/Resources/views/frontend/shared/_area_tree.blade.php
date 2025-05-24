<div class="row">
    <div class="col-md-12 col-12">

        <div class="form-group">
            <select class="select-detail" name="country_id" id="addressCountryId">
                <option value="">
                    --- {{ __('user::frontend.addresses.form.select_country') }} ---
                </option>
                @if(isset($countries) && count($countries) > 0)
                    @foreach ($countries as $country)
                        <option
                            value="{{$country->id}}"
                            {{ old('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div id="countryCitiesSection" style="display: none">
            <div class="form-group">
                <select class="select-detail" name="city_id"
                        id="addressCityId">
                    <option value="">
                        --- {{ __('user::frontend.addresses.form.select_city') }} ---
                    </option>
                </select>
            </div>
        </div>

        <div id="countryCityStatesSection" style="display: none">
            <div class="form-group">
                <select class="select-detail" name="state"
                        id="addressStateId">
                    <option value="">
                        --- {{ __('user::frontend.addresses.form.select_state') }} ---
                    </option>
                </select>
            </div>
        </div>

    </div>
</div>
