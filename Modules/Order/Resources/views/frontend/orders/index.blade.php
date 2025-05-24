@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.master.my_orders') )
@section('content')
    @include('user::frontend.profile.components.header' , ['title' => __('apps::frontend.master.my_orders')])
    <div class="cart-items">
        @if(count($orders) > 0)

            @foreach($orders as $k => $order)
                <div class="order-item">
                    <div class="d-block"><p><b>{{ __('order::frontend.orders.invoice.order_id') }}:</b> #{{ $order->id }}</p></div>
                    <div class="cart-item media align-items-center">
                        <div class="pro-det d-flex align-items-center">
                            <div class="pro-img">
                                <img class="img-fluid" src="{{ config('setting.logo') ? url(config('setting.logo')) : url('frontend/images/header-logo.png') }}">
                            </div>
                            <div class="media-body">
                                <span class="date d-block">
                                    {{ $order->created_at }}
                                </span>
                                <span class="price d-block">
                                    {{ $order->total }} {{ __('apps::frontend.master.kwd') }}D
                                </span>
                                <span class="order-status loading d-block"  style="color: {{optional($order->orderStatus)->color}}">
                                    {{ optional($order->orderStatus)->title }}
                                </span>
                            </div>
                        </div>
                        <div class="text-left">
                            <a href="{{ route('frontend.orders.invoice', $order->id) }}" class="btn btn-them main-custom-btn"><i class="ti-bag"></i>{{ __('order::frontend.orders.index.btn.details') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <b>{{ __('order::frontend.orders.invoice.no_data') }}</b>
        @endif
    </div>
    @include('user::frontend.profile.components.footer')
@endsection
