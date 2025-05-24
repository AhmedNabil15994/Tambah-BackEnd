<div class="address_selector row" style="width: 100%;margin: 0px">
    <div class="col-md-6 col-12">
        <div class="form-group margin-bottom-20">
            <select class="select-detail select2 form-control country_selector" name="country_id"
                    {{!empty($selected_country) && $selected_country ? 'data-select2-id="'.$selected_country.'"' : ''}} tabindex="-1"
                    aria-hidden="true" onchange="getCitiesByCountryId(this)">
                <option>
                    {{__('user::frontend.addresses.form.select_country')}}
                </option>
                @foreach($countries as $id => $country_title)
                    <option value="{{$id}}"
                            {{!empty($selected_country) && $selected_country && $selected_country == $id ? 'selected' : ''}}>
                        {{$country_title}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="state_container">
            <div class="state_selector_content_loader" style="display: none">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status"
                         style="width: 2rem; height: 2rem;">
                        <span class="sr-only">{{__('apps::frontend.Loading')}}</span>
                    </div>
                </div>
            </div>
            <div class="state_selector_content">
                @if(!empty($selected_state) && !empty($selected_country) && $selected_state && $selected_country)
                    @inject('cities','Modules\Area\Entities\City')
                    @php $cities = $cities->active()->with(['states'])->where('country_id',$selected_country)->get(); @endphp

                    <div class="form-group margin-bottom-20">
                        <select class="select-detail select2 form-control area_selector" name="attributes[{{$attribute->id}}]"
                                data-select2-id="{{$selected_state}}" tabindex="-1" data-name="attributes.{{$attribute->id}}"
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
                        <span class="help-block" style=""></span>
                    </div>
                @else
                    {!! field('frontend_no_label')->select("attributes[{$attribute->id}]",
                      __('user::frontend.addresses.form.states'),[] , [],[
                          'class' => 'select-detail select2 form-control area_selector',
                          'data-name' => 'attributes.'.$attribute->id
                    ]) !!}
                @endif
            </div>
        </div>
    </div>
</div>