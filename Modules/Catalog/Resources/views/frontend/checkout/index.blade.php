@extends('apps::frontend.layouts.master')
@section('title', __('catalog::frontend.checkout.index.title'))
@push('plugins_styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/intlTelInput.min.css') }}">
@endpush
@push('styles')
    <style>
        /* start loader style */

        #checkoutInformationLoaderDiv {
            display: none;
            margin: 15px auto;
            justify-content: center;
        }

        #deliveryPriceLoaderDiv {
            display: none;
            margin: 15px 112px;
            justify-content: center;
        }

        #checkoutInformationLoaderDiv .my-loader,
        #deliveryPriceLoaderDiv .my-loader {
            border: 10px solid #f3f3f3;
            border-radius: 50%;
            border-top: 10px solid #3498db;
            width: 70px;
            height: 70px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* end loader style */

        .day-block-company {
            flex: 1;
            background: #fff;
            margin-left: 20px;
            padding: 20px 10px;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            color: #666 !important;
            border: none;
        }

        .day-block-company:last-child {
            margin-left: 0;
        }

        .day-block-company.active {
            color: #fff !important;
            background: #383d41;
        }

        @media (max-width: 991px) {
            .day-block-company {
                flex: none;
                width: 25%;
            }
        }

        .time-radio-btn {
            height: 16px;
            width: 16px;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="page-crumb mt-30">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.home') }}">
                            <i class="ti-home"></i>
                            {{ __('apps::frontend.nav.home_page') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.shopping-cart.index') }}">
                            {{ __('catalog::frontend.cart.title') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">
                        {{ __('catalog::frontend.checkout.index.title') }}
                    </li>
                </ol>
            </nav>
        </div>

        @include('apps::frontend.layouts._alerts')
        <div class="inner-page">
            <form method="post" action="{{ route('frontend.orders.create_order') }}">
                @csrf
                <input type="hidden" name="radio_address_type" id="checkoutAddressType" value="">
                <input type="hidden" id="selectedStateFromAddress" name="selected_state_id"
                    value="{{ get_cookie_value(config('core.config.constants.ORDER_STATE_ID')) ?? null }}">

                <div class="row">

                    <div class="col-md-8">
                        <div class="cart-inner">

                            <div class="address-types">
                                <h2 class="cart-title">{{ __('catalog::frontend.checkout.address.title') }}</h2>
                                <div class="panel-group" id="accordion">

                                    <div class="panel panel-default">
                                        <div class="panel-heading panel-heading-custom">
                                            <h4 class="panel-title">
                                                <a class="{{-- address-type1 --}} addressType-1 checkout-choose-address"
                                                    data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                                    data-name="unknown_address" data-click-state="0">
                                                    <div class="checkboxes radios">
                                                        <input id="check-unknown-address" type="radio" name="check"
                                                            {{ old('radio_address_type') == 'unknown_address' ? 'checked' : '' }}>
                                                        <label
                                                            for="check-unknown-address">{{ __('catalog::frontend.checkout.address.options.unknown_address') }}</label>
                                                    </div>
                                                </a>
                                            </h4>
                                        </div>

                                        <div id="collapseOne" class="panel-collapse collapse">
                                            <div class="panel-body">

                                                <div class="row address_selector">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <select class="select-detail select2 form-control"
                                                                name="country_id" {{--  {{ !empty(old('country_id')) && old('country_id') ? 'data-select2-id="' . old('country_id') . '"' : '' }}  --}} tabindex="-1"
                                                                aria-hidden="true" onchange="getCitiesByCountryId(this)">
                                                                <option value="">
                                                                    {{ __('user::frontend.addresses.form.select_country') }}
                                                                </option>
                                                                @foreach ($countries as $id => $country_title)
                                                                    <option value="{{ $id }}"
                                                                        {{ !empty(old('country_id')) && old('country_id') && old('country_id') == $id ? 'selected' : '' }}>
                                                                        {{ $country_title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="state_container">
                                                            <div class="state_selector_content_loader"
                                                                style="display: none">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status"
                                                                        style="width: 2rem; height: 2rem;">
                                                                        <span
                                                                            class="sr-only">{{ __('apps::frontend.Loading') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="state_selector_content">
                                                                <div class="form-group">
                                                                    <select class="stateSelect2 area_selector"
                                                                        name="state_id"
                                                                        onchange="onStateChanged(event.target.value)">
                                                                        <option value=""></option>
                                                                        @if (isset($states) && count($states) > 0)
                                                                            @foreach ($states as $state)
                                                                                <option value="{{ $state->id }}"
                                                                                    @if (!is_null(Cart::getCondition('company_delivery_fees'))) {{ Cart::getCondition('company_delivery_fees')->getAttributes()['state_id'] == $state->id ? 'selected' : '' }}
                                                                    @else
                                                                        {{ old('state_id') == $state->id ? 'selected' : '' }} @endif>
                                                                                    {{ $state->title }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <input type="text" value="{{ old('receiver_name') }}"
                                                                name="receiver_name" id="txtReceiverName"
                                                                placeholder="{{ __('catalog::frontend.checkout.address.form.receiver_name') }}"
                                                                autocomplete="off" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <input type="text" value="{{ old('receiver_mobile') }}"
                                                                name="receiver_mobile" id="txtReceiverMobile"
                                                                placeholder="{{ __('catalog::frontend.checkout.address.form.receiver_mobile') }}"
                                                                autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading panel-heading-custom">
                                            <h4 class="panel-title">
                                                <a class="{{-- address-type2 --}} addressType-2 checkout-choose-address"
                                                    data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"
                                                    data-name="known_address" data-click-state="0">
                                                    <div class="checkboxes radios">
                                                        <input id="check-etr" type="radio" name="check"
                                                            {{ old('radio_address_type') == 'known_address' ? 'checked' : '' }}>
                                                        <label
                                                            for="check-etr">{{ __('catalog::frontend.checkout.address.options.known_address') }}</label>
                                                    </div>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse">
                                            <div class="panel-body">

                                                <div class="row address_selector">

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <select class="select-detail select2 form-control"
                                                                name="country_id" {{--  {{ !empty(old('country_id')) && old('country_id') ? 'data-select2-id="' . old('country_id') . '"' : '' }}  --}} tabindex="-1"
                                                                aria-hidden="true" onchange="getCitiesByCountryId(this)">
                                                                <option value="">
                                                                    {{ __('user::frontend.addresses.form.select_country') }}
                                                                </option>
                                                                @foreach ($countries as $id => $country_title)
                                                                    <option value="{{ $id }}"
                                                                        {{ !empty(old('country_id')) && old('country_id') && old('country_id') == $id ? 'selected' : '' }}>
                                                                        {{ $country_title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="state_container">
                                                            <div class="state_selector_content_loader"
                                                                style="display: none">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status"
                                                                        style="width: 2rem; height: 2rem;">
                                                                        <span
                                                                            class="sr-only">{{ __('apps::frontend.Loading') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="state_selector_content">
                                                                <div class="form-group">
                                                                    <select class="stateSelect2 area_selector"
                                                                        name="state_id"
                                                                        onchange="onStateChanged(event.target.value)">
                                                                        <option value="">
                                                                            {{-- {{ __('user::frontend.addresses.form.states') }} --}}
                                                                        </option>
                                                                        @if (isset($states) && count($states) > 0)
                                                                            @foreach ($states as $state)
                                                                                <option value="{{ $state->id }}"
                                                                                    @if (!is_null(Cart::getCondition('company_delivery_fees'))) {{ Cart::getCondition('company_delivery_fees')->getAttributes()['state_id'] == $state->id ? 'selected' : '' }}
                                                                        @else
                                                                            {{ old('state_id') == $state->id ? 'selected' : '' }} @endif>
                                                                                    {{ $state->title }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <input type="text" value="{{ old('block') }}"
                                                                name="block" id="txtBlock"
                                                                placeholder="{{ __('user::frontend.addresses.form.block') }}"
                                                                autocomplete="off" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <input type="text" value="{{ old('building') }}"
                                                                name="building" id="txtBuilding"
                                                                placeholder="{{ __('user::frontend.addresses.form.building') }}"
                                                                autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <input type="text" value="{{ old('mobile') }}"
                                                                name="mobile" id="txtMobile"
                                                                placeholder="{{ __('user::frontend.addresses.form.mobile') }}"
                                                                autocomplete="off" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <input type="text" value="{{ old('street') }}"
                                                                name="street" id="txtStreet"
                                                                placeholder="{{ __('user::frontend.addresses.form.street') }}"
                                                                autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="address" id="txtAddress"
                                                        placeholder="{{ __('user::frontend.addresses.form.additional_instructions') }}">{{ old('address') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (auth()->user() && count(auth()->user()->addresses) > 0)
                                        <div class="panel panel-default">
                                            <div class="panel-heading panel-heading-custom">
                                                <h4 class="panel-title">
                                                    <a class="{{-- address-type3 --}} addressType-3 checkout-choose-address"
                                                        data-toggle="collapse" data-parent="#accordion"
                                                        href="#collapseThree" data-name="selected_address"
                                                        data-click-state="0">
                                                        <div class="checkboxes radios">
                                                            <input id="check-selected-address" type="radio"
                                                                name="check"
                                                                {{ old('radio_address_type') == 'selected_address' ? 'checked' : '' }}>
                                                            <label
                                                                for="check-selected-address">{{ __('catalog::frontend.checkout.address.options.choose_from_addresses') }}</label>
                                                        </div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="previous-address choose-add">

                                                        <input type="hidden" name="selected_address_id">

                                                        @foreach (auth()->user()->addresses as $k => $address)
                                                            <div class="address-item media align-items-center"
                                                                data-id="{{ $address->id }}" data-click-state="0"
                                                                id="checkoutSelectedAddress-{{ $address->id }}"
                                                                onclick="selectPreviousAddress('{{ $address->id }}')">
                                                                <div class="product-summ-det">
                                                                    <p class="d-flex">
                                                                        <span
                                                                            class="d-inline-block right-side">{{ __('catalog::frontend.checkout.address.form.address_name') }}</span>
                                                                        <span class="d-inline-block left-side">
                                                                            {{ $address->state->title }}
                                                                        </span>
                                                                    </p>
                                                                    <p class="d-flex">
                                                                        <span class="d-inline-block right-side">
                                                                            {{ __('catalog::frontend.checkout.address.form.address_details') }}</span>
                                                                        <span class="d-inline-block left-side">
                                                                            <span>{{ __('catalog::frontend.checkout.address.form.street') }}
                                                                                : {{ $address->street }}</span>
                                                                            <span> -
                                                                                {{ __('catalog::frontend.checkout.address.form.block') }}
                                                                                : {{ $address->block }}</span>
                                                                            <span> -
                                                                                {{ __('catalog::frontend.checkout.address.form.building') }}
                                                                                : {{ $address->building }}</span>
                                                                            @if ($address->address)
                                                                                <br>
                                                                                <span> -
                                                                                    {{ __('catalog::frontend.checkout.address.form.address_details') }}
                                                                                    : {{ $address->address }}</span>
                                                                            @endif
                                                                        </span>
                                                                    </p>
                                                                    <p class="d-flex">
                                                                        <span class="d-inline-block right-side">
                                                                            {{ __('catalog::frontend.checkout.address.form.mobile') }}</span>
                                                                        <span class="d-inline-block left-side">
                                                                            {{ $address->mobile }} </span>
                                                                    </p>
                                                                </div>
                                                                {{-- <div class="text-left address-operations">
                                                                <a class="btn edit-address" data-toggle="modal"
                                                                   data-target="#exampleModalLong">
                                                                    <i class="ti-pencil-alt"></i> تعديل
                                                                </a>
                                                            </div> --}}
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>


                            @if ($shippingCompany && $shippingCompany->availabilities)
                                <div class="time-delivery choose-companies mt-20 pt-30">

                                    <input type="hidden" name="shipping_company[id]"
                                        value="{{ $shippingCompany->id ?? '' }}">
                                    <input type="hidden" name="shipping_company[day]"
                                        value="{{ old('shipping_company.day') }}">

                                    <h2 class="cart-title">
                                        {{ __('catalog::frontend.checkout.companies.choose_delivery_time') }}
                                    </h2>
                                    <div class="nav nav-pills choose-day d-flex align-items-center text-center mb-30">

                                        @foreach ($shippingCompany->availabilities as $k => $day)
                                            <a data-toggle="pill" href="#menu-{{ $day->day_code }}"
                                                class="day-block  day-block-company deliveryDay-{{ $day->day_code }}
                                       {{ old('shipping_company.day') == $day->day_code ? 'active' : '' }}"
                                                onclick="chooseCompanyDeliveryDay('{{ $shippingCompany->id }}', '{{ $day->day_code }}')"
                                                data-companyName="{{ $shippingCompany->id }}"
                                                data-dayCode="{{ $day->day_code }}" data-state-value="0">
                                                <span
                                                    class="d-block">{{ getDayByDayCode($day->day_code) != '' ? getDayByDayCode($day->day_code)['day'] : '' }}</span>
                                                {{ ucfirst($day->day_code) }}
                                            </a>
                                        @endforeach

                                    </div>

                                    <div class="tab-content">

                                        @foreach ($shippingCompany->availabilities as $k => $day)
                                            <div id="menu-{{ $day->day_code }}"
                                                class="tab-pane fade {{ old('shipping_company.day') == $day->day_code ? 'show active' : '' }}">
                                                <div class="panel panel-default">
                                                    {{--  <div class="panel-heading">
                                                        <div class="panel-title">
                                                            <h5>
                                                                {{ $shippingCompany->name }}
                                                            </h5>
                                                        </div>
                                                    </div>  --}}
                                                    <div class="panel-body">

                                                        <div class="choose-time comp-time">
                                                            @if (!is_null($day->custom_times) && $day->is_full_day == 0)
                                                                @foreach ($day->custom_times as $i => $time)
                                                                    <div class="radios mb-20">
                                                                        <input type="radio" class="time-radio-btn"
                                                                            id="time_{{ $day->day_code }}_{{ $i }}"
                                                                            name="delivered_time_{{ $day->day_code }}"
                                                                            value="{{ $time['time_from'] . '_' . $time['time_to'] }}"
                                                                            {{ old('delivered_time_' . $day->day_code) == $time['time_from'] . '_' . $time['time_to'] ? 'checked' : '' }}>
                                                                        <label>{{ __('catalog::frontend.checkout.companies.times.from') . ': ' . $time['time_from'] . ' - ' . __('catalog::frontend.checkout.companies.times.to') . ': ' . $time['time_to'] }}</label>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <b>{{ __('catalog::frontend.checkout.validation.delivery_time.not_found') }}</b>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="order-summery cart-order-summery">
                            <h4 class="order-summ-title">{{ __('catalog::frontend.checkout.index.order_details') }}</h4>
                            <div class="minicart-content-wrapper">
                                <div class="minicart-items-wrapper">
                                    <ol class="minicart-items">

                                        @foreach (getCartContent() as $k => $item)
                                            <li class="product-item">
                                                <div class="media align-items-center">
                                                    <div class="pro-img d-flex align-items-center">
                                                        <img class="img-fluid"
                                                            src="{{ url($item->attributes->product->image) }}"
                                                            alt="Author">
                                                    </div>
                                                    <div class="media-body">
                                                        <span class="product-name">
                                                            @if ($item->attributes->product_type == 'product')
                                                                <a
                                                                    href="{{ route('frontend.products.index', [$item->attributes->product->slug]) }}">
                                                                    {{ $item->attributes->product->title }}
                                                                </a>
                                                            @else
                                                                <a
                                                                    href="{{ route('frontend.products.index', [$item->attributes->product->product->slug, generateVariantProductData($item->attributes->product->product, $item->attributes->product->id, $item->attributes->selectedOptionsValue)['slug']]) }}">
                                                                    {!! generateVariantProductData(
                                                                        $item->attributes->product->product,
                                                                        $item->attributes->product->id,
                                                                        $item->attributes->selectedOptionsValue,
                                                                    )['name'] !!}
                                                                </a>
                                                            @endif
                                                        </span>
                                                        <div class="product-price d-block"><span class="text-muted">x
                                                                {{ $item->quantity }}</span>
                                                            <span>{{ priceWithCurrenciesCode($item->price) }} </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                                <div class="d-flex mb-20 align-items-center">
                                    <span class="d-inline-block right-side flex-1">
                                        {{ __('catalog::frontend.cart.subtotal') }}
                                    </span>
                                    <span class="d-inline-block left-side">
                                        {{ priceWithCurrenciesCode(number_format(getCartSubTotal(), 3)) }}
                                    </span>
                                </div>
                                <div id="deliveryPriceLoaderDiv" style="display: none">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border" role="status" style="width: 2rem; height: 2rem;">
                                            <span class="sr-only">{{ __('apps::frontend.Loading') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="totalCompaniesDeliveryPrice">

                                    @if (Cart::getCondition('company_delivery_fees'))
                                        <div class="d-flex mb-20 align-items-center">
                                            <span class="d-inline-block right-side flex-1">
                                                {{ __('catalog::frontend.checkout.shipping') }}
                                            </span>
                                            <span class="d-inline-block left-side">
                                                <span id="totalDeliveryPrice">
                                                    {{ priceWithCurrenciesCode(number_format(Cart::getCondition('company_delivery_fees')->getValue(), 3)) }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="d-flex mb-20 align-items-center">
                                            <span class="d-inline-block right-side flex-1">
                                                {{ Cart::getCondition('company_delivery_fees')->getAttributes()['delivery_time'] ?? '' }}
                                            </span>
                                        </div>
                                    @else
                                        <span id="totalDeliveryPrice"></span>
                                    @endif
                                </div>

                                <div id="couponContainer">
                                    @if (\Cart::getCondition('coupon_discount') != null && \Cart::getCondition('coupon_discount')->getValue() != 0)
                                        <div class="d-flex mb-20 align-items-center">
                                            <span
                                                class="d-inline-block right-side flex-1">{{ __('catalog::frontend.cart.coupon_value') }}</span>
                                            <span class="d-inline-block left-side">

                                                {{ priceWithCurrenciesCode(number_format(abs(Cart::getCondition('coupon_discount')->getValue()), 3)) }}
                                            </span>
                                        </div>
                                    @endif

                                    @if (!is_null(getCartItemsCouponValue()) && getCartItemsCouponValue() != 0)
                                        <div class="d-flex mb-20 align-items-center">
                                            <span
                                                class="d-inline-block right-side flex-1">{{ __('catalog::frontend.cart.coupon_value') }}</span>
                                            <span class="d-inline-block left-side">
                                                {{ priceWithCurrenciesCode(number_format(getCartItemsCouponValue(), 3)) }}
                                            </span>
                                        </div>
                                    @endif

                                </div>

                                <div id="couponForm">
                                    <form class="coupon-form" method="POST">
                                        @csrf
                                        <div id="loaderCouponDiv" style="display: none">
                                            <div class="d-flex justify-content-center">
                                                <div class="spinner-border" role="status"
                                                    style="width: 2rem; height: 2rem;">
                                                    <span class="sr-only">{{ __('apps::frontend.Loading') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex promo-code align-items-center justify-content-between">
                                            <div class="d-flex mb-20 promo-code align-items-center">

                                                <input type="hidden" value="" id="coupon_discount_id"
                                                    name="coupon_discount_id">
                                                <input type="hidden" value="" id="coupon_discount_value"
                                                    name="coupon_discount_value">
                                                <span class="d-inline-block right-side flex-1">
                                                    <input type="text" id="txtCouponCode" name=""
                                                        placeholder=" {{ __('catalog::frontend.cart.enter_discount_number') }}">
                                                </span>
                                                <span class="d-inline-block left-side">
                                                    <button class="btn btn-add" id="btnCheckCoupon" type="button">
                                                        {{ __('apps::frontend.Add') }}
                                                    </button>
                                                </span>
                                                <span class="d-inline-block left-side remove" title="ازالة الكوبون"><i
                                                        class="ti-close"></i></span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="minicart-footer">
                                    @include('catalog::frontend.checkout.components.checkout-payments')
                                    <div class="subtotal d-flex text-center justify-content-center mb-20">
                                        <span class="label"> {{ __('catalog::frontend.checkout.total') }} :</span>
                                        <span class="price" id="cartTotalAmount">
                                            {{ priceWithCurrenciesCode(number_format(getCartTotal(), 3)) }}
                                        </span>
                                    </div>
                                    <div class="actions">
                                        <button type="submit"
                                            class="btn btn-checkout btn-them btn-block main-custom-btn">
                                            {{ __('catalog::frontend.cart.btn.checkout') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @if (auth()->check())
        @include('user::frontend.profile.addresses.components.address-model', [
            'route' => route('frontend.profile.address.store'),
            'view_type' => 'checkout',
        ])
    @endif
@endsection

@push('scripts')
    <script src="{{ url('frontend/js/intlTelInput.min.js') }}"></script>
    @include('user::frontend.profile.addresses.components.address-model-scripts')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @guest()
            $('.area_selector').on('select2:select', function(e) {
                var data = e.params.data;
                @if (request()->route()->getName() == 'frontend.checkout.index' && auth()->guest())
                    getDeliveryPriceOnStateChanged(data.id);
                @endif
            });
        @endguest

        $(".stateSelect2").select2({
            placeholder: "{{ __('user::frontend.addresses.form.states') }}",
            allowClear: true
        });
    </script>

    <script>
        $('.checkout-choose-address').on('click', function(e) {
            var addressType = $(this).attr('data-name');
            if ($(this).attr('data-click-state') == 0) {
                $(this).attr('data-click-state', 1);
                $('#checkoutAddressType').val(addressType);
            } else {
                $(this).attr('data-click-state', 0);
                $('#checkoutAddressType').val('');
            }
        });

        function onStateChanged(val) {
            $('#selectedStateFromAddress').val(val);
            getDeliveryPriceOnStateChanged($('#selectedStateFromAddress').val());
        }

        function selectPreviousAddress(addressId) {
            var selectedAddressId = $("input[name='selected_address_id']"),
                checkoutSelectedAddress = $('#checkoutSelectedAddress-' + addressId),
                thisID = '#checkoutSelectedAddress-' + addressId;

            selectedAddressId.val('');

            if (checkoutSelectedAddress.attr('data-click-state') == 0) {
                checkoutSelectedAddress.attr('data-click-state', 1);
                $(`.address-item:not(${thisID})`).attr('data-click-state', 0);
                selectedAddressId.val(addressId);
            } else {
                $('.address-item').attr('data-click-state', 0);
                selectedAddressId.val('');
            }
        }

        function checkoutSelectCompany(vendorId, companyId) {
            var thisID = '#checkVendorCompany-' + vendorId + '-' + companyId;
            var stateId = $('#selectedStateFromAddress').val();

            // START TO make radio button selected
            $(`.check-${vendorId}`).prop('checked', false);
            $('.vendor-company-' + vendorId + '-' + companyId).toggleClass("cut-radio-style");
            $(`.checkout-company-${vendorId}:not(${thisID})`).removeClass("cut-radio-style");
            // END TO make radio button selected

            if ($('#checkVendorCompany-' + vendorId + '-' + companyId).attr('data-state') == 0) {
                $('.checkout-company-' + vendorId).attr('data-state', 0);
                $('#checkVendorCompany-' + vendorId + '-' + companyId).attr('data-state', 1);
                // $(`.checkout-company:not(${thisID})`).attr('data-state', 0);
                $("input[name='vendor_company[" + vendorId + "]']").val(companyId);

                getStateDeliveryPrice(vendorId, companyId, stateId, 'checked');

            } else {
                $('.checkout-company-' + vendorId).attr('data-state', 0);
                $("input[name='vendor_company[" + vendorId + "]']").val('');
                getStateDeliveryPrice(vendorId, companyId, stateId, 'un_checked');
            }

        }

        function chooseCompanyDeliveryDay(companyId, dayCode) {

            $("input[name='shipping_company[day]']").val(dayCode);

            /*$('.day-block-company').not('.deliveryDay-' + dayCode).removeClass('active');
            $('.deliveryDay-' + dayCode).toggleClass("active");

            if ($('.deliveryDay-' + dayCode).attr('data-state-value') == 0) {
                $('.day-block-company').attr('data-state-value', 0);
                $('.deliveryDay-' + dayCode).attr('data-state-value', 1);
                $("input[name='shipping_company[day]']").val(dayCode);
            } else {
                $('.day-block-company').attr('data-state-value', 0);
                $("input[name='shipping_company[day]']").val('');
            }*/

        }

        function getStateDeliveryPrice(vendorId, companyId, stateId, type) {
            var data = {
                'vendor_id': vendorId,
                'company_id': companyId,
                'state_id': stateId,
                'type': type,
            };
            getDeliveryPrice(data, stateId, type, vendorId, companyId);
        }

        function getDeliveryPriceOnStateChanged(stateId) {
            var type = 'selected_state',
                data = {
                    'state_id': stateId,
                    'company_id': $("input[name='shipping_company[id]']").val(),
                    'type': type,
                };
            getDeliveryPrice(data, stateId, type);
        }

        function getDeliveryPrice(data, stateId, type, vendorId = null, companyId = null) {

            $('#deliveryPriceLoaderDiv').show();

            $.ajax({
                method: "GET",
                url: '{{ route('frontend.checkout.get_state_delivery_price') }}',
                data: data,
                beforeSend: function() {},
                success: function(data) {
                    var totalCompaniesDeliveryPrice = $('#totalCompaniesDeliveryPrice');

                    if (type === 'selected_state') {

                        $('.checkedCompanyInput').prop('checked', false);
                        $('.checkedCompany').removeClass("cut-radio-style");
                        $('.checkedCompany').attr('data-state', 0);
                        $(".vendor-company-input").val('');

                        var deliveryPriceRow = `<div class="d-flex mb-20 align-items-center">
                                    <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.checkout.shipping') }}</span>
                                    <span class="d-inline-block left-side"><span id="totalDeliveryPrice">${data.data.totalDeliveryPrice}</span></span>
                                </div>
                                <div class="d-flex mb-20 align-items-center">
                                    <span class="d-inline-block right-side flex-1">
                                        ${data.data.delivery_time}
                                    </span>
                                </div>`;
                        totalCompaniesDeliveryPrice.html(deliveryPriceRow);

                    } else {

                        if (data.data.price != null) {
                            var deliveryPriceRow = `<div class="d-flex mb-20 align-items-center">
                                    <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.checkout.shipping') }}</span>
                                    <span class="d-inline-block left-side"><span id="totalDeliveryPrice">${data.data.totalDeliveryPrice}</span></span>
                                </div>
                                <div class="d-flex mb-20 align-items-center">
                                    <span class="d-inline-block right-side flex-1">
                                        ${data.data.delivery_time}
                                    </span>
                                </div>`;
                            totalCompaniesDeliveryPrice.html(deliveryPriceRow);
                        }

                    }

                },
                error: function(data) {
                    $('#deliveryPriceLoaderDiv').hide();
                    // $('#btnCheckoutSaveInformation').show();
                    displayErrorsMsg(data);

                    var getJSON = $.parseJSON(data.responseText);

                    if (getJSON.data.price == null) {

                        if (type !== 'selected_state') {
                            $('#check-vendor-company-' + vendorId + '-' + companyId).prop('checked', false);
                            $('.checkout-company-' + vendorId).removeClass("cut-radio-style");
                            $("input[name='vendor_company[" + vendorId + "]']").val('');
                        }

                        var totalCompaniesDeliveryPrice = $('#totalCompaniesDeliveryPrice');
                        var deliveryPriceRow = `<div class="d-flex mb-20 align-items-center">
                                    <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.checkout.shipping') }}</span>
                                    <span class="d-inline-block left-side"><span id="totalDeliveryPrice">${getJSON.data.totalDeliveryPrice}</span></span>
                                </div>`;
                        totalCompaniesDeliveryPrice.html(deliveryPriceRow);
                    }
                },
                complete: function(data) {
                    $('#deliveryPriceLoaderDiv').hide();
                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data) {
                        $('#cartTotalAmount').html(getJSON.data.total);
                    }
                },
            });
        }

        // ####################### START (Override) Choose address type #########################
        $('.addressType-1').on('click', function(e) {
            $(this).toggleClass("cut-radio-style");
            $('.addressType-2').removeClass("cut-radio-style");
            $('.addressType-3').removeClass("cut-radio-style");
        });
        $('.addressType-2').on('click', function(e) {
            $(this).toggleClass("cut-radio-style");
            $('.addressType-1').removeClass("cut-radio-style");
            $('.addressType-3').removeClass("cut-radio-style");
        });
        $('.addressType-3').on('click', function(e) {
            $(this).toggleClass("cut-radio-style");
            $('.addressType-1').removeClass("cut-radio-style");
            $('.addressType-2').removeClass("cut-radio-style");
        });
        // ####################### END (Override) Choose address type #########################
    </script>
@endpush
