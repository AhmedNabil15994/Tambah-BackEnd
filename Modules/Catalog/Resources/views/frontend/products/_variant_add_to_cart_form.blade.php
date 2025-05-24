{{-- <form class="form" id="productDetailsForm"
    action="{{ route('frontend.shopping-cart.create-or-update', [$product->slug, $variantProduct->id]) }}" method="POST"
    data-id="{{ $variantProduct->id }}">
    @csrf --}}

    <div id="variantProductInfo">
        <span class="price have-discount">
            @if (isset($variantProduct->price) && $variantProduct->price)
                @if (isset($variantProduct->offer) && $variantProduct->offer)
                    {{ priceWithCurrenciesCode($variantProduct->offer->offer_price) }}
                @else
                    {{ priceWithCurrenciesCode($variantProduct->price) }}
                @endif
            @else
                @if ($product->offer)
                    @if (!is_null($product->offer->offer_price))
                        <span class="price-before">{{ priceWithCurrenciesCode($product->price) }}</span>
                        {{ priceWithCurrenciesCode($product->offer->offer_price) }}
                    @else
                        <span>{{ priceWithCurrenciesCode($product->price) }}</span>
                        /
                        <span class="percentage-discount">
                            {{ $product->offer->percentage . ' %' }}
                            {{ __('apps::frontend.master.discount') }}
                        </span>
                    @endif
                @else
                    {{ priceWithCurrenciesCode($product->price) }}
                @endif
            @endif
        </span>

        <input type="hidden" id="productImage-{{ $variantProduct->id }}" value="{{ url($variantProduct->image) }}">
        <input type="hidden" id="productTitle-{{ $variantProduct->id }}" value="{{ $productTitle }}">
        <input type="hidden" id="productType" value="variation">
        <input type="hidden" id="selectedOptions" value="{{ json_encode($selectedOptions) }}">
        <input type="hidden" id="selectedOptionsValue" value="{{ json_encode($selectedOptionsValue) }}">
        
        <div class="align-items-center d-flex">
            <h5>
                {{ __('catalog::frontend.products.quantity') }}
            </h5>
            <div class="quantity">
                <div class="buttons-added single-product-buttons-added ">
                    <button class="sign plus single-product-plus"><i class="fa fa-chevron-up"></i></button>
                    <input type="text" id="prodQuantity" name="qty"
                        value="{{ getCartItemById('var-' . $variantProduct->id) ? getCartItemById('var-' . $variantProduct->id)->quantity : '1' }}"
                        title="Qty" class="input-text qty text" size="1">
                    <button class="sign minus single-product-minus"><i class="fa fa-chevron-down"></i></button>
                </div>
            </div>
            <button id="btnAddToCart" type="submit" class="btn btn-them main-custom-btn">
                <i class="ti-shopping-cart"></i>
                {{ __('catalog::frontend.products.add_to_cart') }}
            </button>
            <div id="loaderDiv" style="margin:0px 46px">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status" style="width: 2rem; height: 2rem;">
                        <span class="sr-only">{{ __('apps::frontend.Loading') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </form> --}}
