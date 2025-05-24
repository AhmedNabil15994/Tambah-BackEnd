@extends('apps::frontend.layouts.master')
@section('title', __('wrapping::frontend.wrapping.gift_wrapper'))

@push('styles')
    <style>
        /* start loader style */

        .loaderDiv {
            display: none;
            margin: 15px 335px;
            justify-content: center;
        }

        #loaderCouponDiv {
            display: none;
            margin: 15px 100px;
            justify-content: center;
        }

        .loaderDiv .my-loader,
        #loaderCouponDiv .my-loader {
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

        .empty-cart-title {
            text-align: center;
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
                            <i class="ti-home"></i> {{ __('apps::frontend.nav.home_page') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.shopping-cart.index') }}">{{ __('catalog::frontend.cart.title') }}</a>
                    </li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">
                        {{ __('wrapping::frontend.wrapping.gift_wrapper') }}</li>
                </ol>
            </nav>
        </div>
        <div class="inner-page">
            <div class="row">
                <div class="col-md-8">

                    @if (count($items['gifts']) > 0 || count($items['addons']) > 0)
                        <div class="cart-inner wrap-page">

                            @if (count($items['gifts']) > 0)
                                <h3 class="slider-title">
                                    <i class='ti-gift'></i>
                                    {{ __('wrapping::frontend.wrapping.gift_wrapper') }}
                                </h3>

                                @foreach (getCartContent() as $item)
                                    <div class="card flex-row" style="background-color: unset; border: none; right: 10px;">
                                        <img class="card-img-left" src="{{ url($item->attributes->product->image) }}"
                                            style="width: 100px;" />
                                        <div class="card-body">
                                            <h4 class="card-title h5 h4-sm">
                                                @if ($item->attributes->product_type == 'variation')
                                                    {{ limitString(
                                                        generateVariantProductData(
                                                            $item->attributes->product->product,
                                                            $item->attributes->product->id,
                                                            $item->attributes->selectedOptionsValue,
                                                        )['name'],
                                                    ) }}
                                                @else
                                                    {{ limitString($item->attributes->product->title) }}
                                                @endif
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="owl-carousel giftWarp-slider mb-30">

                                        @foreach ($items['gifts'] as $k => $gift)
                                            <div class="product-grid gift-wrap" data-toggle="modal"
                                                data-target="#warp-details-{{ $gift->id }}">
                                                <div class="product-image d-flex align-items-center">
                                                    <img class="pic-1" src="{{ url($gift->image) }}">
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="title">{{ $gift->title }}</h3>
                                                    <div class="d-flex">
                                                        <span
                                                            class="price d-inline-block right-side">{{ priceWithCurrenciesCode($gift->price) }}</span>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                @endforeach

                            @endif

                            {{-- @if (count($items['cards']) > 0)
                                <h3 class="slider-title">
                                    <i class='ti-gallery'></i>
                                    {{ __('wrapping::frontend.wrapping.congratulation_card') }}
                                </h3>
                                <div class="owl-carousel giftWarp-slider choose-card mb-30">

                                    @foreach ($items['cards'] as $k => $card)
                                        <div class="product-grid gift-wrap" data-toggle="modal"
                                            data-target="#card-details-{{ $card->id }}">
                                            <div class="product-image d-flex align-items-center">
                                                <img class="pic-1" src="{{ url($card->image) }}">
                                            </div>
                                            <div class="product-content">
                                                <h3 class="title"> {{ $card->title }}</h3>
                                                <span class="price">{{ priceWithCurrenciesCode($card->price) }}</span>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            @endif --}}

                            @if (count($items['addons']) > 0)
                                <h3 class="slider-title">
                                    <i class='ti-wand'></i> {{ __('wrapping::frontend.wrapping.additions') }}
                                </h3>
                                <div class="owl-carousel giftWarp-slider choose-additions">

                                    @foreach ($items['addons'] as $k => $addons)
                                        <div class="product-grid gift-wrap" data-id="{{ $addons->id }}">
                                            <input type="hidden" name="wrapping_addon[]" value="" />
                                            <div class="product-image d-flex align-items-center">
                                                <img class="pic-1" src="{{ url($addons->image) }}">
                                            </div>
                                            <div class="product-content">
                                                <h3 class="title"> {{ $addons->title }}
                                                </h3>
                                                <span class="price">{{ priceWithCurrenciesCode($addons->price) }}</span>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            @endif

                        </div>
                    @endif

                </div>

                <div class="col-md-4">

                    @include('catalog::frontend.shopping-cart._total-side')

                </div>

            </div>
        </div>
    </div>

    {{-- @foreach ($items['gifts'] as $k => $gift)
        @include('wrapping::frontend.wrapping._gift_wrap', [
            'giftObject' => $gift,
        ])
    @endforeach

    @foreach ($items['cards'] as $k => $card)
        @include('wrapping::frontend.wrapping._card_wrap', [
            'cardObject' => $card,
        ])
    @endforeach

    @foreach ($items['addons'] as $k => $addons)
        @include('wrapping::frontend.wrapping._addons_wrap', [
            'addonsObject' => $addons,
        ])
    @endforeach --}}

@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        var productsIds = [];

        $('.choose-warp .gift-wrap').on('click', function(e) {
            productsIds = [];
        });

        function checkGiftProducts(action, giftId) {

            $(".choose-products-wrap .giftWrap-" + giftId).each(function() {

                var prdId = $(this).attr('data-id');
                var prdType = $(this).attr('data-type');

                // var classString = $(this).attr("class");
                // console.log('classes::', classString);
                // console.log('active::', $(this).hasClass("active"));
                // console.log('prdId::', prdId);
                // console.log('prdType::', prdType);

                if ($(this).hasClass("active") === true && prdId !== undefined) {
                    /*if (productsIds.indexOf(prdId) === -1) {
                        productsIds.push(prdId);
                    }*/

                    var index = productsIds.findIndex(x => x.id === prdId);
                    var item = productsIds.find(x => x.id === prdId);
                    var prd = {
                        'id': prdId,
                        'type': prdType,
                        'qty': 1
                    };

                    if (index == -1) {
                        productsIds.push(prd);
                    }

                }

                if ($(this).hasClass("active") === false && productsIds.findIndex(x => x.id === prdId) !== -1) {
                    /*var index = productsIds.indexOf(prdId);
                    productsIds.splice(index, 1);*/

                    var index = productsIds.findIndex(x => x.id === prdId);
                    productsIds.splice(index, 1);
                }

            });

            $('#btnCheckGift-' + giftId).hide();
            $('.loaderDiv').show();

            $.ajax({
                method: "POST",
                url: action,
                data: {
                    "products_ids": productsIds,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    displaySuccessMsg(data.message);
                },
                error: function(data) {
                    $('.loaderDiv').hide();
                    $('#btnCheckGift-' + giftId).show();
                    displayErrorsMsg(data);
                },
                complete: function(data) {

                    productsIds = [];
                    $('.loaderDiv').hide();
                    $('#btnCheckGift-' + giftId).show();

                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data) {
                        $('#cartTotalAmount').html(getJSON.data.total);

                        var row = `
                            <div class="d-flex mb-20 align-items-center">
                                <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.cart.gifts_total') }}</span>
                                    <span class="d-inline-block left-side">${getJSON.data.giftsTotal}</span>
                            </div>
                        `;
                        $('#giftsTotal').html(row);
                    }

                },
            });

        }

        function addOrUpdateCartCard(action, cardId) {

            var senderName = $('#card_sender_name_' + cardId).val();
            var receiverName = $('#card_receiver_name_' + cardId).val();
            var msgName = $('#card_message_' + cardId).val();

            $('#btnCardCart-' + cardId).hide();
            $('.loaderDiv').show();

            $.ajax({
                method: "POST",
                url: action,
                data: {
                    "sender_name": senderName,
                    "receiver_name": receiverName,
                    "message": msgName,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    displaySuccessMsg(data.message);

                    /*$('#card_sender_name_' + cardId).val('');
                    $('#card_receiver_name_' + cardId).val('');
                    $('#card_message_' + cardId).val('');*/
                },
                error: function(data) {
                    $('.loaderDiv').hide();
                    $('#btnCardCart-' + cardId).show();
                    displayErrorsMsg(data);
                },
                complete: function(data) {

                    $('.loaderDiv').hide();
                    $('#btnCardCart-' + cardId).show();

                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data) {
                        $('#cartTotalAmount').html(getJSON.data.total);

                        var row = `
                            <div class="d-flex mb-20 align-items-center">
                                <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.cart.cards_total') }}</span>
                                    <span class="d-inline-block left-side">${getJSON.data.cardsTotal}</span>
                            </div>
                        `;
                        $('#cardsTotal').html(row);
                    }

                },
            });

        }

        function addOrUpdateCartAddons(action, addonsId) {

            var qty = $('#qty_' + addonsId).val();

            $('#btnAddonsCart-' + addonsId).hide();
            $('.loaderDiv').show();

            $.ajax({
                method: "POST",
                url: action,
                data: {
                    "qty": qty,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    displaySuccessMsg(data.message);
                },
                error: function(data) {
                    $('.loaderDiv').hide();
                    $('#btnAddonsCart-' + addonsId).show();
                    displayErrorsMsg(data);
                },
                complete: function(data) {

                    $('.loaderDiv').hide();
                    $('#btnAddonsCart-' + addonsId).show();

                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data) {
                        $('#cartTotalAmount').html(getJSON.data.total);

                        var row = `
                            <div class="d-flex mb-20 align-items-center">
                                <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.cart.addons_total') }}</span>
                                    <span class="d-inline-block left-side">${getJSON.data.addonsTotal}</span>
                            </div>
                        `;
                        $('#addonsTotal').html(row);
                    }

                },
            });

        }
    </script>

    <script>
        $('.choose-additions .gift-wrap').on('click', function(e) {
            $(this).toggleClass("active");
            var addonId = $(this).attr('data-id');
            $(this).find("input[name='wrapping_addon']").val(addonId);
            console.log('addonId::', addonId);
        });
    </script>
@endpush
