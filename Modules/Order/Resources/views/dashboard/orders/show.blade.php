@extends('apps::dashboard.layouts.app')
@section('title', __('order::dashboard.orders.show.title'))
@section('css')
    <style>
        .btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg {

            padding: 12px 20px 10px;
        }

        .hide_admin_tag {
            display: none;
        }

        .well {
            box-shadow: none;
        }
    </style>

@stop

@section('content')
    <style type="text/css">
        .table>thead>tr>th {
            border-bottom: none !important;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0;
        }

        @media print {
            a[href]:after {
                content: none !important;
            }

            .contentPrint {
                width: 100%;
                /* font-family: tahoma; */
                font-size: 16px;
            }

            .invoice-body td.notbold {
                padding: 2px;
            }

            h2.invoice-title.uppercase {
                margin-top: 0px;
            }

            .invoice-content-2 {
                background-color: #fff;
                padding: 5px 20px;
            }

            .invoice-content-2 .invoice-cust-add,
            .invoice-content-2 .invoice-head {
                margin-bottom: 0px;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }

        }
    </style>

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ url(route('dashboard.' . $flag . '.index')) }}">
                            {{ __('order::dashboard.orders.flags.' . $flag) }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('order::dashboard.orders.show.title') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <div class="col-md-8">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered" style="    border: 1px solid #e7ecf1!important">
                        <div class="portlet-title no-print">
                            <div class="caption font-red-sunglo">
                                <i class="font-red-sunglo fa fa-file-text-o"></i>
                                <span class="caption-subject bold uppercase">
                                    {{ __('order::dashboard.orders.show.invoice_customer') }}
                                    (
                                    {{ $order->from_cashier == 1 ? __('order::dashboard.orders.show.order_from_cashier') : __('order::dashboard.orders.show.order_from_app') }}
                                    )
                                </span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row invoice-head contentPrint">
                                <div class="col-md-12 col-xs-12" style="    margin-bottom: 30px;">
                                    <div class="invoice-logo row">
                                        {{-- <center> --}}

                                        <span class="header">
                                            <h3 class="uppercase">#{{ $order['id'] }}</h6>
                                        </span>
                                        <span class="image">
                                            <img src="{{ url(config('setting.logo')) }}" alt="" />
                                        </span>
                                        @if ($order->orderStatus)
                                            <span class="order_Status">
                                                <span
                                                    style="background-color: {{ json_decode($order->orderStatus->color_label)->value }};padding: 2px 14px; color: #000000; border-radius: 25px; float: none;">
                                                    {{ $order->orderStatus->title }}
                                                </span>
                                            </span>
                                        @endif

                                        {{-- </center> --}}
                                    </div>
                                </div>

                                @if ($order->orderAddress != null)
                                    <div class="col-md-6 col-xs-6">
                                        <div class="note well">
                                            @if (!is_null($order->orderAddress->state))
                                                <span class="bold uppercase">
                                                    {{ $order->orderAddress->state->city->title }}
                                                    /
                                                    {{ $order->orderAddress->state->title }}
                                                </span>
                                            @endif
                                            <br />

                                            @if ($order->orderAddress->governorate)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.governorate') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->governorate }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->block)
                                                <span class="bold">{{ __('order::dashboard.orders.show.address.block') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->block }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->district)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.district') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->district }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->street)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.street') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->street }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->building)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.building') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->building }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->floor)
                                                <span class="bold">{{ __('order::dashboard.orders.show.address.floor') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->floor }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->flat)
                                                <span class="bold">{{ __('order::dashboard.orders.show.address.flat') }}
                                                    : </span>
                                                {{ $order->orderAddress->flat }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->address_title)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.address_title') }}
                                                    : </span>
                                                {{ $order->orderAddress->address_title }}
                                                <br />
                                            @endif

                                            <span class="bold">{{ __('order::dashboard.orders.show.address.details') }}
                                                :
                                            </span>
                                            {{ $order->orderAddress->address ?? '---' }}
                                        </div>
                                    </div>
                                @endif

                                @php
                                    $addressDetails = getOrderAddressDetails($order);
                                @endphp
                                <div class="col-md-6 col-xs-6">
                                    <div class="note well">
                                        <div class="company-address">
                                            <h6 class="uppercase">#{{ $order['id'] }}</h6>
                                            <h6 class="uppercase">
                                                {{ date('Y-m-d / H:i:s', strtotime($order->created_at)) }}
                                            </h6>
                                            <span class="bold">
                                                {{ __('order::dashboard.orders.show.user.username') }} :
                                            </span>
                                            {{ $addressDetails['name'] ?? '---' }}
                                            <br />
                                            <span class="bold">
                                                {{ __('order::dashboard.orders.show.user.mobile') }} :
                                            </span>
                                            {{ $addressDetails['mobile'] ?? '---' }}
                                            <br />
                                            <span class="bold">
                                                {{ __('transaction::dashboard.orders.show.transaction.method') }} :
                                            </span>
                                            {{ ucfirst(optional($order->transactions)->method) }}
                                            <br />

                                            @php $ordrtAttrs = $order->attributes()->get(['name','value','type']); @endphp
                                            @foreach ($ordrtAttrs as $attr)
                                                <span class="bold">
                                                    {{ $attr->name }} :
                                                </span>
                                                @if ($attr->type == 'file')
                                                    <a href="{{ asset($attr->value) }}"><i class="fa fa-file"></i></a>
                                                @else
                                                    {{ $attr->value }}
                                                @endif
                                                <br />
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 table-responsive">
                                    <br>
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th class="invoice-title uppercase text-left">
                                                {{ __('order::dashboard.orders.show.items.title') }}
                                            </th>
                                            <th class="invoice-title uppercase text-left">
                                                {{ __('order::dashboard.orders.show.items.price') }}
                                            </th>
                                            <th class="invoice-title uppercase text-left">
                                                {{ __('order::dashboard.orders.show.items.qty') }}
                                            </th>
                                            <th class="invoice-title uppercase text-left">
                                                {{ __('order::dashboard.orders.show.items.total') }}
                                            </th>
                                            @if ($order->orderCoupons && !empty($order->orderCoupons->products))
                                                <th class="invoice-title uppercase text-left">
                                                    {{ __('order::dashboard.orders.show.items.coupon_discount') }}
                                                </th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $customSubTotal = 0;
                                        @endphp
                                        @foreach ($order->allProducts as $product)
                                            @php
                                                $discount_details = $order->GetDiscountPerProduct($product);
                                                $discount_value = 0;
                                                $discount_percent = 0;
                                                if( isset($discount_details['value']) && $discount_details['value'] > 0 )
                                                {
                                                    $discount_value = $discount_details['value'];
                                                }

                                                if( isset($discount_details['percent']) && $discount_details['percent'] > 0 )
                                                {
                                                    $discount_percent = $discount_details['percent'];
                                                }
                                            @endphp
                                            @if (isset($product->product_variant_id) || $product->product_variant_title)
                                                <tr>
                                                    <td class="text-left sbold">
                                                        @if ($product->product_variant_id)
                                                            <a
                                                                href="{{ route('dashboard.products.edit', $product->variant->product->id) }}">
                                                                <img class="product_photo"
                                                                     src="{{ asset($product->variant->image) }}"
                                                                     width="39px" style="margin: 0px 2px;">
                                                                {{ generateVariantProductData($product->variant->product, $product->product_variant_id, $product->variant->productValues->pluck('option_value_id')->toArray())['name'] }}
                                                            </a>
                                                        @else
                                                            {{ $product->product_variant_title }}
                                                        @endif

                                                        @if ($product->variant->product->store_location)
                                                            <h5>
                                                                <b>#
                                                                    {{ __('order::dashboard.orders.show.items.store_location') }}</b>
                                                                : {{ $product->variant->product->store_location }}
                                                            </h5>
                                                        @endif

                                                        @if ($product->notes)
                                                            <h5>
                                                                <b>#
                                                                    {{ __('order::dashboard.orders.show.items.notes') }}</b>
                                                                : {{ $product->notes }}
                                                            </h5>
                                                        @endif

                                                        @if ($product->productAttributes->count() > 0)
                                                            @foreach ($product->productAttributes as $k => $productAttribute)
                                                                <h5>
                                                                    <b>#
                                                                        {{ $productAttribute->attribute->translate('name', locale()) }}</b>
                                                                    :
                                                                    @if ($productAttribute->attribute->type == 'radio' || $productAttribute->attribute->type == 'drop_down')
                                                                        {{ $productAttribute->attribute->options->where('id', $productAttribute->value)->first()->value }}
                                                                    @elseif($productAttribute->attribute->type == 'boolean')
                                                                        {{ $productAttribute->value == 'on' ? __('apps::frontend.general.yes') : __('apps::frontend.general.no') }}
                                                                    @elseif($productAttribute->attribute->type == 'file')
                                                                        <img src="{{ url($productAttribute->value) }}"
                                                                             class="img-thumbnail img-responsive img-preview"
                                                                             style="height: 150px; width: 250px;"
                                                                             alt="attribute image">
                                                                    @elseif($productAttribute->attribute->type == 'url')
                                                                        <a
                                                                            href="{{ $productAttribute->value }}">{{ $productAttribute->value }}</a>
                                                                    @elseif($productAttribute->attribute->type == 'email')
                                                                        <a
                                                                            href="mailto:{{ $productAttribute->value }}">{{ $productAttribute->value }}</a>
                                                                    @else
                                                                        {{ $productAttribute->value }}
                                                                    @endif

                                                                    @if ($productAttribute->price)
                                                                        <b> #
                                                                            {{ __('catalog::frontend.products.attribute_price') }}:
                                                                        </b>
                                                                        <span>{{ priceWithCurrenciesCode($productAttribute->price) }}</span>
                                                                    @endif

                                                                </h5>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td class="text-left sbold">
                                                        {{ $product->c }}
                                                    </td>
                                                    <td class="text-left sbold">
                                                        {{ $product->qty }}
                                                    </td>
                                                    <td class="text-left sbold">
                                                        {{ $product->total }}
                                                    </td>
                                                    @if (
                                                        $order->orderCoupons &&
                                                            !empty($order->orderCoupons->products) &&
                                                            in_array($product->variant->product->id, $order->orderCoupons->products ?? []))
                                                        <td class="text-left sbold">
                                                            @if ($order->orderCoupons->discount_type == 'value')
                                                                <span>{{ $order->orderCoupons->discount_value * $product->qty }}
                                                                    {{ __('apps::frontend.master.kwd') }}</span>
                                                            @else
                                                                <span>{{ round($order->orderCoupons->discount_percentage, 1) }}
                                                                        %</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @else
                                                @php
                                                    if ($order->orderCoupons && empty($order->orderCoupons->products)) {
                                                        if (!empty($product->add_ons_option_ids)) {
                                                            $customSubTotal += (floatval(json_decode($product->add_ons_option_ids)->total_amount) + floatval($product->sale_price)) * intval($product->qty);
                                                        } else {
                                                            $customSubTotal += $product->total;
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="notbold text-left">

                                                        @if ($product->product_id)
                                                            <a
                                                                href="{{ route('dashboard.products.edit', $product->product->id) }}">
                                                                <img class="product_photo"
                                                                     src="{{ asset($product->product->image) }}"
                                                                     width="39px" style="margin: 0px 2px;">
                                                                <span>
                                                                        {{ $product->product->title }}

                                                                        ({{ $product->product->sku }})
                                                                    </span>
                                                            </a>
                                                        @else
                                                            {{ $product->product_title }}
                                                        @endif

                                                        @if ($product->product->store_location)
                                                            <h5>
                                                                <b>#
                                                                    {{ __('order::dashboard.orders.show.items.store_location') }}</b>
                                                                : {{ $product->product->store_location }}
                                                            </h5>
                                                        @endif

                                                        @if ($product->notes)
                                                            <h5>
                                                                <b>#
                                                                    {{ __('order::dashboard.orders.show.items.notes') }}</b>
                                                                : {{ $product->notes }}
                                                            </h5>
                                                        @endif

                                                        @if ($product->productAttributes->count() > 0)
                                                            @foreach ($product->productAttributes as $k => $productAttribute)
                                                                <h5>
                                                                    <b>#
                                                                        {{ $productAttribute->attribute->translate('name', locale()) }}</b>
                                                                    :
                                                                    @if ($productAttribute->attribute->type == 'radio' || $productAttribute->attribute->type == 'drop_down')
                                                                        {{ $productAttribute->attribute->options->where('id', $productAttribute->value)->first()->value }}
                                                                    @elseif($productAttribute->attribute->type == 'boolean')
                                                                        {{ $productAttribute->value == 'on' ? __('apps::frontend.general.yes') : __('apps::frontend.general.no') }}
                                                                    @elseif($productAttribute->attribute->type == 'file')
                                                                        <img src="{{ url($productAttribute->value) }}"
                                                                             class="img-thumbnail img-responsive img-preview"
                                                                             style="height: 150px; width: 250px;"
                                                                             alt="attribute image">
                                                                    @elseif($productAttribute->attribute->type == 'url')
                                                                        <a
                                                                            href="{{ $productAttribute->value }}">{{ $productAttribute->value }}</a>
                                                                    @elseif($productAttribute->attribute->type == 'email')
                                                                        <a
                                                                            href="mailto:{{ $productAttribute->value }}">{{ $productAttribute->value }}</a>
                                                                    @else
                                                                        {{ $productAttribute->value }}
                                                                    @endif

                                                                    @if ($productAttribute->price)
                                                                        <b> #
                                                                            {{ __('catalog::frontend.products.attribute_price') }}:
                                                                        </b>
                                                                        <span>{{ priceWithCurrenciesCode($productAttribute->price) }}</span>
                                                                    @endif

                                                                </h5>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td class="text-left notbold">
                                                        {{-- {{ $product->sale_price }} --}}
                                                        @if( isset($discount_details['html_price']) )
                                                            {!! $discount_details['html_price'] !!}
                                                        @else
                                                            {{ $product->sale_price }}
                                                        @endif
                                                    </td>
                                                    <td class="text-left notbold"> {{ $product->qty }}
                                                    </td>
                                                    <td class="text-left notbold">
                                                        @php
                                                            $sale_price = $product->sale_price;
                                                            if( isset($discount_details['sale_price']) )
                                                            {
                                                                $sale_price = $discount_details['sale_price'];
                                                            }
                                                        @endphp
                                                        @if (!empty($product->add_ons_option_ids))
                                                            {{ (floatval(json_decode($product->add_ons_option_ids)->total_amount) + floatval($sale_price)) * intval($product->qty) }}
                                                        @else
                                                            {{ $sale_price * $product->qty }}
                                                        @endif
                                                    </td>
                                                    @if (
                                                        $order->orderCoupons &&
                                                            !empty($order->orderCoupons->products) &&
                                                            in_array($product->product->id, $order->orderCoupons->products ?? []))
                                                        <td class="text-left sbold">
                                                            @if ($order->orderCoupons->discount_type == 'value')
                                                                <span>{{ $order->orderCoupons->discount_value }}
                                                                    {{ __('apps::frontend.master.kwd') }}</span>
                                                            @else
                                                                <span>{{ round($order->orderCoupons->discount_percentage, 1) }}
                                                                        %</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>

                                                @if (!is_null($product->add_ons_option_ids) && !empty($product->add_ons_option_ids))
                                                    @foreach (json_decode($product->add_ons_option_ids)->data as $key => $addons)
                                                        @foreach ($addons->options as $k => $option)
                                                            <tr>
                                                                <td>
                                                                    <b>#
                                                                        {{ getAddonsTitle($addons->id) }}</b>
                                                                    - {{ getAddonsOptionTitle($option) }}
                                                                </td>
                                                                <td class="text-left notbold">
                                                                    {{ getOrderAddonsOptionPrice(json_decode($product->add_ons_option_ids), $option) }}
                                                                </td>
                                                                <td class="text-left notbold">1</td>
                                                                <td class="text-left notbold">
                                                                    {{ getOrderAddonsOptionPrice(json_decode($product->add_ons_option_ids), $option) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endforeach
                                        </tbody>
                                        <thead>
                                        <tr>
                                            <th class="text-left bold">
                                                {{ __('order::dashboard.orders.show.order.subtotal') }}
                                            </th>
                                            <th></th>
                                            <th></th>
                                            @if ($order->orderCoupons && empty($order->orderCoupons->products))
                                                <th class="text-left bold"> {{ $customSubTotal }} </th>
                                            @else
                                                <th class="text-left bold"> {{ $order->subtotal }} </th>
                                            @endif
                                        </tr>
                                        @if ($order->orderCoupons && empty($order->orderCoupons->products))
                                            <tr style="border-top: 2px solid #d6dae0;">
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.coupon_discount') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold">
                                                    @if ($order->orderCoupons->discount_type == 'value')
                                                        {{ $order->orderCoupons->discount_value }}
                                                    @else
                                                        {{ $order->orderCoupons->discount_percentage }} % <br />
                                                        {{-- <small class="text-info">( {{($order->subtotal * $order->orderCoupons->discount_percentage) / 100 }} ) KW</small> --}}
                                                    @endif
                                                </th>
                                            </tr>
                                        @endif
                                        <tr
                                            style="{{ is_null($order->orderCoupons) || !empty($order->orderCoupons->products) ? 'border-top: 2px solid #d6dae0;' : '' }}">
                                            <th class="text-left bold">
                                                {{ __('order::dashboard.orders.show.order.shipping') }}
                                            </th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-left bold">{{ $order->shipping }}</th>
                                        </tr>

                                        @if ($order->orderGifts->count() > 0)
                                            <tr>
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.gifts_total') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold">{{ $order->orderGifts->sum('price') }}
                                                </th>
                                            </tr>
                                        @endif

                                        @if ($order->orderAddons->count() > 0)
                                            <tr>
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.addons_total') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold">{{ $order->orderAddons->sum('price') }}
                                                </th>
                                            </tr>
                                        @endif

                                        @if ($order->orderCards->count() > 0)
                                            <tr>
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.cards_total') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold">{{ $order->orderCards->sum('price') }}
                                                </th>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th class="text-left bold">
                                                {{ __('order::dashboard.orders.show.order.total') }}
                                            </th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-left bold">{{ $order->total }}</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div style="margin: 10px;">
                                            <b>{{ __('order::dashboard.orders.show.notes') }}
                                                : </b>
                                            <span>{{ $order->notes ?? '---' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div style="margin: 10px;">
                                            <b>
                                                <i class="font-red-sunglo fa fa-file-text-o"></i>
                                                {{ __('order::dashboard.orders.show.admin_note') }}
                                                :

                                                <i class="fa fa-edit show_admin_tag no-print"
                                                   style="color:#32c5d2; cursor: pointer;"
                                                   onclick="toggleAdminTag()"></i>
                                                <span class="hide_admin_tag no-print">
                                                    <i class="fa fa-close" style="color:#fa5661; cursor: pointer;"
                                                       onclick="toggleAdminTag()"></i>
                                                </span>
                                            </b>
                                            <span id="admin_note">{{ $order->admin_note ?? '---' }}</span>
                                            <div style="padding: 20px 74px;" class="hide_admin_tag no-print">

                                                {!! Form::open([
                                                    'url' => route('dashboard.orders.admin.note', $order->id),
                                                    'role' => 'form',
                                                    'page' => 'form',
                                                    'class' => 'form-horizontal form-row-seperated updateForm',
                                                    'method' => 'PUT',
                                                    'files' => true,
                                                ]) !!}

                                                <div class="form-group">
                                                    <textarea class="form-control" name="admin_note" rows="8" cols="6">{{ $order->admin_note }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" class="submit btn green btn-lg">
                                                        {{ __('apps::dashboard.general.edit_btn') }}
                                                    </button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    @if (isset($order->delivery_time['date']) && !empty($order->delivery_time['date']))
                                        <div class="col-md-6 col-xs-12">
                                            <div style="margin: 10px;">
                                                <b>{{ __('order::dashboard.orders.show.delivery_time.day') }}
                                                    : </b>
                                                <span>{{ $order->delivery_time['date'] ?? '---' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($order->delivery_time['time_from']) &&
                                            isset($order->delivery_time['time_to']) &&
                                            !is_null($order->delivery_time['time_from']) &&
                                            !is_null($order->delivery_time['time_to']))
                                        <div class="col-md-6 col-xs-12">
                                            <div style="margin: 10px;">
                                                <b>{{ __('order::dashboard.orders.show.delivery_time.time') }}
                                                    : </b>
                                                <span>From:
                                                    {{ $order->delivery_time['time_from'] }}</span>
                                                <span>To: {{ $order->delivery_time['time_to'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if (count($order->orderGifts) > 0)
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12 table-responsive">
                                            <h4>{{ __('order::dashboard.orders.show.items.gifts.index') }}</h4>
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.gifts.title') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.gifts.price') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.gifts.products') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.gifts.gift_card_content') }}
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($order->orderGifts as $k => $item)
                                                    <tr>
                                                        <td>{{ ++$k }}</td>
                                                        <td class="text-center sbold">
                                                            {{ !is_null($item->gift) ? $item->gift->title : '---' }}
                                                        </td>
                                                        <td class="text-center sbold"> {{ $item->price ?? '---' }}
                                                        </td>
                                                        <td class="text-center sbold">
                                                            <ul>
                                                                @foreach ($item->products as $i => $prd)
                                                                    <li>{{ $prd['title'] }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td class="text-center sbold">
                                                            @if ($item->gift_card_content)
                                                                <ul>
                                                                    <li><b>{{ __('order::dashboard.orders.show.items.gifts.gift_card.gift_card_from') }}
                                                                            :
                                                                        </b>{{ $item->gift_card_content['gift_card_from'] }}
                                                                    </li>
                                                                    <li><b>{{ __('order::dashboard.orders.show.items.gifts.gift_card.gift_card_to') }}
                                                                            :
                                                                        </b>{{ $item->gift_card_content['gift_card_to'] }}
                                                                    </li>
                                                                    <li><b>{{ __('order::dashboard.orders.show.items.gifts.gift_card.gift_card_message') }}
                                                                            :
                                                                        </b>{{ $item->gift_card_content['gift_card_message'] }}
                                                                    </li>
                                                                </ul>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if (count($order->orderCards) > 0)
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12 table-responsive">
                                            <h4>{{ __('order::dashboard.orders.show.items.cards.index') }}</h4>
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    {{--  <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.cards.title') }}
                                                    </th>  --}}
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.cards.price') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.cards.sender_name') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.cards.receiver_name') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.cards.message') }}
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($order->orderCards as $k => $item)
                                                    <tr>
                                                        <td>{{ ++$k }}</td>
                                                        {{--  <td class="text-center sbold">
                                                            {{ $item->card->title }}
                                                        </td>  --}}
                                                        <td class="text-center sbold"> {{ $item->price }}
                                                        </td>
                                                        <td class="text-center sbold">
                                                            {{ $item->sender_name }}
                                                        </td>
                                                        <td class="text-center sbold">
                                                            {{ $item->receiver_name }} </td>
                                                        <td class="text-center sbold"> {{ $item->message }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if (count($order->orderAddons) > 0)
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12 table-responsive">
                                            <h4>{{ __('order::dashboard.orders.show.items.addons.index') }}</h4>
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.addons.title') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.addons.price') }}
                                                    </th>
                                                    <th class="invoice-title uppercase text-center">
                                                        {{ __('order::dashboard.orders.show.items.addons.qty') }}
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($order->orderAddons as $k => $item)
                                                    <tr>
                                                        <td>{{ ++$k }}</td>
                                                        <td class="text-center sbold">
                                                            {{ $item->addons->title }}
                                                        </td>
                                                        <td class="text-center sbold"> {{ $item->price }}
                                                        </td>
                                                        <td class="text-center sbold"> {{ $item->qty }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 no-print">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered" style="border: 1px solid #e7ecf1!important">

                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-xs-2">
                                    <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                       onclick="javascript:window.print();">
                                        {{ __('apps::dashboard.general.print_btn') }}
                                        <i class="fa fa-print"></i>
                                    </a>
                                </div>

                                @if (optional($order->paymentStatus)->flag == 'pending' ||
                                        (optional($order->paymentStatus)->flag == 'cash' && is_null($order->payment_confirmed_at)) ||
                                        (optional($order->paymentType)->flag == 'cash' && is_null($order->payment_confirmed_at)))

                                    {{-- @permission('confirm_payment_order')
                                        <a class="btn btn-lg btn-success hidden-print margin-bottom-5" href="#confirm_payment"
                                            data-toggle="modal" style="margin: 0px 77px;">
                                            {{ __('order::dashboard.orders.show.confirm_payment') }}
                                        </a>
                                        <div class="modal fade" id="confirm_payment" tabindex="-1" role="basic"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"></button>

                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('order::dashboard.orders.show.confirm_payment_dec') }}

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark btn-outline"
                                                            data-dismiss="modal">

                                                            {{ __('order::dashboard.orders.show.close') }}
                                                        </button>
                                                        <a href={{ route('dashboard.orders.confirm.payment', $order->id) }}
                                                            class="btn green">
                                                            {{ __('order::dashboard.orders.show.confirm_payment') }}
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    @endpermission --}}
                                @elseif(in_array(optional($order->paymentStatus)->flag, ['success']) ||
                                        (optional($order->paymentStatus)->flag == 'cash' && !is_null($order->payment_confirmed_at)) ||
                                        (optional($order->paymentType)->flag == 'cash' && !is_null($order->payment_confirmed_at)))
                                    @permission('refund_order')
                                    @if (!$order->is_refund)
                                        <div class="col-xs-2">
                                            <a class="btn btn-lg btn-warning hidden-print margin-bottom-5"
                                               data-toggle="modal" href="#refund_modal" style="margin: 0px 77px;">
                                                {{ __('order::dashboard.orders.show.refund_btn') }}
                                            </a>
                                        </div>

                                        <div class="modal fade bs-modal-lg" id="refund_modal" tabindex="-1"
                                             role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true"></button>
                                                        <h4 class="modal-title">
                                                            {{ __('order::dashboard.orders.show.refund_btn') }}</h4>
                                                    </div>
                                                    {!! Form::open([
                                                        'url' => route('dashboard.orders.refund', $order->id),
                                                        'role' => 'form',
                                                        'page' => 'form',
                                                        'class' => 'form-horizontal form-row-seperated updateForm',
                                                        'method' => 'PUT',
                                                        'files' => true,
                                                    ]) !!}

                                                    <div class="modal-body">

                                                        <table class="table table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th class="invoice-title uppercase text-left">
                                                                    {{ __('order::dashboard.orders.show.items.title') }}
                                                                </th>
                                                                <th class="invoice-title uppercase text-left">
                                                                    {{ __('order::dashboard.orders.show.items.price') }}
                                                                </th>
                                                                <th class="invoice-title uppercase text-left">
                                                                    {{ __('order::dashboard.orders.show.items.qty') }}
                                                                </th>
                                                                <th class="invoice-title uppercase text-left"
                                                                    style="width: 116px;">
                                                                    {{ __('order::dashboard.orders.show.items.refund_qty') }}
                                                                </th>
                                                                <th class="invoice-title uppercase text-left"
                                                                    style="width: 116px;">
                                                                    {{ __('order::dashboard.orders.show.items.remain') }}
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php
                                                                $customSubTotal = 0;
                                                                $totalQty = 0;
                                                            @endphp
                                                            @foreach ($order->allProducts as $product)
                                                                @if (isset($product->product_variant_id) || $product->product_variant_title)
                                                                    <tr>
                                                                        <td class="text-left sbold">
                                                                            @if ($product->product_variant_id)
                                                                                <a
                                                                                    href="{{ route('dashboard.products.edit', $product->variant->product->id) }}">
                                                                                    {{ generateVariantProductData($product->variant->product, $product->product_variant_id, $product->variant->productValues->pluck('option_value_id')->toArray())['name'] }}
                                                                                </a>
                                                                            @else
                                                                                {{ $product->product_variant_title }}
                                                                            @endif

                                                                            @if ($product->notes)
                                                                                <h5>
                                                                                    <b>#
                                                                                        {{ __('order::dashboard.orders.show.items.notes') }}</b>
                                                                                    : {{ $product->notes }}
                                                                                </h5>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-left sbold">
                                                                            {{ $product->sale_price }}
                                                                        </td>
                                                                        <td class="text-left sbold">
                                                                            {{ $product->qty }} </td>
                                                                        <td class="text-left sbold">
                                                                            <input type="number"
                                                                                   max="{{ $product->qty }}"
                                                                                   min="0" value="0"
                                                                                   class="form-control refund_qty"
                                                                                   data-id="{{ $product->id }}"
                                                                                   data-fexedqty="{{ $product->qty }}">
                                                                        </td>
                                                                        <td class="text-left sbold">
                                                                                    <span
                                                                                        id="remaining_qty_{{ $product->id }}">{{ $product->qty }}</span>
                                                                            <input type="hidden"
                                                                                   name="items[{{ $product->id }}][qty]"
                                                                                   value="{{ $product->qty }}"
                                                                                   id="remaining_qty_input_{{ $product->id }}">
                                                                            <input type="hidden"
                                                                                   name="items[{{ $product->id }}][type]"
                                                                                   value="variant">
                                                                        </td>
                                                                    </tr>
                                                                @else
                                                                    @php
                                                                        if ($order->orderCoupons && empty($order->orderCoupons->products)) {
                                                                            if (!empty($product->add_ons_option_ids)) {
                                                                                $customSubTotal += (floatval(json_decode($product->add_ons_option_ids)->total_amount) + floatval($product->sale_price)) * intval($product->qty);
                                                                            } else {
                                                                                $customSubTotal += $product->total;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="notbold text-left">

                                                                            @if ($product->product_id)
                                                                                <a
                                                                                    href="{{ route('dashboard.products.edit', $product->product->id) }}">
                                                                                    {{ $product->product->title }}
                                                                                    <br>
                                                                                    {{ $product->product->sku }}
                                                                                </a>
                                                                            @else
                                                                                {{ $product->product_title }}
                                                                            @endif


                                                                            @if ($product->notes)
                                                                                <h5>
                                                                                    <b>#
                                                                                        {{ __('order::dashboard.orders.show.items.notes') }}</b>
                                                                                    : {{ $product->notes }}
                                                                                </h5>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-left notbold">
                                                                            {{ $product->sale_price }} </td>
                                                                        <td class="text-left notbold">
                                                                            {{ $product->qty }}</td>
                                                                        <td class="text-left sbold">
                                                                            <input type="number"
                                                                                   max="{{ $product->qty }}"
                                                                                   min="0" value="0"
                                                                                   class="form-control refund_qty"
                                                                                   data-id="{{ $product->id }}"
                                                                                   data-fexedqty="{{ $product->qty }}">
                                                                        </td>
                                                                        <td class="text-left sbold">
                                                                                    <span
                                                                                        id="remaining_qty_{{ $product->id }}">{{ $product->qty }}</span>
                                                                            <input type="hidden"
                                                                                   name="items[{{ $product->id }}][qty]"
                                                                                   value="{{ $product->qty }}"
                                                                                   id="remaining_qty_input_{{ $product->id }}">
                                                                            <input type="hidden"
                                                                                   name="items[{{ $product->id }}][type]"
                                                                                   value="product">
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        <div class="row">
                                                            <div class="form-group col-md-9 col-xs-6">
                                                            </div>
                                                            <div class="form-group col-md-3 col-xs-6"
                                                                 style="text-align:center">
                                                                <label>
                                                                    {{ __('order::dashboard.orders.show.return') }} <span
                                                                        id="return_qty">{{ $totalQty }}</span>
                                                                    {{ __('order::dashboard.orders.show.product') }}
                                                                </label>
                                                                <input type="checkbox" checked style="margin: 0px 10px;"
                                                                       name="increment_stock" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="row" style="border-bottom: 1px solid #9e9e9e54;">
                                                            <div class="form-group col-md-6 col-xs-2">
                                                            </div>
                                                            <div class="form-group col-md-3 col-xs-5"
                                                                 style="text-align:center">
                                                                {{ __('order::dashboard.orders.show.order.shipping') }}
                                                            </div>
                                                            <div class="form-group col-md-3 col-xs-5"
                                                                 style="text-align:center">
                                                                {{ $order->shipping }}
                                                            </div>

                                                            @if ($order->orderCoupons)
                                                                <div class="form-group col-md-6 col-xs-2">
                                                                </div>
                                                                <div class="form-group col-md-3 col-xs-5"
                                                                     style="text-align:center">
                                                                    {{ __('order::dashboard.orders.show.order.coupon') }}
                                                                </div>
                                                                <div class="form-group col-md-3 col-xs-5"
                                                                     style="text-align:center">
                                                                    @if ($order->orderCoupons->discount_type == 'value')
                                                                        {{ $order->orderCoupons->discount_value }}
                                                                    @else
                                                                        {{ $order->orderCoupons->discount_percentage }} %
                                                                    @endif
                                                                </div>
                                                            @endif

                                                            <div class="form-group col-md-6 col-xs-2">
                                                            </div>
                                                            <div class="form-group col-md-3 col-xs-5"
                                                                 style="text-align:center">
                                                                {{ __('order::dashboard.orders.show.order.total') }}
                                                            </div>
                                                            <div class="form-group col-md-3 col-xs-5"
                                                                 style="text-align:center">
                                                                {{ $order->total }}
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin:17px 0px">
                                                            <div class="form-group col-md-9 col-xs-6">


                                                                    <span class="bold">
                                                                        {{ __('transaction::dashboard.orders.show.transaction.method') }}
                                                                        :
                                                                    </span>
                                                                {{ ucfirst($order->transactions->method) }}
                                                            </div>
                                                            <div class="form-group col-md-3 col-xs-6"
                                                                 style="text-align:center">
                                                                <input type="number" value="{{ $order->total }}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="form-actions">
                                                            @include('apps::dashboard.layouts._ajax-msg')
                                                            <div>
                                                                <button type="button" class="btn btn-warning btn-outline"
                                                                        data-dismiss="modal">
                                                                    {{ __('order::dashboard.orders.show.close') }}
                                                                </button>
                                                                <button type="submit"
                                                                        class="btn green submit">{{ __('order::dashboard.orders.show.refund') }}</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    @endif
                                    @endpermission
                                @endif
                            </div>
                        </div>
                    </div>

                    @permission('show_order_change_status_tab')
                    <div class="portlet light bordered" style="border: 1px solid #e7ecf1!important">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="fa fa-shopping-cart font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">
                                        {{ __('order::dashboard.orders.show.change_order_status') }}
                                    </span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="no-print">
                                <div class="row">
                                    <div class="col-md-12">

                                        <form id="updateForm" method="POST"
                                              action="{{ url(route('dashboard.orders.update', $order['id'])) }}"
                                              enctype="multipart/form-data" class="horizontal-form">
                                            @csrf
                                            <input name="_method" type="hidden" value="PUT">

                                            <div class="form-group">
                                                <label>
                                                    {{ __('order::dashboard.orders.show.drivers.title') }}
                                                </label>
                                                <select name="user_id" class="form-control">
                                                    <option value="">
                                                        --- {{ __('order::dashboard.orders.show.drivers.title') }}
                                                        ---
                                                    </option>
                                                    @foreach ($drivers as $driver)
                                                        <option value="{{ $driver->id }}"
                                                        @if ($order->driver) {{ $order->driver->user_id == $driver->id ? 'selected' : '' }} @endif>

                                                            {{ $driver->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    {{ __('order::dashboard.orders.show.status') }}
                                                </label>
                                                <select name="order_status" id="single" class="form-control">
                                                    <option value="">--- Select ---</option>
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status->id }}"
                                                            {{ $order->order_status_id == $status->id ? 'selected' : '' }}>

                                                            {{ $status->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    {{ __('order::dashboard.orders.show.order_notes') }}
                                                </label>
                                                <textarea class="form-control" name="order_notes" rows="8" cols="80">{{ $order->order_notes }}</textarea>
                                            </div>


                                            <div id="result" style="display: none"></div>
                                            <div class="progress-info" style="display: none">
                                                <div class="progress-0">
                                                    <span class="progress-bar-0 progress-bar-warning"></span>
                                                </div>
                                                <div class="status" id="progress-status-0"></div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" id="submit" class="btn green btn-lg">
                                                    {{ __('apps::dashboard.general.edit_btn') }}
                                                </button>
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    @endpermission

                    @permission('show_order_change_payment_type_tab')
                    <div class="portlet light bordered" style="border: 1px solid #e7ecf1!important">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="fa fa-shopping-cart font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">
                                    {{ __('order::dashboard.orders.show.change_order_payment_types') }}
                                </span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="no-print">
                                <div class="row">
                                    <div class="col-md-12">

                                        <form id="updateForm-1" method="POST"
                                              action="{{ url(route('dashboard.orders.update_order_payment_type', $order['id'])) }}"
                                              enctype="multipart/form-data" class="horizontal-form">
                                            @csrf
                                            <input name="_method" type="hidden" value="PUT">

                                            <div class="form-group">
                                                <label>
                                                    {{ __('order::dashboard.orders.show.payment_type') }}
                                                </label>
                                                <select name="payment_type_id" id="paymentType" class="form-control">
                                                    <option value="">--- Select ---</option>
                                                    @foreach ($paymentTypes as $paymentType)
                                                        <option value="{{ $paymentType->id }}"
                                                            {{ $order->payment_type_id == $paymentType->id || (optional($order->paymentStatus)->flag == 'cash' && $paymentType->flag == 'cash') ? 'selected' : '' }}>
                                                            {{ $paymentType->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div id="progress-result-1" style="display: none"></div>
                                            <div class="progress-info" style="display: none">
                                                <div class="progress-1">
                                                    <span class="progress-bar-1 progress-bar-warning"></span>
                                                </div>
                                                <div class="status" id="progress-status-1"></div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" id="submit-1" class="btn green btn-lg">
                                                    {{ __('apps::dashboard.general.edit_btn') }}
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                @if ($order->orderPaymentTypeLogs->count() > 0)
                                    <div class="no-print row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.payment_type') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.updated_by') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.date') }}
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($order->orderPaymentTypeLogs as $k => $history)
                                                        <tr id="paymentTypeHistory-{{ $history->id }}">
                                                            <td class="text-center sbold">
                                                                {{ optional($history->paymentable)->title ?? '' }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ is_null($history->user_id) ? '---' : $history->user->name ?? '---' }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ $history->created_at }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endpermission

                    @permission('show_order_change_payment_status_tab')
                    <div class="portlet light bordered" style="border: 1px solid #e7ecf1!important">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="fa fa-shopping-cart font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">
                                    {{ __('order::dashboard.orders.show.change_order_payment_status') }}
                                </span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="no-print">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="updateForm-2" method="POST"
                                              action="{{ url(route('dashboard.orders.update_order_payment_status', $order['id'])) }}"
                                              enctype="multipart/form-data" class="horizontal-form">
                                            @csrf
                                            <input name="_method" type="hidden" value="PUT">

                                            <div class="form-group">
                                                <label>
                                                    {{ __('order::dashboard.orders.show.status') }}
                                                </label>
                                                <select name="payment_status_id" id="paymentStatusSelect"
                                                        class="form-control">
                                                    <option value="">--- Select ---</option>
                                                    @foreach ($paymentStatuses as $paymentStatus)
                                                        <option value="{{ $paymentStatus->id }}"
                                                                data-flag="{{ $paymentStatus->flag }}"
                                                            {{ $order->payment_status_id == $paymentStatus->id ? 'selected' : '' }}>
                                                            {{ $paymentStatus->custom_title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="decrementQtyHintSection" style="display: none">
                                                    <code id="decrementQtyHint"></code>
                                                </div>
                                            </div>

                                            <div id="progress-result-2" style="display: none"></div>
                                            <div class="progress-info" style="display: none">
                                                <div class="progress-2">
                                                    <span class="progress-bar-2 progress-bar-warning"></span>
                                                </div>
                                                <div class="status" id="progress-status-2"></div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" id="submit-2" class="btn green btn-lg">
                                                    {{ __('apps::dashboard.general.edit_btn') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                @if ($order->orderPaymentStatusLogs->count() > 0)
                                    <div class="no-print row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.order_status') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.updated_by') }}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{ __('order::dashboard.orders.show.order_history.date') }}
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($order->orderPaymentStatusLogs as $k => $history)
                                                        <tr id="paymentStatusHistory-{{ $history->id }}">
                                                            <td class="text-center sbold">
                                                                {{ optional($history->paymentable)->flag ?? '' }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ is_null($history->user_id) ? '---' : $history->user->name ?? '---' }}
                                                            </td>
                                                            <td class="text-center sbold">
                                                                {{ $history->created_at }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endpermission

                    <div class="note note-success">
                        <div class="company-address">
                            <span class="bold">
                                {{ __('transaction::dashboard.orders.show.transaction.payment_id') }} :
                            </span>
                            {{ $order->transactions->payment_id ?? '---' }}
                            <br />
                            <span class="bold">
                                {{ __('transaction::dashboard.orders.show.transaction.track_id') }} :
                            </span>
                            {{ $order->transactions->track_id ?? '---' }}
                            <br />
                            <span class="bold">
                                {{ __('transaction::dashboard.orders.show.transaction.method') }} :
                            </span>{{ $order->transactions->method ?? '---' }}
                            <br />
                            <span class="bold">
                                {{ __('transaction::dashboard.orders.show.transaction.payment_status') }} :
                            </span>{{ $order->paymentStatus->flag ?? '---' }}
                            <br />
                            <span class="bold">
                                {{ __('transaction::dashboard.orders.show.transaction.result') }} :
                            </span>{{ $order->transactions->result ?? '---' }}
                            <br />
                            <span class="bold">
                                {{ __('transaction::dashboard.orders.show.transaction.ref') }} :
                            </span>{{ $order->transactions->ref ?? '---' }}
                            <br />
                            <span class="bold">
                                {{ __('transaction::dashboard.orders.show.transaction.tran_id') }} :
                            </span>
                            {{ $order->transactions->tran_id ?? '---' }}
                            <br />
                        </div>
                    </div>
                    @permission('show_order_change_status_tab')

                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered" style="    border: 1px solid #e7ecf1!important">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="fa fa-archive font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">
                                        {{ __('order::dashboard.orders.show.order_history_log') }}
                                    </span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="no-print row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="invoice-title uppercase text-center">
                                                    {{ __('order::dashboard.orders.show.order_history.order_status') }}
                                                </th>
                                                <th class="invoice-title uppercase text-center">
                                                    {{ __('order::dashboard.orders.show.order_history.updated_by') }}
                                                </th>
                                                <th class="invoice-title uppercase text-center">
                                                    {{ __('order::dashboard.orders.show.order_history.date') }}
                                                </th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($order->orderStatusesHistory()->orderBy('pivot_created_at', 'desc')->get() as $k => $history)
                                                <tr id="orderHistory-{{ optional($history->pivot)->id }}">
                                                    <td class="text-center sbold">
                                                        {{ $history->title ?? '' }}
                                                    </td>
                                                    <td class="text-center sbold">
                                                        {{ is_null(optional($history->pivot)->user_id) ? '---' : \Modules\User\Entities\User::find(optional($history->pivot)->user_id)->name ?? null }}
                                                    </td>
                                                    <td class="text-center sbold">
                                                        {{ optional($history->pivot)->created_at }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                </div>
            </div>
        </div>
    </div>
    </div>

@stop

@section('scripts')
    <script>
        $(".refund_qty").bind('keyup mouseup', function() {
            var newQty = 0;
            $('.refund_qty').each(function() {
                let productId = $(this).attr('data-id');
                let fexedqty = $(this).attr('data-fexedqty');
                let newItemQty = $(this).val();
                let outputQty = parseInt(fexedqty) - parseInt(newItemQty);
                $(`#remaining_qty_${productId}`).text('').html(outputQty);
                $(`#remaining_qty_input_${productId}`).val(outputQty);

                newQty += parseInt($(this).val());
            });

            $('#return_qty').text('').append(newQty);
        });

        function toggleAdminTag() {
            $('.hide_admin_tag').toggle();
            $('.show_admin_tag').toggle();
        }

        function requestUpdating(status, data) {

            if (status == 'success') {

                $('#admin_note').text("").append(data.note);
                toggleAdminTag();
            }
        }

        $('#paymentStatusSelect').on('change', function() {
            let dataFlag = $(this).find(':selected').data('flag');
            let orderPaymentStatusFlag = '{{ $order->paymentStatus->flag }}';
            let msg = '';
            if (dataFlag != orderPaymentStatusFlag) {
                if (dataFlag == 'success' && orderPaymentStatusFlag != 'pending') {
                    msg = "{{ __('order::dashboard.orders.show.decrement_product_qty') }}";
                }else if(dataFlag == 'failed'){
                    msg = "{{ __('order::dashboard.orders.show.increment_product_qty') }}";
                }
            }
            if (msg != '') {
                $('#decrementQtyHintSection').show();
                $('#decrementQtyHint').html(msg);
            } else {
                $('#decrementQtyHintSection').hide();
            }

        });
    </script>


@endsection
