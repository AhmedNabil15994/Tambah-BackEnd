<div class="portlet-body variation-container variation-edit">
    <div class="table-container">
        <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
            {{-- <tr role="row" class="heading">
                <th width="15%"> # </th>
                <th width="15%"> Price </th>
                <th width="200"> Qty </th>
                <th width="200"> Status </th>
                <th width="10%"> SKU </th>
                <th width="10%"> Image </th>
                <th width="10%"> Delete </th>
            </tr> --}}
            </thead>
            <tbody>
            <input type="hidden" name="removed_variants" value="">
            @foreach ($product->variants as $key => $data)
                <tr>
                    <td colspan="6" style="padding: 15px;">
                        @foreach ($data->productValues as $key2 => $value)
                            {{-- <input type="hidden" name="option_values_id[{{ $data['id'] }}][]" value="{{ $value->optional(id }}"> --}}
                            <span>{{optional(optional($value->optionValue)->option)->title}} : </span>
                            <b>
                                @if(optional($value->optionValue)->type == 'text')
                                    {{optional($value->optionValue)->title}}
                                @else
                                    <label style="
                                       padding: 7px 11px;
                                        border-radius:3px;
                                        border: 1px solid;
                                        margin-bottom:0px;
                                        background: {{optional($value->optionValue)->color}}"></label>
                                    {{optional($value->optionValue)->color}}
                                @endif
                            </b>
                            @if(!$loop->last)
                                /
                            @endif
                        @endforeach
                        <input type="hidden" name="upateds_option_values_id[{{ $data['id'] }}]" value="{{ $data->id }}">
                    </td>
                </tr>
                <tr role="row" class="filter" data-key="{{$data['id']}}">
                    {{-- <input type="hidden" name="variants_ids[]" value="{{ $data->id }}"> --}}
                    <input type="hidden" name="variants[_old][{{ $data['id'] }}]" value="{{ $product->id }}">

                    <td>
                        <label>
                            {{__('catalog::dashboard.products.form.price')}}
                        </label>
                        <input type="text" placeholder="Price" class="price-variation form-control form-filter input-sm"
                               name="_variation_price[{{ $data['id'] }}]" value="{{ $data->price }}">
                    </td>
                    <td>
                        <label>
                            {{__('catalog::dashboard.products.form.qty')}}
                        </label>
                        <input type="text" placeholder="Qty" class="qty-variation  form-control form-filter input-sm"
                               name="_variation_qty[{{ $data['id'] }}]" value="{{ $data->qty }}">
                    </td>
                    <td style="padding: 10px 31px 0px 31px;">
                        {!! field('variant')->checkBox('_variation_status['. $data['id'] .']', __('catalog::dashboard.products.form.status'), null ,[$data->status ? 'checked' : '' => '','class' => 'make-switch status-variation']) !!}
                    </td>
                    <td>
                        <label>
                            {{__('catalog::dashboard.products.form.sku')}}
                        </label>
                        <input type="text"
                               placeholder="SKU"
                               class="form-control form-filter input-sm"
                               name="_variation_sku[{{ $data['id'] }}]"
                               value="{{ $data->sku ?? generateRandomCode() }}">
                    </td>
                    <td rowspan="3">
                        {{--                        {!! field('dashboard-no-label')->file('_v_images['.$data['id'].']','',url($data->image)) !!}--}}
                        <div class="form-group" style="margin-left: -4px; float: left; margin-top: 23px;">
                            <input name="_v_images[{{ $data['id'] }}]"
                                   class="form-control form-filter input-sm _v_images" type="file"
                                   onchange="readURL(this, 'imgUploadPreview-{{ $data['id'] }}', 'single');">

                            <span class="holder" style="margin-top:15px;max-height:100px;">
                                <img id="imgUploadPreview-{{ $data['id'] }}" src="{{ url($data->image) }}" alt=""
                                     class="img-thumbnail"
                                     style="width:100px; height: auto;">
                            </span>

                        </div>
                    </td>
                    <td rowspan="3">
                        <button type="button" class="btn btn-sm red btn-outline variants-delete"
                                data-index="{{$loop->index}}">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>

                <tr class="variation_options_update_{{$data['id']}}">
                    <td colspan="4">

                        <div class="form-group">

                            <div class="col-md-3 text-left">
                                <input type="text" value="{{optional($data->shipment)['weight']}}"
                                       placeholder="{{__('catalog::dashboard.products.form.weight')}}"
                                       class="form-control weight-variation" data-name="_vshipment.{{$data['id']}}.weight"
                                       name="_vshipment[{{$data['id']}}][weight]">
                                <div class="help-block"></div>
                            </div>

                            <div class="col-md-3 text-left">
                                <input type="text" value="{{optional($data->shipment)['width']}}"
                                       placeholder="{{__('catalog::dashboard.products.form.width')}}"
                                       data-name="_vshipment.{{$data['id']}}.width" class="form-control width-variation"
                                       name="_vshipment[{{$data['id']}}][width]">
                                <div class="help-block"></div>
                            </div>

                            <div class="col-md-3 text-left">
                                <input type="text" value="{{optional($data->shipment)['length']}}"
                                       placeholder="{{__('catalog::dashboard.products.form.length')}}"
                                       data-name="_vshipment.{{$data['id']}}.length" class="form-control length-variation"
                                       name="_vshipment[{{$data['id']}}][length]">
                                <div class="help-block"></div>
                            </div>

                            <div class="col-md-3 text-left">
                                <input type="text" value="{{optional($data->shipment)['height']}}"
                                       placeholder="{{__('catalog::dashboard.products.form.height')}}"
                                       class="form-control height-variation" data-name="_vshipment.{{$data['id']}}.height"
                                       name="_vshipment[{{$data['id']}}][height]">
                                <div class="help-block"></div>
                            </div>

                        </div>
                    </td>
                </tr>

                <tr class="variation_options_update_{{$data['id']}}">
                    <td colspan="4">

                        <div class="form-group">
                            <label class="col-md-2">
                                {{__('catalog::dashboard.products.form.offer_status')}}
                            </label>
                            <div class="col-md-9 text-left">
                                <input type="checkbox"
                                       @if ($data->offer)
                                       {{($data->offer->status == 1) ? ' checked="" ' : ''}}
                                       @endif
                                       class="offer-status offer-status-variation" data-index="update{{$data['id']}}"
                                       name="_v_offers[{{ $data['id'] }}][status]"
                                       data-name="_v_offers.{{ $data['id'] }}.status">
                                <div class="help-block"></div>
                            </div>
                        </div>


                        <div class="offer-form_update{{$data['id']}} variation_offer" style="display:none;">

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text"
                                                   value="{{optional($data->offer)->offer_price}}"
                                                   placeholder="{{__('catalog::dashboard.products.form.offer_price')}}"
                                                   id="offer-form_v" data-name="_v_offers.{{$data['id'] }}.offer_price"
                                                   class="form-control offer-price-variation" name="_v_offers[{{ $data['id'] }}][offer_price]"
                                                   disabled>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-4">
                                            {{__('catalog::dashboard.products.form.start_at')}}
                                        </label>
                                        <div class="col-md-8">
                                            <div class="input-group input-medium date date-picker"
                                                 data-date-format="yyyy-mm-dd" data-date-start-date="+0d">
                                                <input type="text"
                                                       value="{{optional($data->offer)->start_at}}"
                                                       id="offer-form_v" class="form-control start-at-variation"
                                                       name="_v_offers[{{ $data['id'] }}][start_at]"
                                                       data-name="_v_offers.{{ $data['id'] }}.start_at" disabled>
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="help-block" style="color: #e73d4a"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">


                                    <div class="form-group">
                                        <label class="col-md-4">
                                            {{__('catalog::dashboard.products.form.end_at')}}
                                        </label>
                                        <div class="col-md-8">
                                            <div class="input-group input-medium date date-picker"
                                                 data-date-format="yyyy-mm-dd" data-date-start-date="+0d">
                                                <input type="text"
                                                       value="{{optional($data->offer)->end_at}}"
                                                       id="offer-form_v" class="form-control end-at-variation"
                                                       name="_v_offers[{{ $data['id'] }}][end_at]" disabled
                                                       data-name="_v_offers.{{ $data['id'] }}.end_at">
                                                <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                            </div>
                                            <div class="help-block" style="color: #e73d4a"></div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
</div>
