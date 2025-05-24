@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.master.favourites') )
@section('content')

    @include('user::frontend.profile.components.header' , ['title' => __('apps::frontend.master.favourites')])

    @if(count($favourites) == 0)
        <div class="inner-page">
            <div class="container">
                <div class="align-items-center text-center">
                    <div class="order-done">
                        <h1 class="margin-top-20 margin-bottom-20">{{ __('apps::frontend.master.your_wish_list_is_empty') }}</h1>
                        <p>
                            {{ __('apps::frontend.master.wish_list_description') }}
                        </p>
                        <a href="{{ route('frontend.home') }}"
                           class="btn btn-info margin-top-20">{{ __('apps::frontend.master.btn_start_shopping') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @else

        <div class="cart-items">
            @foreach($favourites as $k => $product)
                <div class="cart-item media align-items-center">
                    <div class="pro-det d-flex align-items-center">
                        <div class="pro-img">
                            <img class="img-fluid" src="{{ url($product->image) }}" alt="{{ $product->title }}">
                        </div>
                        <div class="media-body">
                            <span class="product-name">
                                <a href="{{ route('frontend.products.index', $product->slug) }}"> {{ $product->title }}</a>
                            </span>
                            <span class="price d-block">
                                {{ $product->price }} {{ __('apps::frontend.master.kwd') }}
                            </span>
                            @if($product->offer)
                                <span class="price d-block">
                                    {{ $product->offer->offer_price }} {{ __('apps::frontend.master.kwd') }}
                                    <span class="text-muted">/ Offer</span>
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="text-left">
                        <div class="generalLoaderDiv" style="display: none"
                             id="generalLoaderDiv-{{ $product->id }}">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status"
                                     style="width: 2rem; height: 2rem;">
                                    <span class="sr-only">{{__('apps::frontend.Loading')}}</span>
                                </div>
                            </div>
                        </div>
                        <form method="POST">
                            @csrf
                            <input type="hidden" id="productImage-{{ $product->id }}"
                                   value="{{ url($product->image) }}">
                            <input type="hidden" id="productTitle-{{ $product->id }}"
                                   value="{{ $product->title }}">
                            <input type="hidden" id="productQuantity-{{ $product->id }}"
                                   value="{{ getCartQuantityById($product->id) ? getCartQuantityById($product->id) + 1 : 1 }}">

                            <button type="button" class="btn add-cart"
                                    id="general_add_to_cart-{{ $product->id }}"
                                    onclick="generalAddToCart('{{ route("frontend.shopping-cart.create-or-update", [ $product->slug ]) }}', '{{ $product->id }}')">
                                <i class="ti-shopping-cart"></i>
                            </button>
                            <a href="{{ route('frontend.profile.favourites.delete', $product->id) }}" class="btn remove"><i
                                        class="ti-trash"></i></a>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @include('user::frontend.profile.components.footer')
@endsection
