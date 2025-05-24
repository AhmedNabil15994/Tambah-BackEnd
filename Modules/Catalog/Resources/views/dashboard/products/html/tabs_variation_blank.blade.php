

<div class="portlet-body variation-container  variation-add " style="margin-top: 20px">
    <div class="table-container">
        <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">

            <tbody>
            @forelse ($res as $key2 => $value2)

                @if (is_array($value2))
                    <tr>
                        <td colspan="6" style="padding: 15px;">
                            @foreach ($value2 as $optionValue)
                                <input type="hidden" name="option_values_id[{{ $key2 }}][]" value="{{ $optionValue }}">
                                <span>
                                    {{ $optionValues->findOptionValueById($optionValue)->option->title }} :
                                </span>
                                <b>
                                    @if($optionValues->findOptionValueById($optionValue)->type == 'text')
                                        {{$optionValues->findOptionValueById($optionValue)->title}}
                                    @else
                                        <label style="
                                        padding: 7px 11px;
                                        border-radius:3px;
                                        border: 1px solid;
                                        margin-bottom:0px;
                                        background: {{$optionValues->findOptionValueById($optionValue)->color}}"></label>
                                        {{$optionValues->findOptionValueById($optionValue)->color}}
                                    @endif
                                </b>
                                @if (!$loop->last)
                                    /
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr role="row" class="filter " data-key="{{$key2}}">
                        <td>
                            <label>
                                {{__('catalog::dashboard.products.form.price')}}
                            </label>
                            <input type="text" placeholder="Price" data-name="variation_price.{{$key2}}"
                                   class="form-control price-variation form-filter input-sm" name="variation_price[{{$key2}}]">
                            <div class="help-block"></div>
                        </td>
                        <td>
                            <label>
                                {{__('catalog::dashboard.products.form.qty')}}
                            </label>
                            <input type="text" placeholder="Qty" data-name="variation_qty.{{$key2}}"
                                   class="form-control qty-variation form-filter input-sm" name="variation_qty[{{$key2}}]">
                            <div class="help-block"></div>
                        </td>
                        <td style="padding: 10px 31px 0px 31px;">
                            {!! field('variant')->checkBox('variation_status[]', __('catalog::dashboard.products.form.status'), null ,[ 'checked' => '','class' => 'make-switch status-variation']) !!}
                        </td>
                        <td>
                            <label>
                                {{__('catalog::dashboard.products.form.sku')}}
                            </label>
                            <input type="text"
                                   placeholder="SKU"
                                   data-name="variation_sku.{{$key2}}"
                                   class="form-control form-filter input-sm"
                                   value="{{ generateRandomCode() }}"
                                   name="variation_sku[{{$key2}}]">
                            <div class="help-block"></div>
                        </td>
                        <td>
                            <div class="form-group" style="margin-left: -4px; float: left; margin-top: 23px;">

                                {{--<a data-input="v_images" data-preview="holder" class="btn btn-sm blue lfm ">
                                    <i class="fa fa-picture-o"></i>
                                </a>
                                <input name="v_images[{{$key2}}]" data-name="v_images.{{$key2}}"
                                       class="form-control form-filter input-sm v_images" type="text" readonly
                                       style="display:none;">
                                <span class="holder" style="margin-top:15px;max-height:100px;"></span>--}}

                                <input name="v_images[{{$key2}}]" data-name="v_images.{{$key2}}"
                                       class="form-control form-filter input-sm v_images" type="file"
                                       onchange="readURL(this, 'imgUploadPreview-{{ $key2.'-var-'.($key2+1) }}', 'single');">

                                <span class="holder" style="margin-top:15px;max-height:100px;">
                                    <img id="imgUploadPreview-{{ $key2.'-var-'.($key2+1) }}" src="#" alt=""
                                         class="img-thumbnail"
                                         style="display: none; width:100px; height: auto;">
                                </span>

                                <div class="help-block"></div>
                            </div>
                        </td>

                        <td rowspan="3">
                            <button type="button" class="btn btn-sm red btn-outline variants-delete">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>

                    <tr class="variation_options_{{$key2}}">
                        <td colspan="4">

                            <div class="form-group">

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.weight')}}"
                                           class="form-control  weight-variation" data-name="vshipment.{{$key2}}.weight"
                                           name="vshipment[{{$key2}}][weight]">
                                    <div class="help-block"></div>
                                </div>

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.width')}}"
                                           data-name="vshipment.{{$key2}}.width" class="form-control  width-variation"
                                           name="vshipment[{{$key2}}][width]">
                                    <div class="help-block"></div>
                                </div>

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.length')}}"
                                           data-name="vshipment.{{$key2}}.length" class="form-control  length-variation"
                                           name="vshipment[{{$key2}}][length]">
                                    <div class="help-block"></div>
                                </div>

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.height')}}"
                                           class="form-control  height-variation" data-name="vshipment.{{$key2}}.height"
                                           name="vshipment[{{$key2}}][height]">
                                    <div class="help-block"></div>
                                </div>

                            </div>
                        </td>
                    </tr>

                    <tr class="variation_options_{{$key2}}">
                        <td colspan="4">

                            <div class="form-group">
                                <label class="col-md-2">
                                    {{__('catalog::dashboard.products.form.offer_status')}}
                                </label>
                                <div class="col-md-9 text-left">
                                    <input type="checkbox" class="offer-status offer-status-variation" data-index="{{$key2}}"
                                           name="v_offers[{{ $key2 }}][status]" data-name="v_offers.{{ $key2 }}.status">
                                    <div class="help-block"></div>
                                </div>
                            </div>


                            <div class="offer-form_{{$key2}} variation_offer" style="display:none;">

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text"
                                                       placeholder="{{__('catalog::dashboard.products.form.offer_price')}}"
                                                       id="offer-form_v" data-name="v_offers.{{ $key2 }}.offer_price"
                                                       class="form-control offer-price-variation" name="v_offers[{{ $key2 }}][offer_price]"
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
                                                    <input type="text" id="offer-form_v" class="form-control  start-at-variation"
                                                           name="v_offers[{{ $key2 }}][start_at]"
                                                           data-name="v_offers.{{ $key2 }}.start_at" disabled>
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
                                                    <input type="text" id="offer-form_v" class="form-control end-at-variation"
                                                           name="v_offers[{{ $key2 }}][end_at]" disabled
                                                           data-name="v_offers.{{ $key2 }}.end_at">
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

                @else
                    <td colspan="6" style="padding: 15px;">

                        <input type="hidden" name="option_values_id[{{ $key2 }}][]" value="{{ $value2 }}">
                        <span>
                                    {{ $optionValues->findOptionValueById($value2)->option->title }}
                                     : </span>
                        <b>
                            @if($optionValues->findOptionValueById($value2)->type == 'text')
                                {{$optionValues->findOptionValueById($value2)->title}}
                            @else
                                <label style="
                                        padding: 7px 11px;
                                        border-radius:3px;
                                        border: 1px solid;
                                        margin-bottom: 0px;
                                        background: {{$optionValues->findOptionValueById($value2)->color}}"></label>

                                {{$optionValues->findOptionValueById($value2)->color}}
                            @endif
                        </b>

                    </td>
                    <tr role="row" class="filter " data-key="{{$key2}}">
                        <td>
                            <div class="form-group" style="margin: 0;">
                                <label>
                                    {{__('catalog::dashboard.products.form.price')}}
                                </label>
                                <input type="text" placeholder="Price" data-name="variation_price.{{$key2}}"
                                       class="form-control price-variation form-filter input-sm" name="variation_price[{{$key2}}]">
                                <div class="help-block"></div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group" style="margin: 0">
                                <label>
                                    {{__('catalog::dashboard.products.form.qty')}}
                                </label>
                                <input type="text" placeholder="Qty" data-name="variation_qty.{{$key2}}"
                                       class="form-control qty-variation form-filter input-sm" name="variation_qty[{{$key2}}]">
                                <div class="help-block"></div>
                            </div>
                        </td>
                        <td style="padding: 10px 31px 0px 31px;">
                            {!! field('variant')->checkBox('variation_status[]', __('catalog::dashboard.products.form.status'), null ,[ 'checked' => '','class' => 'make-switch status-variation']) !!}
                        </td>
                        <td>
                            <div class="form-group" style="margin: 0">
                                <label>
                                    {{__('catalog::dashboard.products.form.sku')}}
                                </label>
                                <input type="text"
                                       placeholder="SKU"
                                       data-name="variation_sku.{{$key2}}"
                                       value="{{ generateRandomCode() }}"
                                       class="form-control form-filter input-sm"
                                       name="variation_sku[{{$key2}}]">
                                <div class="help-block"></div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group" style="margin-left: -4px; float: left;">

                                {{--<a data-input="v_images" data-preview="holder" class="btn btn-sm blue lfm ">
                                    <i class="fa fa-picture-o"></i>
                                </a>
                                <input name="v_images[{{$key2}}]" data-name="v_images.{{$key2}}"
                                       class="form-control form-filter input-sm v_images" type="text" readonly
                                       style="display:none;">
                                <span class="holder" style="margin-top:15px;max-height:100px;"></span>--}}

                                <input name="v_images[{{$key2}}]" data-name="v_images.{{$key2}}"
                                       class="form-control form-filter input-sm v_images" type="file"
                                       onchange="readURL(this, 'imgUploadPreview-{{ $key2.'-var-'.($key2+1) }}', 'single');">

                                <span class="holder" style="margin-top:15px;max-height:100px;">
                                    <img id="imgUploadPreview-{{ $key2.'-var-'.($key2+1) }}" src="#" alt=""
                                         class="img-thumbnail"
                                         style="display: none; width:100px; height: auto;">
                                </span>

                                <div class="help-block"></div>
                            </div>
                        </td>

                        <td rowspan="3">
                            <button type="button" class="btn btn-sm red btn-outline variants-delete">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>

                    <tr class="variation_options_{{$key2}}">
                        <td colspan="4">

                            <div class="form-group">

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.weight')}}"
                                           class="form-control weight-variation" data-name="vshipment.{{$key2}}.weight"
                                           name="vshipment[{{$key2}}][weight]">
                                    <div class="help-block"></div>
                                </div>

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.width')}}"
                                           data-name="vshipment.{{$key2}}.width" class="form-control width-variation"
                                           name="vshipment[{{$key2}}][width]">
                                    <div class="help-block"></div>
                                </div>

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.length')}}"
                                           data-name="vshipment.{{$key2}}.length" class="form-control length-variation"
                                           name="vshipment[{{$key2}}][length]">
                                    <div class="help-block"></div>
                                </div>

                                <div class="col-md-3 text-left">
                                    <input type="text" placeholder="{{__('catalog::dashboard.products.form.height')}}"
                                           class="form-control height-variation" data-name="vshipment.{{$key2}}.height"
                                           name="vshipment[{{$key2}}][height]">
                                    <div class="help-block"></div>
                                </div>

                            </div>
                        </td>
                    </tr>

                    <tr class="variation_options_{{$key2}}">
                        <td colspan="4">

                            <div class="form-group">
                                <label class="col-md-2">
                                    {{__('catalog::dashboard.products.form.offer_status')}}
                                </label>
                                <div class="col-md-9 text-left">
                                    <input type="checkbox" class="offer-status offer-status-variation" data-index="{{$key2}}"
                                           name="v_offers[{{ $key2 }}][status]" data-name="v_offers.{{ $key2 }}.status">
                                    <div class="help-block"></div>
                                </div>
                            </div>


                            <div class="offer-form_{{$key2}} variation_offer" style="display:none;">

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text"
                                                       placeholder="{{__('catalog::dashboard.products.form.offer_price')}}"
                                                       id="offer-form_v" data-name="v_offers.{{ $key2 }}.offer_price"
                                                       class="form-control offer-price-variation" name="v_offers[{{ $key2 }}][offer_price]"
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
                                                    <input type="text" id="offer-form_v" class="form-control start-at-variation"
                                                           name="v_offers[{{ $key2 }}][start_at]"
                                                           data-name="v_offers.{{ $key2 }}.start_at" disabled>
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
                                                    <input type="text" id="offer-form_v" class="form-control end-at-variation"
                                                           name="v_offers[{{ $key2 }}][end_at]" disabled
                                                           data-name="v_offers.{{ $key2 }}.end_at">
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

                @endif

            @empty
                <td>
                <td colspan="7">
                    <div class="alert alert-info" role="alert">
                        {{__('catalog::dashboard.products.form.empty_options')}}
                    </div>
                </td>
                </td>
            @endforelse
            </tbody>
        </table>
    </div>
</div>


<script>
    $('.lfm').filemanager('image');

    $('.variants-delete').click(function () {
        var delterow = $(this).closest('.filter');
        $(`.variation_options_${delterow.data('key')}`).remove();
        delterow.prev("tr").remove();
        delterow.remove();
    });


</script>
