<div class="order-summery cart-order-summery">
    <div class="minicart-content-wrapper">

        @if (!in_array(request()->route()->getName(),
            ['frontend.shopping-cart.index']))
            <div class="minicart-items-wrapper">
                <ol class="minicart-items">

                    @foreach (getCartContent() as $k => $item)
                        <li class="product-item">
                            <div class="media align-items-center">
                                <div class="pro-img d-flex align-items-center">
                                    <img class="img-fluid" src="{{ url($item->attributes->product->image) }}"
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
                                    <div class="product-price d-block">
                                        <span class="text-muted">x {{ $item->quantity }}</span>
                                        <span>{{ priceWithCurrenciesCode($item->price) }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach

                </ol>
            </div>
        @endif

        <div class="d-flex mb-20 align-items-center">
            <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.cart.subtotal') }}</span>
            <span id="cartSubTotal"
                class="d-inline-block left-side">{{ priceWithCurrenciesCode(number_format(getCartSubTotal(), 3)) }}</span>
        </div>

        <div id="deliveryPriceLoaderDiv">
            <div class="my-loader"></div>
        </div>
        <div id="totalCompaniesDeliveryPrice">

            @if (Cart::getCondition('company_delivery_fees'))
                <div class="d-flex mb-20 align-items-center mb-3">
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
            @endif

        </div>

        @if (!in_array(request()->route()->getName(),
            ['frontend.checkout.index']))
            <div id="couponForm">
                @if ((\Cart::getCondition('coupon_discount') == null ||
                    \Cart::getCondition('coupon_discount')->getValue() == 0) &&
                    (is_null(getCartItemsCouponValue()) || getCartItemsCouponValue() == 0))
                    <form class="coupon-form" action="{{ url(route('frontend.check_coupon')) }}" method="POST">
                        @csrf

                        {{-- <div id="loaderCouponDiv" class="text-center"
                             style="display: none; margin-bottom: 5px;">Loading
                        </div> --}}

                        <div id="loaderCouponDiv">
                            <div class="my-loader"></div>
                        </div>

                        <div class="d-flex mb-20 promo-code align-items-center">

                            <input type="hidden" value="" id="coupon_discount_id" name="coupon_discount_id">
                            <input type="hidden" value="" id="coupon_discount_value"
                                name="coupon_discount_value">

                            <span class="d-inline-block right-side flex-1">
                                <input type="text" id="txtCouponCode"
                                    placeholder="{{ __('catalog::frontend.cart.enter_discount_number') }}" />
                            </span>
                            <span class="d-inline-block left-side">
                                <button class="btn btn-add" id="btnCheckCoupon"
                                    type="button">{{ __('catalog::frontend.cart.btn.add') }}</button>
                            </span>
                            <span class="d-inline-block left-side remove"
                                title="{{ __('catalog::frontend.cart.btn.remove_coupon') }}">
                                <i class="ti-close"></i>
                            </span>
                        </div>

                    </form>
                @endif
            </div>
        @endif

        <div id="couponContainer">
            @if (\Cart::getCondition('coupon_discount') != null && \Cart::getCondition('coupon_discount')->getValue() != 0)
                <div class="d-flex mb-20 align-items-center">
                    <span class="d-inline-block right-side flex-1">
                        {{ __('catalog::frontend.cart.coupon_value') }}</span>
                    <span
                        class="d-inline-block left-side">{{ priceWithCurrenciesCode(number_format(abs(Cart::getCondition('coupon_discount')->getValue()), 3)) }}</span>
                </div>
            @endif

            @if (!is_null(getCartItemsCouponValue()) && getCartItemsCouponValue() != 0)
                <div class="d-flex mb-20 align-items-center">
                    <span class="d-inline-block right-side flex-1">
                        {{ __('catalog::frontend.cart.coupon_value') }}</span>
                    <span
                        class="d-inline-block left-side">{{ priceWithCurrenciesCode(number_format(getCartItemsCouponValue(), 3)) }}</span>
                </div>
            @endif

        </div>

        <div class="minicart-footer">

            @if (in_array(request()->route()->getName(),
                ['frontend.checkout.index']))
                <div class="order-payment">

                    @foreach ($paymentMethods as $k => $payment)
                        <div class="checkboxes radios mb-20">
                            <input id="payment-{{ $payment->id }}" type="radio" value="{{ $payment->code }}"
                                name="payment" {{ old('payment') == $payment->code ? 'checked' : '' }}>
                            <label for="payment-{{ $payment->id }}">
                                {{ $payment->title }}
                            </label>
                        </div>
                    @endforeach

                </div>
            @endif

            <div class="subtotal d-flex text-center justify-content-center mb-20">
                <span class="label">{{ __('catalog::frontend.checkout.total') }} : </span>
                <span id="cartTotalAmount"
                    class="price">{{ priceWithCurrenciesCode(number_format(getCartTotal(), 3)) }}</span>
            </div>

            @if (in_array(request()->route()->getName(),
                ['frontend.checkout.index']))
                <div class="actions">
                    <button type="submit" class="btn btn-checkout btn-them btn-block main-custom-btn">
                        {{ __('catalog::frontend.checkout.index.confirm_order') }}
                    </button>
                </div>
            @else
                <div class="actions">
                    <a class="btn btn-checkout btn-them btn-block main-custom-btn"
                        href="{{ route('frontend.checkout.index') }}">
                        {{ __('catalog::frontend.cart.btn.checkout') }}
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>
