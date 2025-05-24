<div class="tab-pane fade" id="shipping_orders">

    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#local_shiping">
                {{ __('setting::dashboard.settings.form.local') }}
            </a>
        </li>
        {{-- <li>
            <a data-toggle="tab" href="#aramex">Aramex</a>
        </li> --}}
    </ul>
    <div class="tab-content">
        <div id="local_shiping" class="tab-pane fade in active">
            <div class="row">            
                <div class="form-group">
                    <label class="col-md-2">
                        {{ __('setting::dashboard.settings.form.supported_countries') }}
                    </label>
                    <div class="col-md-9">
                        <select name="shiping[local][countries][]" class="form-control select2" multiple="">
                            @foreach ($countries as $code => $country)
                                <option value="{{ $code }}"
                                        @if (collect(Setting::get('shiping.local.countries'))->contains($code))
                                        selected=""
                                    @endif>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div id="aramex" class="tab-pane fade">
            <div class="row">
                <div class="col-md-6 col-md-offset-4">
                    <div class="form-group">

                        <div class="col-md-9">
                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline">
                                    Integration Data
                                    <input onchange="paymentModeSwitcher('aramexInputs','IntegrationData')" type="radio" name="aramexInputs" checked>
                                    <span></span>
                                </label>
                                <label class="mt-radio mt-radio-outline">
                                    Source Address
                                    <input type="radio" onchange="paymentModeSwitcher('aramexInputs','SourceAddress')" name="aramexInputs">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="row aramexInputs" id="IntegrationData">            
                <div class="form-group">
                    <label class="col-md-2">
                        {{ __('setting::dashboard.settings.form.supported_countries') }}
                    </label>
                    <div class="col-md-9">
                        <select name="shiping[aramex][countries][]" class="form-control select2" multiple="">
                            @foreach ($countries as $code => $country)
                                <option value="{{ $code }}"
                                        @if (collect(Setting::get('shiping.aramex.countries'))->contains($code))
                                        selected=""
                                    @endif>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>            
                <div class="form-group">
                    <label class="col-md-2">
                        Country Code
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="env[ARAMEX_COUNTRY_CODE]"
                            value="{{env('ARAMEX_COUNTRY_CODE')}}"/>
                    </div>
                </div>            
                <div class="form-group">
                    <label class="col-md-2">
                        entity
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="env[ARAMEX_ENTITY]"
                            value="{{env('ARAMEX_ENTITY')}}"/>
                    </div>
                </div>            
                <div class="form-group">
                    <label class="col-md-2">
                        number
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="env[ARAMEX_NUMBER]"
                            value="{{env('ARAMEX_NUMBER')}}"/>
                    </div>
                </div>            
                <div class="form-group">
                    <label class="col-md-2">
                        pin
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="env[ARAMEX_PIN]"
                            value="{{env('ARAMEX_PIN')}}"/>
                    </div>
                </div>            
                <div class="form-group">
                    <label class="col-md-2">
                        username
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="env[ARAMEX_USERNAME]"
                            value="{{env('ARAMEX_USERNAME')}}"/>
                    </div>
                </div>            
                <div class="form-group">
                    <label class="col-md-2">
                        password
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="env[ARAMEX_PASSWORD]"
                            value="{{env('ARAMEX_PASSWORD')}}"/>
                    </div>
                </div>
            </div>

            <div class="row aramexInputs" id="SourceAddress" style="display:none">            
                <div class="form-group">
                    <label class="col-md-2">
                        {{__('user::frontend.addresses.form.select_country')}}
                    </label>
                    <div class="col-md-9">
                        
                        <div class="address_selector row" style="width: 100%;margin: 0px">
                            <div class="col-md-6 col-12">
                                <div class="form-group margin-bottom-20">
                                    <select class="select-detail select2 form-control country_selector" name="shiping[aramex][source][country_id]" id="aramex_country_selector"
                                            data-select2-id="{{Setting::get('shiping.aramex.source.country_id')}}" tabindex="-1"
                                            aria-hidden="true" onchange="getCitiesByCountryId(this)">
                                        <option>
                                            {{__('user::frontend.addresses.form.select_country')}}
                                        </option>
                                        @foreach($countries as $id => $country_title)
                                            <option value="{{$id}}"
                                                    {{Setting::get('shiping.aramex.source.country_id') && Setting::get('shiping.aramex.source.country_id') == $id ? 'selected' : ''}}>
                                                {{$country_title}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="state_container">
                                    <div class="state_selector_content_loader" style="display: none">
                                        {{__('apps::frontend.Loading')}}
                                    </div>
                                    <div class="state_selector_content">
                                        @if(!empty($selected_state) && !empty($selected_country) && $selected_state && $selected_country)
                                            @inject('cities','Modules\Area\Entities\City')
                                            @php $cities = $cities->active()->with(['states'])->where('country_id',$selected_country)->get(); @endphp

                                            <div class="form-group margin-bottom-20">
                                                <select class="select-detail select2 form-control area_selector"  name="shiping[aramex][source][state_id]"
                                                        data-select2-id="{{$selected_state}}" tabindex="-1"
                                                        aria-hidden="true">
                                                    <option>
                                                        {{__('user::frontend.addresses.form.states')}}
                                                    </option>
                                                    @foreach($cities as $city)
                                                        <optgroup label="{{$city->title}}">
                                                            @foreach($city->states as $state)
                                                                <option value="{{$state->id}}"
                                                                        {{$selected_state == $state->id ? 'selected' : ''}}>
                                                                    {{$state->title}}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            {!! field('frontend_no_label')->select('shiping[aramex][source][state_id]',
                                            __('user::frontend.addresses.form.states'),[] , [],[
                                                'class' => 'select-detail select2 form-control area_selector',
                                            ]) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  

                {!! field()->text('shiping[aramex][source][street]',__('user::frontend.addresses.form.street'),Setting::get('shiping.aramex.source.street')) !!}
            
                {!! field()->text('shiping[aramex][source][building]',__('user::frontend.addresses.form.building'),Setting::get('shiping.aramex.source.building')) !!}
            
                {!! field()->text('shiping[aramex][source][address]',__('user::frontend.addresses.form.address_details'),Setting::get('shiping.aramex.source.address')) !!}   
            </div>
        </div> --}}
    </div>
</div>
