@extends('apps::dashboard.layouts.app')
@section('title', __('catalog::dashboard.products.routes.create'))

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
            /*width: 77%;*/
            /*height: 200px;*/
            height: auto;
            width: 15%;
            display: none;
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
                        <a href="#">{{ __('catalog::dashboard.products.routes.create') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.products.store') }}">
                    @csrf
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">

                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">

                                            {{-- <div style="margin: 10px;">
                                                    <span class="pull-right">منتج متعدد الإختيارات</span>
                                                    <input type="checkbox" id="productTypeSwitch" class="make-switch pull-left text-left"
                                                           data-size="small"
                                                           name="product_type">
                                                    <div class="help-block"></div>
                                                </div> --}}

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

                                {{-- CREATE FORM --}}

                                <div class="tab-pane active fade in" id="global_setting">
                                    <ul class="nav nav-tabs">
                                        @foreach (config('translatable.locales') as $code)
                                            <li class="@if ($loop->first) active @endif">
                                                <a data-toggle="tab"
                                                    href="#first_{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">

                                        @foreach (config('translatable.locales') as $code)
                                            <div id="first_{{ $code }}"
                                                class="tab-pane fade @if ($loop->first) in active @endif">

                                                <div class="col-md-10">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.title') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="title[{{ $code }}]"
                                                                class="form-control" data-name="title.{{ $code }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    {!! field()->ckEditor5(
                                                        'description[' . $code . ']',
                                                        __('catalog::dashboard.products.form.description') . '-' . $code,
                                                    ) !!}

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('catalog::dashboard.products.form.delivery_time') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="delivery_time[{{ $code }}]"
                                                                class="form-control"
                                                                data-name="delivery_time.{{ $code }}">
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
                                                                data-name="store_location.{{ $code }}">
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
                                                        value="{{ generateRandomCode() }}" data-name="sku">
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
                                                            <option value="{{ $brand['id'] }}">
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
                                                            <option value="{{ $vendor['id'] }}">
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
                                                        placeholder="{{ __('catalog::dashboard.products.form.weight') }}"
                                                        class="form-control" data-name="shipment.weight"
                                                        name="shipment[weight]">
                                                    <div class="help-block"></div>
                                                </div>

                                                <div class="col-md-2 text-left">
                                                    <label>{{ __('catalog::dashboard.products.form.width') }}</label>
                                                    <input type="number"
                                                        placeholder="{{ __('catalog::dashboard.products.form.width') }}"
                                                        data-name="shipment.width" class="form-control"
                                                        name="shipment[width]">
                                                    <div class="help-block"></div>
                                                </div>

                                                <div class="col-md-2 text-left">
                                                    <label>{{ __('catalog::dashboard.products.form.length') }}</label>
                                                    <input type="number"
                                                        placeholder="{{ __('catalog::dashboard.products.form.length') }}"
                                                        data-name="shipment.length" class="form-control"
                                                        name="shipment[length]">
                                                    <div class="help-block"></div>
                                                </div>

                                                <div class="col-md-2 text-left">
                                                    <label>{{ __('catalog::dashboard.products.form.height') }}</label>
                                                    <input type="number"
                                                        placeholder="{{ __('catalog::dashboard.products.form.height') }}"
                                                        class="form-control" data-name="shipment.height"
                                                        name="shipment[height]">
                                                    <div class="help-block"></div>
                                                </div>

                                            </div>

                                            {!! field()->checkBox('all_countries', __('catalog::dashboard.products.form.all_countries')) !!}

                                            <div id="select_countries" style="display:block">
                                                {!! field()->multiSelect(
                                                    'countries',
                                                    __('catalog::dashboard.products.form.countries'),
                                                    $supported_countries->pluck('title', 'id')->toArray(),
                                                    null,
                                                    ['data-placeholder' => __('catalog::dashboard.products.form.all_countries')],
                                                ) !!}
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.image') }}
                                                </label>
                                                <div class="col-md-9">
                                                    @include('core::dashboard.shared.file_upload', [
                                                        'image' => null,
                                                    ])
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.sort') }}
                                                </label>
                                                <div class="col-md-3">
                                                    <input type="number" name="sort" class="form-control"
                                                        data-name="sort" value="0">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.is_new') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                        data-size="small" name="is_new">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.status') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                        data-size="small" name="status">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.allow_wrapping') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="allow_wrapping"
                                                        data-size="small" name="allow_wrapping">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.show_product_website') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="show_product_website"
                                                        data-size="small" name="show_product_website">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.show_product_pos') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="show_product_pos"
                                                        data-size="small" name="show_product_pos">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            {{--  @if (auth()->user()->hasRole('admins'))  --}}
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.featured') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                        data-size="small" name="featured">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            {{--  @endif  --}}

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.for_boys_girls') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="form-check">
                                                        <span style="margin: 10px;">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="gender-radio"
                                                                    name="for_boys_girls" value="both" checked>
                                                                {{ __('catalog::dashboard.products.form.for_both') }}
                                                            </label>
                                                        </span>

                                                        <span style="margin: 10px;">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="for_boys_girls"
                                                                    class="gender-radio" value="boys">
                                                                {{ __('catalog::dashboard.products.form.for_boys') }}
                                                            </label>
                                                        </span>

                                                        <span style="margin: 10px;">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="for_boys_girls"
                                                                    class="gender-radio" value="girls">
                                                                {{ __('catalog::dashboard.products.form.for_girls') }}
                                                            </label>
                                                        </span>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="tab-pane fade in" id="categories">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.categories')}}
                                    </h3> --}}
                                    <div id="jstree">
                                        @include('catalog::dashboard.tree.products.view', [
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
                                                    data-name="price">
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
                                                            value="unlimited" onchange="manageQty(this.value)" checked>
                                                        <label class="form-check-label">
                                                            {{ __('catalog::dashboard.products.form.unlimited') }}
                                                        </label>
                                                    </span>

                                                    <span style="margin: 10px;">
                                                        <input type="radio" name="manage_qty" class="manageQty"
                                                            value="limited" onchange="manageQty(this.value)">
                                                        <label class="form-check-label">
                                                            {{ __('catalog::dashboard.products.form.limited') }}
                                                        </label>

                                                        <input type="number" id="prdQty" name="qty"
                                                            class="form-control" data-name="qty" style="display: none;">
                                                        <div class="help-block"></div>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="page-title">{{ __('catalog::dashboard.products.form.offer') }}</h3>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('catalog::dashboard.products.form.offer_status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" id="offer-status" name="offer_status">
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
                                                                onclick="toggleOfferType('amount')" checked="">
                                                            {{ __('catalog::dashboard.products.form.offer_type.amount') }}
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="offer_type"
                                                                id="offerPercentageRadioBtn" value="percentage"
                                                                onclick="toggleOfferType('percentage')">
                                                            {{ __('catalog::dashboard.products.form.offer_type.percentage') }}
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="offerAmountSection">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.offer_price') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number" step="0.1" min="0" id="offer-form"
                                                        name="offer_price" class="form-control" data-name="offer_price">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="offerPercentageSection" style="display: none">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.percentage') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number" step="0.5" min="0"
                                                        id="offer-percentage-form" name="offer_percentage"
                                                        class="form-control" data-name="offer_percentage">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            {{-- <div class="input-group input-large date-picker input-daterange"
                                                     data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                                    <input type="text" class="form-control" name="from">
                                                    <span class="input-group-addon"> to </span>
                                                    <input type="text" class="form-control" name="to">
                                                </div> --}}

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.start_at') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-medium date date-picker"
                                                        data-date-format="yyyy-mm-dd" data-date-start-date="+0d">
                                                        <input type="text" id="offer-form" class="form-control"
                                                            name="start_at" data-name="start_at" disabled>
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
                                                            name="end_at" disabled data-name="end_at">
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

                                        <div class="row">

                                            @foreach ($options as $option)
                                                <div class="col-md-5" style="margin: 0 0 0 10px;">
                                                    <div class="form-group">
                                                        <label>{{ $option->title }}</label>
                                                        @if (!$option->values()->where('type', 'color')->count())
                                                            <select name="option_values"
                                                                class="option_values form-control select2" multiple="">
                                                                <option value=""></option>
                                                                @foreach ($option->values as $value)
                                                                    <option value="{{ $value->id }}"
                                                                        data-name="option_values[{{ $option->id }}]">
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
                                                                            class="option_values_checkbox">
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
                                        <div class="html_option_values"></div>
                                    </div>
                                @endif

                                <div class="tab-pane fade in" id="images">
                                    {{-- <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.images')}}</h3> --}}
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            {{-- <label>Upload Image</label> --}}
                                            <button type="button" onclick="addMoreImages()"
                                                class="btn btn-success btnAddMore">
                                                {{ __('catalog::dashboard.products.form.btn_add_more') }} <i
                                                    class="fa fa-plus-circle"></i>
                                            </button>

                                            <div id="product-images">

                                                <div id="prd-image-0" class="prd-image-section">
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
                                                        <button type="button" class="btn btn-danger btnRemoveMore"
                                                            dolores onclick="removeMoreImage(0, 0, 'row')">X
                                                        </button>
                                                    </div>
                                                    <img id='img-upload-preview-0' class="img-preview img-thumbnail"
                                                        alt="image preview" />
                                                </div>

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
                                                    <option value="{{ $tag['id'] }}">
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
                                                    <option value="{{ $age['id'] }}">
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
                                                {{-- <option value=""></option> --}}
                                                @foreach ($searchKeywords as $keyword)
                                                    <option value="{{ $keyword['id'] }}">
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
                                                @foreach ($sharedActiveProducts as $product)
                                                    <option value="{{ $product['id'] }}">
                                                        {{ $product->title }}
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
                                                                data-name="seo_keywords.{{ $code }}"></textarea>
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
                                                                data-name="seo_description.{{ $code }}"></textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                                {{-- END CREATE FORM --}}
                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{ __('apps::dashboard.general.add_btn') }}
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
        // JS TREE FOR CATEGORIES
        var variations = $("#click-varaition");

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

            $('.searchKeywordsSelect').select2({
                tags: true,
            });
            $('span.select2-container').width('100%');
        });

        // CHANGE DISPLAY OF OFFER FORM
        $("#offer-status").click(function(e) {

            if ($('#offer-status').is(':checked')) {
                $("input#offer-form").prop("disabled", false);
                $('.offer-form').css('display', '');
                variations.hide()
                $(".variation-add input").prop("disabled", true);
            } else {
                $("input#offer-form").prop("disabled", true);
                $('.offer-form').css('display', 'none');
                variations.show()
                $(".variation-add input").prop("disabled", false);
            }

        });
        // variation
        $(document).ready(function() {
            $(".load_variations").click(function(e) {
                e.preventDefault();

                var option_values = [];

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
                            values_ids: option_values
                        },
                        dataType: 'html',
                        encode: true,
                        beforeSend: function(xhr) {
                            $('.load_variations').prop('disabled', true);
                        }
                    })
                    .done(function(res) {
                        $('.html_option_values').html(res);
                        ComponentsDateTimePickers.init();
                        $('.load_variations').prop('disabled', false);
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

        //======

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
    </script>

    <script>
        function manageQty(value) {
            if (value == 'limited')
                $('#prdQty').show();
            else
                $('#prdQty').hide();
        }

        var rowCountsArray = [0];

        function addMoreImages() {

            var rowCount = Math.floor(Math.random() * 9000000000) + 1000000000;
            rowCountsArray.push(rowCount);

            var productImages = $('#product-images');
            var row = `
            <div id="prd-image-${rowCount}" class="prd-image-section">
                <div class="input-group">
                    <span class="input-group-btn">
                         <span class="btn btn-default btn-file-upload">
                         {{ __('catalog::dashboard.products.form.browse_image') }}<input type="file" name="images[]" onchange="readURL(this, ${rowCount});">
                         </span>
                    </span>
                    <input type="text" id="uploadInputName-${rowCount}" class="form-control upload-input-name" readonly>
                    <button type="button" class="btn btn-danger btnRemoveMore" onclick="removeMoreImage(${rowCount}, ${rowCount}, 'row')">X</button>
                </div>
                <img id='img-upload-preview-${rowCount}' class="img-preview img-thumbnail" alt="image preview"/>
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
                                    const k = rowCountsArray.indexOf(index);
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

        ////===========================
    </script>

@endsection
