@extends('apps::frontend.layouts.master')
@section('title', __('order::frontend.orders.invoice.details_title'))
@section('content')
    <div class="container">
        <div class="invoice-page invoice-style2">
            <div class="invoice-conent">
                <h1 class="invoice-head"> {{ __('order::frontend.orders.invoice.title') }}</h1>
                <div class="invoice-head-rec">
                    <div class="row">
                        <div class="col-md-8 col-4">
                            <img src="{{ config('setting.logo') ? url(config('setting.logo')) : url('frontend/images/logo.png') }}"
                                class="img-fluid">
                        </div>
                        <div class="col-md-4 col-8">
                            <address class="norm">
                                <p class="d-flex"><b class="flex-1">
                                        {{ __('order::frontend.orders.invoice.order_id') }}
                                    </b>{{ $order->id }}</p>
                                <p class="d-flex"><b
                                        class="flex-1">{{ __('order::frontend.orders.invoice.date') }}</b>{{ $order->created_at }}
                                <p>
                                <p class="d-flex"><b class="flex-1">{{ __('order::frontend.orders.invoice.method') }}</b>
                                    @if ($order->transactions->method == 'cash')
                                        {{ __('order::frontend.orders.invoice.cash') }}
                                    @else
                                        {{ __('order::frontend.orders.invoice.online') }}
                                    @endif
                                </p>
                            </address>
                        </div>
                    </div>
                </div>

                <div class="invoice-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h1>{{ __('order::frontend.orders.invoice.client_address.receiver') }}</h1>
                            @if ($order->unknownOrderAddress)
                                <address class="norm">
                                    <p class="d-flex">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.name') }}
                                            : </b>{{ $order->unknownOrderAddress->receiver_name }}
                                    </p>
                                    <p class="d-flex">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.state') }}
                                            :</b>{{ $order->unknownOrderAddress->state->title }}
                                    <p>
                                    <p class="d-flex">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.mobile') }}
                                            :</b>{{ $order->unknownOrderAddress->receiver_mobile }}
                                    </p>
                                </address>
                            @else
                                <address class="norm">
                                    <p class="d-flex">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.state') }}
                                            : </b>{{ $order->orderAddress->state->title }}
                                    </p>
                                    <p class="d-flex">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.block') }}
                                            :</b>{{ $order->orderAddress->block }}
                                    <p>
                                    <p class="d-flex">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.building') }}
                                            :</b>{{ $order->orderAddress->building }}
                                    </p>

                                    @if (!empty($order->orderAddress->address))
                                        <p class="d-flex">
                                            <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.details') }}
                                                :</b>{{ $order->orderAddress->address }}
                                        </p>
                                    @endif

                                </address>
                            @endif
                        </div>
                        <div class="col-md-4"></div>
                    </div>

                    <table class="inventory">
                        <thead>
                            <tr>
                                <th><span> #</span></th>
                                <th><span>{{ __('order::frontend.orders.invoice.product_title') }}</span></th>
                                <th><span>{{ __('order::frontend.orders.invoice.product_qty') }}</span></th>
                                <th><span>{{ __('order::frontend.orders.invoice.product_price') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($order->orderProducts) > 0)
                                @foreach ($order->orderProducts as $key => $orderProduct)
                                    <tr class="{{ ++$key % 2 == 0 ? 'even' : '' }}">
                                        <td><span>{{ $key }}</span></td>
                                        @if (isset($orderProduct->product_variant_id) || $orderProduct->product_variant_title)
                                            <td>
                                                <span>
                                                    {!! $orderProduct->product_variant_id
                                                        ? generateVariantProductData(
                                                            $orderProduct->variant->product,
                                                            $orderProduct->product_variant_id,
                                                            $orderProduct->variant->productValues->pluck('option_value_id')->toArray(),
                                                        )['name']
                                                        : $orderProduct->product_variant_title !!}
                                                </span>
                                            </td>
                                        @else
                                            <td><span>{{ $orderProduct->product_id ? $orderProduct->product->title : $orderProduct->product_title }}</span>
                                            </td>
                                        @endif
                                        <td><span>{{ $orderProduct->qty }}</span></td>
                                        <td><span>{{ $orderProduct->price }}</span>
                                            {{--  <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>  --}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    @if ($order->orderGifts)
                        @foreach ($order->orderGifts as $k => $gift)
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="">
                                        <b class="flex-1"># {{ ++$k }}
                                            # {{ __('order::frontend.orders.invoice.gift.title') }}
                                            : </b>{{ $gift->gift->title }}
                                    </p>
                                    <p class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.gift.price') }}
                                            : </b>{{ $gift->price }} {{--  {{ __('apps::frontend.master.kwd') }}  --}}
                                    </p>
                                    <div class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.gift.products') }}: </b>
                                        <ul>
                                            @foreach ($gift->products as $i => $prd)
                                                <li>- {{ $prd['title'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    @endif

                    @if ($order->orderCards)
                        @foreach ($order->orderCards as $k => $card)
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="">
                                        <b class="flex-1"># {{ ++$k }}
                                            {{-- # {{ __('order::frontend.orders.invoice.card.title') }}
                                            : </b>{{ $card->card->title }} --}}
                                    </p>
                                    {{-- <p class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.card.price') }}
                                            : </b>{{ $card->price }}
                                    </p> --}}
                                    <p class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.card.sender_name') }}
                                            : </b>{{ $card->sender_name }}
                                    </p>
                                    <p class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.card.receiver_name') }}
                                            : </b>{{ $card->receiver_name }}
                                    </p>
                                    <p class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.card.message') }}
                                            : </b>{{ $card->message }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    @endif

                    @if ($order->orderAddons)
                        @foreach ($order->orderAddons as $k => $addOn)
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="">
                                        <b class="flex-1"># {{ ++$k }}
                                            # {{ __('order::frontend.orders.invoice.addons.title') }}
                                            : </b>{{ $addOn->addons->title }}
                                    </p>
                                    <p class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.addons.price') }}
                                            : </b>{{ $addOn->price }} {{--  {{ __('apps::frontend.master.kwd') }}  --}}
                                    </p>
                                    <p class="">
                                        <b class="flex-1">{{ __('order::frontend.orders.invoice.addons.qty') }}
                                            : </b>{{ $addOn->qty }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    @endif

                    <table class="balance">
                        <tr>
                            <th><span>{{ __('order::frontend.orders.invoice.subtotal') }}</span></th>
                            <td>
                                <span>{{ $order->subtotal }}</span>
                                {{--  <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>  --}}
                            </td>
                        </tr>
                        @if (count($order->orderGifts) > 0)
                            <tr>
                                <th><span>{{ __('order::frontend.orders.invoice.gifts_total') }}</span></th>
                                <td>
                                    <span>{{ $order->orderGifts->sum('price') }}</span>
                                    {{--  <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>  --}}
                                </td>
                            </tr>
                        @endif

                        @if (count($order->orderCards) > 0)
                            <tr>
                                <th><span>{{ __('order::frontend.orders.invoice.cards_total') }}</span></th>
                                <td>
                                    <span>{{ $order->orderCards->sum('price') }}</span>
                                    {{--  <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>  --}}
                                </td>
                            </tr>
                        @endif

                        @if (count($order->orderAddons) > 0)
                            <tr>
                                <th><span>{{ __('order::frontend.orders.invoice.addons_total') }}</span></th>
                                <td>
                                    <span>{{ $order->orderAddons->sum('price') * $order->orderAddons->sum('qty') }}</span>
                                    {{--  <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>  --}}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th><span>{{ __('order::frontend.orders.invoice.shipping') }}</span></th>
                            <td>
                                <span>{{ $order->shipping }}</span>
                                {{--  <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>  --}}
                            </td>
                        </tr>
                        <tr class="price">
                            <th><span>{{ __('order::frontend.orders.invoice.total') }}</span></th>
                            <td>
                                <span>{{ $order->total }}</span>
                                {{--  <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>  --}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="invoice-footer no-print">
                <button class="btn btn-them print-invoice main-custom-btn"><i class="ti-printer"></i>
                    {{ __('order::frontend.orders.invoice.btn.print') }}</button>
            </div>
        </div>
    </div>
@endsection
