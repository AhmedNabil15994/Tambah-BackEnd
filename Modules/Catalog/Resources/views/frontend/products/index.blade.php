@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.products.details.title'))
@section('meta_description', $product->seo_description ?? '')
@section('meta_keywords', $product->seo_keywords ?? '')
@push('styles')
    <style>
        #loaderDiv {
            display: none;
            margin: 15px auto;
            justify-content: center;
        }

        .color-backet {
            padding: 13px;
            border: 1px solid black;
            border-radius: 17px;
            background-color: #ff000000;
            cursor: pointer;
        }

        .color-selected {
            border: 2px solid white !important;
        }
    </style>
@endpush
@section('content')

    <div class="container">
        <div class="page-crumb mt-30">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"><i class="ti-home"></i>
                            {{ __('apps::frontend.master.home') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('frontend.categories.products', optional($product->categories()->first())->slug) }}">
                            {{ optional($product->categories()->first())->title }}
                        </a></li>
                    <li class="breadcrumb-item active text-muted" aria-current="page"> {{ $product->title }} </li>
                </ol>
            </nav>
        </div>
        <div class="inner-page">
            <div class="row">
                <div class="col-md-5">
                    <div class="main-image sp-wrap" id="mainProductSlider">
                        <a href="{{ asset($product->image) }}"><img src="{{ asset($product->image) }}"
                                class="img-responsive" alt="{{ $product->title }}"></a>
                        @if ($product->images->count())
                            @foreach ($product->images as $image)
                                <a href="{{ asset('uploads/products/' . $image->image) }}">
                                    <img src="{{ asset('uploads/products/' . $image->image) }}" class="img-responsive"
                                        alt="{{ $product->title }}"></a>
                            @endforeach
                        @endif

                        @if ($product->variants->count() > 0)
                            @foreach ($product->variants as $k => $varPrd)
                                <a href="{{ url($varPrd->image) }}" id="variantPrd-{{ $varPrd->id }}">
                                    <img src="{{ url($varPrd->image) }}" class="img-responsive" alt="img">
                                </a>
                            @endforeach
                        @endif

                    </div>
                </div>
                <div class="col-md-7">
                    <div class="product-detials">
                        <div class="product-head media align-items-center">
                            <div class="media-body">
                                <h1 id="pro-name">{{ $product->title }}</h1>
                            </div>
                            <div class="text-left">
                                @if (auth()->check() &&
                                    !in_array(
                                        $product->id,
                                        array_column(auth()->user()->favourites->toArray(),
                                            'id')))
                                    <form class="favourites-form" method="POST">
                                        @csrf
                                        <button type="button" class="btn favo-btn"
                                            onclick="generalAddToFavourites('{{ route('frontend.profile.favourites.store', [$product->id]) }}', '{{ $product->id }}')"
                                            id="btnAddToFavourites-{{ $product->id }}">
                                            <i class="fa fa-heart"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="product-summ-det">
                            {!! $product->description !!}
                        </div>
                        <div class="product-summ-det">
                            <p class="d-flex">
                                <span class="d-inline-block right-side">
                                    {{ __('catalog::frontend.products.sku') }}</span>
                                <span class="d-inline-block left-side">
                                    {{ !is_null($variantPrd) ? $variantPrd->sku : $product->sku }}
                                </span>
                            </p>
                            <p class="d-flex">
                                <span class="d-inline-block right-side">
                                    {{ __('catalog::frontend.products.remaining_qty') }}</span>
                                <span class="d-inline-block left-side">

                                    @if (!is_null($variantPrd))
                                        @if ($variantPrd->qty <= 3)
                                            {{ $variantPrd->qty }}
                                        @endif
                                    @else
                                        @if ($product->qty <= 3)
                                            {{ $product->qty }}
                                        @endif
                                    @endif
                                </span>
                            </p>
                        </div>

                        <div class="product-summ-det">
                            <div id="successMsg"></div>
                            <div id="responseMsg"></div>

                            @foreach ($product->options as $k => $opt)
                                <p class="d-flex">
                                    <span class="d-inline-block right-side">
                                        {{ $opt->option->title }}
                                    </span>
                                    <span class="d-inline-block left-side">
                                        <select class="form-control product-var-options"
                                            data-option-id="{{ $opt->option->id }}" id="prdOption-{{ $opt->id }}"
                                            onchange="getVariationInfo(this, '{{ $product->id }}')">
                                            <option value="">
                                                ---{{ __('catalog::frontend.products.select_option') }}---
                                            </option>
                                            @foreach ($opt->productValues->unique('option_value_id') as $i => $optValue)
                                                <option value="{{ $optValue->optionValue->id }}"
                                                    {{ in_array($opt->option->id, $selectedOptions) && in_array($optValue->optionValue->id, $selectedOptionsValue) ? 'selected' : '' }}>
                                                    {{ $optValue->optionValue->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </span>
                                </p>
                            @endforeach

                        </div>

                    </div>
                    <div class="product-summ-price">

                        <div id="addVariantPrdToCartSection">

                            <form class="form" id="productDetailsForm" action="{{ $formAction }}" method="POST"
                                data-id="{{ $formDataId }}">
                                @csrf

                                @include('catalog::frontend.products._product_attributes')

                                <div id="variantProductInfo">
                                    <span class="price have-discount">
                                        @if (!is_null($variantPrd))
                                            @if ($variantPrd->offer)
                                                <span
                                                    class="price-before">{{ priceWithCurrenciesCode($variantPrd->price) }}</span>
                                                {{ priceWithCurrenciesCode($variantPrd->offer->offer_price) }}
                                            @else
                                                {{ priceWithCurrenciesCode($variantPrd->price) }}
                                            @endif
                                        @else
                                            @if ($product->offer)
                                                @if (!is_null($product->offer->offer_price))
                                                    <span
                                                        class="price-before">{{ priceWithCurrenciesCode($product->price) }}</span>
                                                    {{ priceWithCurrenciesCode($product->offer->offer_price) }}
                                                @else
                                                    <span>{{ priceWithCurrenciesCode($product->price) }}</span>
                                                    /
                                                    <span class="percentage-discount">
                                                        {{ $product->offer->percentage . ' %' }}
                                                        {{ __('apps::frontend.master.discount') }} </span>
                                                    {{-- {{ calculateOfferAmountByPercentage($product->price, $product->offer->percentage) }} --}}
                                                @endif
                                            @else
                                                {{ priceWithCurrenciesCode($product->price) }}
                                            @endif
                                        @endif
                                    </span>

                                    @if (!is_null($variantPrd))
                                        <input type="hidden" id="productImage-{{ $variantPrd->id }}"
                                            value="{{ $variantPrd->image ? url($variantPrd->image) : '' }}">
                                        <input type="hidden" id="productTitle-{{ $variantPrd->id }}"
                                            value="{{ $productTitle }}">
                                        <input type="hidden" id="productType" value="variation">
                                        <input type="hidden" id="selectedOptions"
                                            value="{{ json_encode($selectedOptions) }}">
                                        <input type="hidden" id="selectedOptionsValue"
                                            value="{{ json_encode($selectedOptionsValue) }}">
                                    @else
                                        <input type="hidden" id="productImage-{{ $product->id }}"
                                            value="{{ url($product->image) }}">
                                        <input type="hidden" id="productTitle-{{ $product->id }}"
                                            value="{{ $product->title }}">
                                        <input type="hidden" id="productType" value="product">
                                        <input type="hidden" id="selectedOptions" value="">
                                        <input type="hidden" id="selectedOptionsValue" value="">
                                    @endif

                                    <div class="align-items-center d-flex">
                                        <h5>
                                            {{ __('catalog::frontend.products.quantity') }}
                                        </h5>
                                        <div class="quantity">
                                            <div class="buttons-added single-product-buttons-added ">
                                                <button class="sign plus single-product-plus"><i
                                                        class="fa fa-chevron-up"></i></button>
                                                <input type="text" id="prodQuantity" name="qty"
                                                    value="{{ getCartItemById($product->id) ? getCartItemById($product->id)->quantity : '1' }}"
                                                    title="Qty" class="input-text qty text" size="1">
                                                <button class="sign minus single-product-minus"><i
                                                        class="fa fa-chevron-down"></i></button>
                                            </div>
                                        </div>
                                        <button id="btnAddToCart" type="submit" class="btn btn-them main-custom-btn">
                                            <i class="ti-shopping-cart"></i>
                                            {{ __('catalog::frontend.products.add_to_cart') }}
                                        </button>
                                        <div id="loaderDiv" style="margin:0px 46px">
                                            <div class="d-flex justify-content-center">
                                                <div class="spinner-border" role="status"
                                                    style="width: 2rem; height: 2rem;">
                                                    <span class="sr-only">{{ __('apps::frontend.Loading') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @if (count($related_products))
            <div class="home-products mt-40 mb-0">
                <h3 class="slider-title">
                    {{ __('apps::frontend.Recently viewed') }}
                </h3>
                <div class="owl-carousel products-slider">
                    @foreach ($related_products as $k => $record)
                        @include('catalog::frontend.products.components.single-product', [
                            'product' => $record,
                        ])
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        var _product_attributes = `@include('catalog::frontend.products._product_attributes')`

        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var submitActor = null;
            var $form = $('#productDetailsForm');
            // var $submitActors = $form.find('button[type=submit]');

            $form.submit(function(e) {
                e.preventDefault();

                /*if (null === submitActor)
                    submitActor = $submitActors[0];*/

                var action = $(this).attr('action');
                var qty = $('#prodQuantity').val();

                var productId = $(this).attr('data-id');
                var productType = $(this).find('#productType').val();
                var productImage = $(this).find('#productImage-' + productId).val();
                var selectedOptions = $(this).find('#selectedOptions').val();
                var selectedOptionsValue = $(this).find('#selectedOptionsValue').val();

                var formData = new FormData(this);
                formData.append("request_type", 'product');
                formData.append("product_type", productType);
                formData.append("selectedOptions", selectedOptions);
                formData.append("selectedOptionsValue", selectedOptionsValue);

                if (parseInt(qty) > 0) {

                    $('#btnAddToCart').hide();
                    $('#btnAddToCartCheckout').hide();
                    $('#loaderDiv').show();

                    $.ajax({
                        method: "POST",
                        url: action,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {},
                        success: function(data) {
                            var params = {
                                'productId': productId,
                                'productImage': productImage,
                                'productTitle': data.data.productTitle,
                                'productQuantity': data.data.productQuantity,
                                'productPrice': data.data.productPrice,
                                'productDetailsRoute': data.data.productDetailsRoute,
                                'cartCount': data.data.cartCount,
                                'cartSubTotal': data.data.subTotal,
                                'product_type': productType,
                            };

                            updateHeaderCart(params);

                            if (data.data.remainingQty != null && data.data.remainingQty <= 3) {
                                var qty = `
                                    <p class="d-flex">
                                        <span class="d-inline-block right-side">{{ __('catalog::frontend.products.remaining_qty') }}</span>
                                        <span class="d-inline-block left-side">${data.data.remainingQty}</span>
                                    </p>
                                `;
                                $('#remainingQtySection').html(qty);
                            }

                            var msg = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${data.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                            $('#successMsg').html(msg);

                            if (submitActor === 'btn_add_to_cart_and_checkout')
                                window.location.replace(
                                    "{{ route('frontend.checkout.index') }}");

                            {{-- if (submitActor.name === 'btn_add_to_cart_and_checkout')
                                window.location.replace("{{ route('frontend.checkout.index') }}"); --}}
                        },
                        error: function(data) {
                            $('#loaderDiv').hide();
                            $('#btnAddToCart').show();
                            $('#btnAddToCartCheckout').show();
                            // displayErrorsMsg(data);

                            let getJSON = $.parseJSON(data.responseText);
                            let error = '';
                            if (getJSON.errors['notes'])
                                error = getJSON.errors['notes'];
                            else
                                error = getJSON.errors;

                            if (typeof error == 'object')
                                error = error[Object.keys(error)][0];

                            let msg = `
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    ${error}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                            $('#responseMsg').html(msg);
                        },
                        complete: function(data) {
                            $('#loaderDiv').hide();
                            $('#btnAddToCart').show();
                            $('#btnAddToCartCheckout').show();
                        },
                    });
                }

            });

            /*$submitActors.click(function (event) {
                submitActor = this;
            });*/

            /* $(document).on('click', '#btnAddToCartCheckout, #btnAddToCart', function(e) {
                submitActor = e.target.name;
            }); */

        });

        function callGetVariationInfo(e) {

            var optionSelected = $("option:selected", e);
            getVariationInfo(optionSelected, optionSelected.attr('data-product-id'), optionSelected.attr('data-option-id'))
        }

        function selectVariantColor(e) {

            var optionSelected = $(e);
            $('.color-backet').removeClass('color-selected');
            optionSelected.addClass('color-selected');
            getVariationInfo(optionSelected, optionSelected.attr('data-product-id'), optionSelected.attr('data-option-id'))
        }

        function getVariationInfo(e, productId) {

            var selectedOptions = [];
            var selectedOptionsValue = [];

            $('#remainingQtySection').empty();
            $('.product-var-options').each(function(i, item) {
                selectedOpt = $(this).attr('data-option-id');
                selectedOptions.push(selectedOpt);
                selectedOptionsValue.push($(this).val());
            });

            if (selectedOptions.length != 0 && !selectedOptionsValue.includes(undefined) && !selectedOptionsValue.includes(
                    "")) {
                $.ajax({
                    method: "GET",
                    url: '{{ route('frontend.get_prd_variation_info') }}',
                    data: {
                        "selectedOptions": selectedOptions,
                        "selectedOptionsValue": selectedOptionsValue,
                        "product_id": productId,
                        "_token": '{{ csrf_token() }}',
                    },
                    beforeSend: function() {},
                    success: function(data) {

                        var variantProduct = data.data.variantProduct;

                        if (variantProduct.sku) {
                            var sku = `
                <p class="d-flex">
                    <span class="d-inline-block right-side">{{ __('catalog::frontend.products.sku') }}</span>
                    <span class="d-inline-block left-side">${variantProduct.sku}</span>
                </p>
            `;
                            $('#skuSection').html(sku);
                        }

                        if (variantProduct.qty <= 3) {
                            var qty = `
                <p class="d-flex">
                    <span class="d-inline-block right-side">{{ __('catalog::frontend.products.remaining_qty') }}</span>
                    <span class="d-inline-block left-side">${variantProduct.qty}</span>
                </p>
            `;
                            $('#remainingQtySection').html(qty);
                        }

                        if (variantProduct.price) {
                            if (variantProduct.offer) {
                                var price = `
                <span class="price-before">${variantProduct.price} {{ __('apps::frontend.master.kwd') }}</span>
                ${variantProduct.offer.offer_price} {{ __('apps::frontend.master.kwd') }}
                `;
                            } else {
                                var price = `${variantProduct.price} {{ __('apps::frontend.master.kwd') }}`;
                            }
                            $('#priceSection').html(price);
                        }

                        if (variantProduct.image) {
                            var selectedImg = `
                                <div class="sp-large" style="overflow: hidden; height: auto; width: auto;">
                                    <a href="${variantProduct.image}" class="sp-current-big">
                                        <img src="${variantProduct.image}" alt="">
                                    </a>
                                </div>
                            `;
                            $('.sp-large').remove();
                            $('#mainProductSlider').prepend(selectedImg);
                        }

                    },
                    error: function(data) {
                        displayErrorsMsg(data);
                    },
                    complete: function(data) {
                        // console.log('data::', data);
                        var getJSON = $.parseJSON(data.responseText);
                        // console.log('getJSON::', getJSON);

                        $('#addVariantPrdToCartSection #productDetailsForm').html(getJSON.data.form_view);
                        $('#productDetailsForm').attr('action', getJSON.data.formAction);
                        $('#productDetailsForm').attr('data-id', getJSON.data.data_id);
                        $('#productDetailsForm').prepend(_product_attributes);
                    },
                });
            } else {
                $('#addVariantPrdToCartSection #productDetailsForm').empty();
            }

        }

        $(document).ready(function() {
            // $('.select2').select2();
        });

        function onAttributeOptionChange(event, attributeId, type) {
            let optionPrice = null;
            if (type == 'select') {
                optionPrice = $(event).find('option:selected').data('price');
            } else if (type == 'radio') {
                optionPrice = $(event).data('price');
            } else {
                optionPrice = null;
            }

            if (optionPrice != null && optionPrice != '') {
                let attributePriceLabel = `
                {{-- <b> / {{ __('catalog::frontend.products.attribute_price') }}: </b> --}}
                / {{ priceWithCurrenciesCode('${optionPrice}') }}
            `;
                $('#attributePriceLabel-' + attributeId).html(attributePriceLabel);
            } else {
                $('#attributePriceLabel-' + attributeId).empty();
            }
        }

        @if (!is_null($variantPrd) && !empty($variantPrd->image) && $variantPrd->id == request()->var)
            $(document).ready(function() {
                var img = `
            <div class="sp-large">
                <a href="{{ $variantPrd->image }}">
                    <img src="{{ $variantPrd->image }}" class="img-responsive" alt="">
                </a>
            </div>
        `;
                $('.sp-large').remove();
                $('#mainProductSlider').prepend(img);
            });
        @endif
    </script>
@endpush
