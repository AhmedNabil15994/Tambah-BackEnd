@extends('apps::dashboard.layouts.app')
@section('title', __('catalog::dashboard.products.routes.update'))

@section('css')
    <style>
        .btn-file-upload {
            position: relative;
            overflow: hidden;
        }

        .btn-file-upload input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        .img-preview {
            height: auto;
            width: 15%;
            /*width: 77%;*/
            /*height: 200px;*/
            /*display: none;*/
        }

        .upload-input-name {
            width: 75% !important;
        }

        .btnRemoveMore {
            margin: 0 5px;
        }

        .btnAddMore {
            margin: 7px 0;
        }

        .prd-image-section {
            margin-bottom: 10px;
        }

        .manageQty {
            width: 18px;
            height: 18px;
        }

        .gender-radio {
            width: 18px;
            height: 14px;
        }
    </style>
@endsection

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ url(route('dashboard.products.index')) }}">
                            {{ __('catalog::dashboard.products.routes.index') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('catalog::dashboard.products.routes.update') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.products.update', $product->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">

                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.general') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#stock" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.stock') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#categories" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.categories') }}
                                                    </a>
                                                </li>

                                                @if (config('setting.products.toggle_variations') == 1)
                                                    <li class="">
                                                        <a href="#variations" id="click-varaition" data-toggle="tab">
                                                            {{ __('catalog::dashboard.products.form.tabs.variations') }}
                                                        </a>
                                                    </li>
                                                @endif

                                                <li class="">
                                                    <a href="#images" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.images') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#tags" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.tags') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#ages" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.ages') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#search_keywords" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.search_keywords') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#related_products" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.related_products') }}
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#seo" data-toggle="tab">
                                                        {{ __('catalog::dashboard.products.form.tabs.seo') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PAGE CONTENT --}}
                        <div class="col-md-9">
                            <div class="tab-content">

                                {{-- UPDATE FORM --}}
                                <div class="tab-pane active fade in" id="global_setting">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.general')}}</h3>
                                    --}}

                                    <ul class="nav nav-tabs">
                                        @foreach (config('translatable.locales') as $code)
                                            <li class="@if ($loop->first) active @endif">
                                                <a data-toggle="tab"
                                                    href="#global_setting{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">

                                        @foreach (config('translatable.locales') as $code)
                                            <div id="global_setting{{ $code }}"
                                                class="tab-pane fade @if ($loop->first) in active @endif">

                                                <div class="col-md-10">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.title') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="title[{{ $code }}]"
                                                                class="form-control" data-name="title.{{ $code }}"
                                                                {{ auth()->user()->can('edit_products_title')? '': 'disabled' }}
                                                                value="{{ $product->getTranslation('title', $code) }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    {!! field()->ckEditor5(
                                                        'description[' . $code . ']',
                                                        __('page::dashboard.pages.form.description') . '-' . $code,
                                                        $product->getTranslation('description', $code),
                                                    ) !!}

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.delivery_time') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="delivery_time[{{ $code }}]"
                                                                class="form-control"
                                                                data-name="delivery_time.{{ $code }}"
                                                                value="{{ $product->getTranslation('delivery_time', $code) }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.store_location') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text"
                                                                name="store_location[{{ $code }}]"
                                                                class="form-control"
                                                                data-name="store_location.{{ $code }}"
                                                                value="{{ $product->getTranslation('store_location', $code) }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach

                                        <div class="col-md-10">

                                            @include('catalog::dashboard.products.components.barcodefield')

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.sku') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="sku" class="form-control"
                                                        data-name="sku"
                                                        value="{{ $product->sku ?? generateRandomCode() }}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.brand') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="brand_id" id="brand" class="form-control select2"
                                                        data-name="brand_id">
                                                        <option value=""></option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand['id'] }}"
                                                                {{ $brand['id'] == $product->brand_id ? 'selected' : '' }}>
                                                                {{ $brand->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.vendor') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="vendor_id" id="vendor_id" class="form-control select2"
                                                        data-name="vendor_id">
                                                        <option value=""></option>
                                                        @foreach ($activeVendors as $vendor)
                                                            <option value="{{ $vendor['id'] }}"
                                                                {{ $vendor['id'] == $product->vendor_id ? 'selected' : '' }}>
                                                                {{ $vendor->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">

                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.tabs.shipment') }}
                                                </label>

                                                <div class="col-md-3 text-left">
                                                    <label>{{ __('catalog::dashboard.products.form.weight') }}</label>
                                                    <input type="number"
                                                        value="{{ optional($product->shipment)['weight'] }}"
                                                        placeholder="{{ __('catalog::dashboard.products.form.weight') }}"
                                                        class="form-control" data-name="shipment.weight"
                                                        name="shipment[weight]">
                                                    <div class="help-block"></div>
                                                </div>

                                                <div class="col-md-2 text-left">
                                                    <label>{{ __('catalog::dashboard.products.form.width') }}</label>
                                                    <input type="number"
                                                        value="{{ optional($product->shipment)['width'] }}"
                                                        placeholder="{{ __('catalog::dashboard.products.form.width') }}"
                                                        data-name="shipment.width" class="form-control"
                                                        name="shipment[width]">
                                                    <div class="help-block"></div>
                                                </div>

                                                <div class="col-md-2 text-left">
                                                    <label>{{ __('catalog::dashboard.products.form.length') }}</label>
                                                    <input type="number"
                                                        value="{{ optional($product->shipment)['length'] }}"
                                                        placeholder="{{ __('catalog::dashboard.products.form.length') }}"
                                                        data-name="shipment.length" class="form-control"
                                                        name="shipment[length]">
                                                    <div class="help-block"></div>
                                                </div>

                                                <div class="col-md-2 text-left">
                                                    <label>{{ __('catalog::dashboard.products.form.height') }}</label>
                                                    <input type="number"
                                                        value="{{ optional($product->shipment)['height'] }}"
                                                        placeholder="{{ __('catalog::dashboard.products.form.height') }}"
                                                        class="form-control" data-name="shipment.height"
                                                        name="shipment[height]">
                                                    <div class="help-block"></div>
                                                </div>

                                            </div>
                                            {!! field()->checkBox(
                                                'all_countries',
                                                __('catalog::dashboard.products.form.all_countries'),
                                                $product->all_countries,
                                                [$product->all_countries == 1 ? 'checked' : '' => ''],
                                            ) !!}
                                            <div id="select_countries"
                                                style="display: {{ $product->all_countries == 1 ? 'none' : 'block' }}">
                                                {!! field()->multiSelect(
                                                    'countries',
                                                    __('catalog::dashboard.products.form.countries'),
                                                    $supported_countries->pluck('title', 'id')->toArray(),
                                                    $product->countries()->count() ? $product->countries->pluck('id')->toArray() : [],
                                                    ['data-placeholder' => __('catalog::dashboard.products.form.all_countries')],
                                                ) !!}
                                            </div>

                                            @if (auth()->user()->can('edit_products_image'))
                                                {!! field()->file('image', __('catalog::dashboard.products.form.image'), asset($product->image)) !!}
                                            @else
                                                <div class="form-group">
                                                    <label class="col-md-2">
                                                        {{ __('catalog::dashboard.products.form.image') }}
                                                    </label>
                                                    <div class="col-md-9">
                                                        <span class="holder" style="margin-top:15px;max-height:100px;">
                                                            <img src="{{ url($product->image) }}" alt=""
                                                                style="height: 15rem;">
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.sort') }}
                                                </label>
                                                <div class="col-md-3">
                                                    <input type="number" name="sort" class="form-control"
                                                        data-name="sort" value="{{ $product->sort }}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.status') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                        data-size="small" name="status"
                                                        {{ $product->status == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.allow_wrapping') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="allow_wrapping"
                                                        data-size="small" name="allow_wrapping"
                                                        {{ $product->allow_wrapping == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.show_product_website') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="show_product_website"
                                                        data-size="small" name="show_product_website"
                                                        {{ $product->show_product_website == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.show_product_pos') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="show_product_pos"
                                                        data-size="small" name="show_product_pos"
                                                        {{ $product->show_product_pos == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.is_new') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                        data-size="small" name="is_new"
                                                        {{ $product->is_new == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            {{--  @if (auth()->user()->hasRole('admins'))  --}}
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.featured') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" data-size="small"
                                                        name="featured"
                                                        {{ $product->featured == 1 ? ' checked="" ' : '' }}>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            {{--  @endif  --}}

                                            @if ($product->trashed())
                                                <div class="form-group">
                                                    <label class="col-md-2">
                                                        {{ __('catalog::dashboard.products.form.restore') }}
                                                    </label>
                                                    <div class="col-md-9">
                                                        <input type="checkbox" class="make-switch" id="test"
                                                            data-size="small" name="restore">
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.for_boys_girls') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="form-check">
                                                        <span style="margin: 10px;">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="gender-radio"
                                                                    name="for_boys_girls" value="both"
                                                                    {{ $product->for_boys_girls == 'both' ? ' checked="" ' : '' }}>
                                                                {{ __('catalog::dashboard.products.form.for_both') }}
                                                            </label>
                                                        </span>

                                                        <span style="margin: 10px;">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="for_boys_girls"
                                                                    class="gender-radio" value="boys"
                                                                    {{ $product->for_boys_girls == 'boys' ? ' checked="" ' : '' }}>
                                                                {{ __('catalog::dashboard.products.form.for_boys') }}
                                                            </label>
                                                        </span>

                                                        <span style="margin: 10px;">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="for_boys_girls"
                                                                    class="gender-radio" value="girls"
                                                                    {{ $product->for_boys_girls == 'girls' ? ' checked="" ' : '' }}>
                                                                {{ __('catalog::dashboard.products.form.for_girls') }}
                                                            </label>
                                                        </span>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.product_url') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="hidden" class="form-control" id="copyProductUrlInput"
                                                        value="{{ route('frontend.products.index', $product->slug) }}">
                                                    <a href="{{ route('frontend.products.index', $product->slug) }}"
                                                        target="_blank">{{ route('frontend.products.index', $product->slug) }}</a>
                                                    <button type="button" class="btn" onclick="copyToClipboard()">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="tab-pane fade in" id="categories">
                                    <h3 class="page-title">{{ __('catalog::dashboard.products.form.tabs.categories') }}
                                    </h3>
                                    <div id="jstree">
                                        @include('catalog::dashboard.tree.products.edit', [
                                            'mainCategories' => $mainCategories,
                                        ])
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="category_id" id="root_category" value=""
                                            data-name="category_id">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="stock">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.stock')}}</h3>
                                    --}}
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('catalog::dashboard.products.form.price') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="price" class="form-control"
                                                    data-name="price"
                                                    {{ auth()->user()->can('edit_products_price')? '': 'disabled' }}
                                                    value="{{ $product->price }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('catalog::dashboard.products.form.qty') }}
                                            </label>
                                            <div class="col-md-9">

                                                <div class="form-check">
                                                    <span style="margin: 10px;">
                                                        <input type="radio" class="manageQty" name="manage_qty"
                                                            value="unlimited" onchange="manageQty(this.value)"
                                                            {{ is_null($product->qty) ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            {{ __('catalog::dashboard.products.form.unlimited') }}
                                                        </label>
                                                    </span>

                                                    <span style="margin: 10px;">
                                                        <input type="radio" name="manage_qty" class="manageQty"
                                                            value="limited" onchange="manageQty(this.value)"
                                                            {{ !is_null($product->qty) ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            {{ __('catalog::dashboard.products.form.limited') }}
                                                        </label>

                                                        <input type="number" id="prdQty" name="qty"
                                                            class="form-control" data-name="qty"
                                                            {{ auth()->user()->can('edit_products_qty')? '': 'disabled' }}
                                                            style="display: {{ !is_null($product->qty) ? 'block' : 'none' }}"
                                                            value="{{ $product->qty }}">
                                                        <div class="help-block"></div>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                        <hr>

                                        <h3 class="page-title">{{ __('catalog::dashboard.products.form.offer') }}</h3>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('catalog::dashboard.products.form.offer_status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="isUnchecked" id="offer-status"
                                                    name="offer_status"
                                                    @if ($product->offer) {{ $product->offer->status == 1 ? ' checked="" ' : '' }} @endif
                                                    onclick="checkFunction()">
                                                <input type="hidden" class="isUnchecked" name="offer_status"
                                                    value="0"
                                                    @if ($product->offer) {{ $product->offer->status == 1 ? ' disabled ' : '' }} @endif>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>


                                        <div class="offer-form" style="display:none;">

                                            <div class="form-group">
                                                <label
                                                    class="col-md-2">{{ __('catalog::dashboard.products.form.offer_type.label') }}</label>
                                                <div class="col-md-9">
                                                    <div class="mt-radio-inline">
                                                        <label class="mt-radio">
                                                            <input type="radio" name="offer_type"
                                                                id="offerAmountRadioBtn" value="amount"
                                                                onclick="toggleOfferType('amount')"
                                                                {{ optional($product->offer)->offer_price ? 'checked' : '' }}>
                                                            {{ __('catalog::dashboard.products.form.offer_type.amount') }}
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="offer_type"
                                                                id="offerPercentageRadioBtn" value="percentage"
                                                                onclick="toggleOfferType('percentage')"
                                                                {{ optional($product->offer)->percentage ? 'checked' : '' }}>
                                                            {{ __('catalog::dashboard.products.form.offer_type.percentage') }}
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="offerAmountSection"
                                                style="display: {{ optional($product->offer)->offer_price ? 'block' : 'none' }}">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.offer_price') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" id="offer-form" name="offer_price"
                                                        class="form-control" data-name="offer_price"
                                                        {{ auth()->user()->can('edit_products_price')? '': 'disabled' }}
                                                        value="{{ $product->offer ? $product->offer->offer_price : '' }}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="offerPercentageSection"
                                                style="display: '{{ optional($product->offer)->percentage ? 'block' : 'none' }}'">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.percentage') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number" step="0.5" min="0"
                                                        id="offer-percentage-form" name="offer_percentage"
                                                        class="form-control"
                                                        {{ auth()->user()->can('edit_products_price')? '': 'disabled' }}
                                                        data-name="offer_percentage"
                                                        value="{{ $product->offer ? $product->offer->percentage : '' }}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.start_at') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-medium date date-picker"
                                                        data-date-format="yyyy-mm-dd" data-date-start-date="+0d">
                                                        <input type="text" id="offer-form" class="form-control"
                                                            name="start_at" data-name="start_at"
                                                            {{ auth()->user()->can('edit_products_price')? '': 'disabled' }}
                                                            value="{{ $product->offer ? $product->offer->start_at : '' }}">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.end_at') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-medium date date-picker"
                                                        data-date-format="yyyy-mm-dd" data-date-start-date="+0d">
                                                        <input type="text" id="offer-form" class="form-control"
                                                            name="end_at"
                                                            {{ auth()->user()->can('edit_products_price')? '': 'disabled' }}
                                                            data-name="end_at"
                                                            value="{{ $product->offer ? $product->offer->end_at : '' }}">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                @if (config('setting.products.toggle_variations') == 1)
                                    <div class="tab-pane fade in" id="variations">
                                        {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.variations')}}
                                        </h3> --}}

                                        {{-- option to add --}}
                                        <div class="row">

                                            @foreach ($options as $option)
                                                <div class="col-md-5" style="margin: 0 0 0 10px;">
                                                    <div class="form-group">
                                                        <label>{{ optional($option)->title }}</label>
                                                        @if (!$option->values()->where('type', 'color')->count())
                                                            <select name="option_values"
                                                                class="option_values form-control select2" multiple="">
                                                                <option value=""></option>
                                                                @foreach (optional($option)->values as $value)
                                                                    <option
                                                                        @if ($product->checkIfHaveOption(optional($value)->id)) selected @endif
                                                                        value="{{ optional($value)->id }}"
                                                                        data-name="option_values[{{ optional($option)->id }}]">
                                                                        {{ $value->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <div style="margin:10px">
                                                                @foreach (optional($option)->values as $value)
                                                                    <label class="mt-checkbox"
                                                                        style="margin-right: 5px;margin-left: 5px;">
                                                                        <input type="checkbox"
                                                                            name="option_values_checkbox"
                                                                            value="{{ optional($value)->id }}"
                                                                            class="option_values_checkbox"
                                                                            {{ $product->checkIfHaveOption(optional($value)->id) ? 'checked' : '' }}>
                                                                        <label
                                                                            style="
                                                                        padding: 7px 11px;
                                                                        border-radius:3px;
                                                                        border: 1px solid;
                                                                         background: {{ $value->color }}"></label>
                                                                        {{ $value->color }}
                                                                        <span>

                                                                        </span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            @endforeach

                                            {{-- <div class="col-md-10">
                                                        <div class="copy_variations_html">
                                                            <div class="content">
                                                                <div class="form-group">
                                                                    @foreach ($options as $option)
                                                                        <div class="col-md-4">
                                                                            <div class="mt-element-ribbon bg-grey-steel">
                                                                                <div
                                                                                    class="ribbon ribbon-border-hor ribbon-clip ribbon-color-danger uppercase">
                                                                                    <div
                                                                                        class="ribbon-sub ribbon-clip"></div>
                                                                                    {{ $option->title }}
                                        </div>

                                        <div class="ribbon-content" style="padding: 8px;">
                                            <div class="col-md-offset-2">
                                                <div class="mt-checkbox-list">
                                                    @foreach ($option->values as $value)
                                                    <label class="mt-checkbox mt-checkbox-outline">
                                                        <input type="checkbox" name="option_values"
                                                            @if ($product->checkIfHaveOption($value->id))
                                                        checked
                                                        @endif
                                                        value="{{$value->id}}"
                                                        data-name="option_values[{{ $option->id }}]"
                                                        />
                                                        {{$value->title}}
                                                        <span></span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-offset-4" style="margin-bottom: 14px;">
                                <button type="button" class="btn btn-lg green load_variations">
                                    <i class="fa fa-refresh"></i>
                                    {{__('catalog::dashboard.products.form.add_variations')}}
                                </button>
                            </div>
                        </div>
                </div>
            </div> --}}

                                        </div>

                                        <div class="row">
                                            <div class="col-md-offset-4" style="margin-bottom: 14px;">
                                                <button type="button" class="btn btn-lg green load_variations">
                                                    <i class="fa fa-refresh"></i>
                                                    {{ __('catalog::dashboard.products.form.add_variations') }}
                                                </button>
                                            </div>
                                        </div>

                                        <div id="copy-vairations">
                                            <hr>

                                            <div class="copy-options" style="display: flex">

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label> {{ __('catalog::dashboard.products.form.price') }}</label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="price-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.qty') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="qty-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.status') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="status-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.weight') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="weight-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.width') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="width-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.length') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="length-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.height') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="height-variation" />
                                                </div>


                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.offer_status') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="offer-status-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.offer_price') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="offer-price-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.start_at') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="start-at-variation" />
                                                </div>

                                                <div class="copy-option-elment"
                                                    style="justify-content: space-between ;width:10%">
                                                    <label>
                                                        {{ __('catalog::dashboard.products.form.end_at') }}
                                                    </label>
                                                    <input class="copy-option" type="checkbox" name="copy_options"
                                                        value="end-at-variation" />
                                                </div>


                                            </div>



                                            <div class="copy-controller text-center" style="margin: 10px 0">
                                                <button class="btn btn-primary" id="copy-variation-button">
                                                    {{ __('catalog::dashboard.products.form.copy_from_first') }}</button>

                                                <button class="btn btn-success" id="select-variation-all">Toggle
                                                    Select</button>
                                            </div>
                                        </div>
                                        <hr>
                                        @include('catalog::dashboard.products.html.tabs_variations')

                                        <div style="display: none" id="result-add-option">
                                            <h2 class="text-center">{{ __('catalog::dashboard.products.form.new_add') }}
                                            </h2>
                                            <div class="html_option_values"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="tab-pane fade in" id="images">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.images')}}</h3> --}}
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            {{-- <label>Upload Image</label> --}}

                                            @if (auth()->user()->can('edit_products_gallery'))
                                                <button type="button" onclick="addMoreImages()"
                                                    class="btn btn-success btnAddMore">
                                                    {{ __('catalog::dashboard.products.form.btn_add_more') }} <i
                                                        class="fa fa-plus-circle"></i>
                                                </button>
                                            @endif

                                            <div id="product-images">

                                                @if (count($product->images) > 0)
                                                    @foreach ($product->images as $k => $img)
                                                        <div id="prd-image-{{ $k }}"
                                                            class="prd-image-section">

                                                            @if (auth()->user()->can('edit_products_gallery'))
                                                                <div class="input-group">
                                                                    <span class="input-group-btn">
                                                                        <span class="btn btn-default btn-file-upload">
                                                                            {{ __('catalog::dashboard.products.form.browse_image') }}
                                                                            <input type="file"
                                                                                name="images[{{ $img->id }}]"
                                                                                onchange="readURL(this, {{ $k }});">
                                                                        </span>
                                                                    </span>
                                                                    <input type="text"
                                                                        id="uploadInputName-{{ $k }}"
                                                                        class="form-control upload-input-name" readonly>
                                                                    <button type="button"
                                                                        class="btn btn-danger btnRemoveMore"
                                                                        onclick="removeMoreImage('{{ $k }}', '{{ $img->id }}', 'db')">
                                                                        X
                                                                    </button>
                                                                </div>
                                                            @endif

                                                            <img id='img-upload-preview-{{ $k }}'
                                                                class="img-preview img-thumbnail"
                                                                src="{{ url('uploads/products/' . $img->image) }}"
                                                                alt="image preview" />
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div id="prd-image-0" class="prd-image-section">

                                                        @if (auth()->user()->can('edit_products_gallery'))
                                                            <div class="input-group">
                                                                <span class="input-group-btn">
                                                                    <span class="btn btn-default btn-file-upload">
                                                                        {{ __('catalog::dashboard.products.form.browse_image') }}
                                                                        <input type="file" name="images[]"
                                                                            onchange="readURL(this, 0);">
                                                                    </span>
                                                                </span>
                                                                <input type="text" id="uploadInputName-0"
                                                                    class="form-control upload-input-name" readonly>
                                                                <button type="button"
                                                                    class="btn btn-danger btnRemoveMore"
                                                                    onclick="removeMoreImage(0, 0, 'row')">X
                                                                </button>
                                                            </div>
                                                        @endif

                                                        <img id='img-upload-preview-0' class="img-preview img-thumbnail"
                                                            alt="image preview" style="display: none;" />
                                                    </div>
                                                @endif

                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="tags">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.tags')}}</h3> --}}
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <select name="tags[]" class="form-control select2" multiple="">
                                                <option value=""></option>
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag['id'] }}"
                                                        {{ in_array($tag->id, $product->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $tag->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="ages">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.ages')}}</h3> --}}
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <select name="ages[]" class="form-control select2" multiple="">
                                                <option value=""></option>
                                                @foreach ($ages as $age)
                                                    <option value="{{ $age['id'] }}"
                                                        {{ in_array($age->id, $product->ages->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $age->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="search_keywords">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.search_keywords')}}</h3> --}}
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <select name="search_keywords[]" class="form-control searchKeywordsSelect"
                                                multiple="">
                                                <option value=""></option>
                                                @foreach ($searchKeywords as $keyword)
                                                    <option value="{{ $keyword['id'] }}"
                                                        {{ in_array($keyword->id, $product->searchKeywords->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $keyword->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="related_products">
                                    {{-- <h3 class="page-title">{{ __('catalog::dashboard.products.form.related_products') }}</h3> --}}
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <select id="relatedProductsSelect" name="related_products[]"
                                                class="form-control select2" multiple="">
                                                @foreach ($sharedActiveProducts as $prd)
                                                    <option value="{{ $prd['id'] }}"
                                                        {{ in_array($prd['id'], array_map('intval', $product->related_products ?? [])) ? 'selected' : '' }}>
                                                        {{ $prd->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="seo">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.seo')}}</h3> --}}

                                    <ul class="nav nav-tabs">
                                        @foreach (config('translatable.locales') as $code)
                                            <li class="@if ($loop->first) active @endif">
                                                <a data-toggle="tab"
                                                    href="#seo_{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">

                                        @foreach (config('translatable.locales') as $code)
                                            <div id="seo_{{ $code }}"
                                                class="tab-pane fade @if ($loop->first) in active @endif">

                                                <div class="col-md-10">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.meta_keywords') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <textarea name="seo_keywords[{{ $code }}]" rows="8" cols="80" class="form-control"
                                                                data-name="seo_keywords.{{ $code }}">{{ $product->getTranslation('seo_keywords', $code) }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.meta_description') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <textarea name="seo_description[{{ $code }}]" rows="8" cols="80" class="form-control"
                                                                data-name="seo_description.{{ $code }}">{{ $product->getTranslation('seo_description', $code) }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                                {{-- END UPDATE FORM --}}

                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{ __('apps::dashboard.general.edit_btn') }}
                                    </button>

                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script>
        $('#all_countries').on('switchChange.bootstrapSwitch', function(e, data) {

            if (data == true) {
                $('#select_countries').hide();
            } else {
                $('#select_countries').show();
            }

        });
        var resultAddVaraivation = $("#result-add-option")
        var variations = $("#click-varaition")
        // CATEGORIES TREE
        $(function() {
            $('#jstree').jstree({
                core: {
                    multiple: true
                },
                "checkbox": {
                    "keep_selected_style": false
                },
                "plugins": ["checkbox"]
            });

            $('#jstree').on("changed.jstree", function(e, data) {
                $('#root_category').val(data.selected);
            });

            @if (!auth()->user()->can('edit_products_category'))
                $('#jstree li').each(function() {
                    $("#jstree").jstree().disable_node(this.id);
                });
            @endif

            $('.searchKeywordsSelect').select2({
                tags: true,
            });
            $('span.select2-container').width('100%');

        });

        // PRODUCT HAS RELATION WITH OFFER / NEW ARRIVAL
        $(function() {

            @if ($product->offer)
                $("input#offer-form").prop("disabled", false);
                $('.offer-form').css('display', '');
                variations.hide()
                $(".variation-add input , .variation-edit input").prop("disabled", true);
            @endif

            @if ($product->newArrival)
                $("input#arrival-form").prop("disabled", false);
                $('.arrival-form').css('display', '');
            @endif

        });

        // DISABLED OR UNDISABLED OF STATUS FORM
        $("#offer-status").click(function(e) {

            if ($('#offer-status').is(':checked')) {
                @if (auth()->user()->can('edit_products_price'))
                    $("input#offer-form").prop("disabled", false);
                @endif
                $('.offer-form').css('display', '');
                variations.hide()
                $(".variation-add input , .variation-edit input").prop("disabled", true);
            } else {
                $("input#offer-form").prop("disabled", true);
                $('.offer-form').css('display', 'none');
                variations.show()
                $(".variation-add input , .variation-edit input").prop("disabled", false);
            }

        });

        // DISABLED OR UNDISABLED OF STATUS FORM
        $("#new-arraival-status").click(function(e) {

            if ($('#new-arraival-status').is(':checked')) {
                $("input#arrival-form").prop("disabled", false);
                $('.arrival-form').css('display', '');

            } else {
                $("input#arrival-form").prop("disabled", true);
                $('.arrival-form').css('display', 'none');

            }

        });

        // CHANGE STATUS OF CHECKBOX WITH 0 VALUE OR 1
        function checkFunction() {
            $('[name="offer_status"]').change(function() {
                if ($(this).is(':checked'))
                    $(this).next().prop('disabled', true);
                else
                    $(this).next().prop('disabled', false);
            });

            $('[name="arrival_status"]').change(function() {
                if ($(this).is(':checked'))
                    $(this).next().prop('disabled', true);
                else
                    $(this).next().prop('disabled', false);
            });

        }

        // GALLERY FORM / ADD NEW UPLOAD BUTTON
        $(document).ready(function() {
            var html = $("div.getGalleryForm").html();
            $(".addGallery").click(function(e) {
                e.preventDefault();
                $(".galleryForm").append(html);
                $('.lfm').filemanager('image');
            });
        });

        // DELETE UPLOAD BUTTON & IMAGE
        $(".galleryForm").on("click", ".delete-gallery", function(e) {
            e.preventDefault();
            $(this).closest('.form-group').remove();
        });

        var variatns_removed = [];
        var currentVaraition = @json($currentVaration);
        var deleteVaration = [];


        $('.variants-delete').click(function() {
            var val = $(this).closest(".filter").find("input[name='variants_ids[]']").val();
            variatns_removed.push(val);
            $("input[name='removed_variants']").val(variatns_removed);

            var delterow = $(this).closest('.filter');

            $(`.variation_options_update_${delterow.data('key')}`).remove();
            delterow.remove();
        });


        $(document).ready(function() {
            $(".load_variations").click(function(e) {
                e.preventDefault();

                var option_values = [];
                var current_option = [];

                if (deleteVaration.length > 0) {
                    for (let index = 0; index < currentVaraition.length; index++) {
                        if (!deleteVaration.includes(index)) {
                            current_option.push(currentVaraition[index])
                        }

                    }
                } else {
                    current_option = currentVaraition
                }

                $.each($("input[name='option_values_checkbox']:checked"), function() {
                    option_values.push($(this).val());
                });

                $(".option_values  > option:selected").each(function() {
                    option_values.push($(this).val());
                });

                $.ajax({
                        type: 'GET',
                        url: '{{ url(route('dashboard.values_by_option_id')) }}',
                        data: {
                            values_ids: option_values,
                            current_option
                        },
                        dataType: 'html',
                        encode: true,
                        beforeSend: function(xhr) {
                            $('.load_variations').prop('disabled', true);
                        }
                    })
                    .done(function(res) {
                        resultAddVaraivation.show();
                        $('.html_option_values').html(res);
                        $('.load_variations').prop('disabled', false);
                        ComponentsDateTimePickers.init();

                        $('.make-switch').bootstrapSwitch();
                    })

                    .fail(function(res) {
                        // console.log(res);
                        alert(
                            "{{ __('catalog::dashboard.products.validation.select_option_values') }}"
                        );
                        $('.load_variations').prop('disabled', false);
                    });
            });
        });

        //===========
        $("body").on("click", ".offer-status", function() {
            var elm = $(this)
            var form = $(`.offer-form_${elm.data('index')}`)
            if (elm.is(':checked')) {
                form.find("input#offer-form_v").prop("disabled", false);
                form.show()
            } else {
                form.find("input#offer-form_v").prop("disabled", true);
                form.hide()
            }

        })
        $(function() {
            $(".offer-status").each(function() {
                var elm = $(this)
                var form = $(`.offer-form_${elm.data('index')}`)
                if (elm.is(':checked')) {
                    form.find("input#offer-form_v").prop("disabled", false);
                    form.show()
                } else {
                    form.find("input#offer-form_v").prop("disabled", true);
                    form.hide()
                }

            })
        })
    </script>

    <script>
        function manageQty(value) {
            if (value == 'limited')
                $('#prdQty').show();
            else
                $('#prdQty').hide();
        }

        var rowCountsArray = [];
        @foreach ($product->images as $k => $img)
            rowCountsArray.push({{ $k }});
        @endforeach

        function addMoreImages() {

            var rowCount = Math.floor(Math.random() * 9000000000) + 1000000000;
            rowCountsArray.push(rowCount);

            var productImages = $('#product-images');
            var row = `
            <div id="prd-image-${rowCount}" class="prd-image-section">
                <div class="input-group">
                    <span class="input-group-btn">
                         <span class="btn btn-default btn-file-upload">
                         {{ __('catalog::dashboard.products.form.browse_image') }}<input type="file" name="images[${rowCount}]" onchange="readURL(this, ${rowCount});">
                         </span>
                    </span>
                    <input type="text" id="uploadInputName-${rowCount}" class="form-control upload-input-name" readonly>
                    <button type="button" class="btn btn-danger btnRemoveMore" onclick="removeMoreImage(${rowCount}, ${rowCount}, 'row')">X</button>
                </div>
                <img id='img-upload-preview-${rowCount}' class="img-preview img-thumbnail" alt="image preview" style="display: none;"/>
            </div>`;

            productImages.prepend(row);
        }

        function removeMoreImage(index, rowId, flag = '') {

            if (rowCountsArray.length > 1) {

                if (flag === 'db') {

                    var r = confirm("{{ __('catalog::dashboard.products.form.add_ons.confirm_msg') }}");
                    if (r == true) {

                        $.ajax({
                            url: "{{ route('dashboard.products.delete_product_image') }}?id=" + rowId,
                            type: 'get',
                            dataType: 'JSON',
                            contentType: false,
                            cache: false,
                            processData: false,

                            beforeSend: function() {
                                $('.progress-info').show();
                                $('.progress-bar').width('0%');
                                resetErrors();
                            },
                            success: function(data) {

                                if (data[0] == true) {

                                    $('#prd-image-' + index).remove();
                                    const k = rowCountsArray.indexOf(parseInt(index));
                                    if (k > -1) {
                                        rowCountsArray.splice(k, 1);
                                    }

                                    successfully(data);
                                    resetErrors();
                                } else {
                                    displayMissing(data);
                                }

                            },
                            error: function(data) {
                                displayErrors(data);
                            },
                        });

                    }
                } else {
                    $('#prd-image-' + index).remove();
                    const i = rowCountsArray.indexOf(index);
                    if (i > -1) {
                        rowCountsArray.splice(i, 1);
                    }
                }

            } else {
                alert("{{ __('catalog::dashboard.products.form.add_ons.at_least_one_field') }}");
                return false;
            }

        }

        function toggleOfferType(type = '') {
            if (type === 'amount') {
                $('#offerAmountSection').show();
                $('#offerPercentageSection').hide();
                // $('input[name="offer_percentage"]').val('');
            } else if (type === 'percentage') {
                $('#offerPercentageSection').show();
                $('#offerAmountSection').hide();
                // $('input[name="offer_price"]').val('');
            }
        }
    </script>


    <script>
        var sku = document.getElementById("sku")
        window.callbackFormSuccess = function(data, is_create) {
            if (data.length >= 3) sku.value = data[2]
        }

        // handle copy variations
        var copyButtion = $("#copy-variation-button");
        var varaitionElment = []
        var variationContainer = $(".variation-container");


        // select all
        $("body").on("click", "#select-variation-all", function(event) {
            event.preventDefault()
            $("input:checkbox[name=copy_options]").trigger('click');


        })

        // copy button
        $("body").on("click", "#copy-variation-button", function(event) {
            event.preventDefault()
            // validation

            if (varaitionElment.length == 0) {
                alert("Must check at least one form input")
                return
            }

            if ($(".variation-container tr.filter ").length <= 1) {
                alert("Must Contain more then one variation")
                return
            }
            varaitionElment.forEach(function(value) {
                copyValue(value)
            })

            //==============================

        });

        // function copyValue
        function copyValue(classElment) {
            var elments = $(`.variation-container .${classElment}`)
            var firstElement = elments.first()

            // if input will sest vlaue
            if (firstElement.is("input[type=text]")) {
                elments.slice(1).each(function() {
                    $(this).val(firstElement.val())
                })
            }

            // if check input checbox
            if (firstElement.is("input[type=checkbox]")) {
                elments.slice(1).each(function() {
                    var check = $(this)
                    if (check.is(":checked") != firstElement.is(":checked")) {
                        check.trigger("click");
                    }

                })
            }

        }


        // add elment to copy
        $("body").on("click", ".copy-option", function() {
            addElmentOptionToArray()
        })

        function addElmentOptionToArray() {
            varaitionElment = []
            $("input:checkbox[name=copy_options]:checked").each(function() {
                varaitionElment.push($(this).val());
            });
        }


        //=================================

        // start handle type
        var productType = $(".type-product")
        var typeElment = $(".type-elment")
        var head = $(".head")


        productType.change(function() {
            // alert(productType.val())
            handleProductType()
        })

        function handleProductType() {
            var value = $('input[name="type"]:checked').val()
            // reset
            typeElment.find("input,select").prop("disabled", true)
            head.hide().end()
            if (value == 1) {
                typeElment.find(".html_option_values").html("")
            }
            typeElment.not(`.type_${value}`).removeClass("active")
            //=======

            var selectElment = $(`.type_${value}`)
            $(`.type_${value}-head`).show().end().trigger("click")
            selectElment.find("input,select").prop("disabled", false)
        }

        handleProductType()

        $(".labe_type").click(function() {
            $(`${$(this).data('target')}`).click()
        })

        function copyToClipboard() {
            var copyText = document.getElementById("copyProductUrlInput").value;
            navigator.clipboard.writeText(copyText).then(() => {
                // Alert the user that the action took place.
                // Nobody likes hidden stuff being done under the hood!
                alert("{{ __('catalog::dashboard.products.alerts.url_copied_successfully') }}");
            });
        }

        ////===========================
    </script>

@endsection
