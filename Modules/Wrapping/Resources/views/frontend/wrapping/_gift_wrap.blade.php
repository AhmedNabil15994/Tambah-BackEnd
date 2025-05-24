<div class="modal fade" id="warp-details-{{ $giftObject->id }}" tabindex="-1" role="dialog" aria-hidden="true"
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            {{-- <div class="modal-header">
                <h5 class="modal-title">{{ __('wrapping::frontend.wrapping.gift_wrapper') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> --}}

            <div class="modal-body wrap-det">
                <div class="img-box text-center mb-20">
                    <img src="{{ url($giftObject->image) }}" alt="{{ $giftObject->title }}"
                        style="width: 300px; height: 200px;" />
                </div>
                <h4>{{ __('wrapping::frontend.wrapping.gift_wrapper') }}</h4>
                <span class="warp-price d-block mb-20">{{ priceWithCurrenciesCode($giftObject->price) }}</span>
                <p>{{ $giftObject->title }}</p>

                <div class="choose-products-wrap mt-20">
                    <h5>{{ __('wrapping::frontend.wrapping.select_at_least_one_element') }}</h5>

                    <div class="row">

                        @if (count(Cart::getContent()) > 0)
                            @foreach (Cart::getContent() as $cartItem)
                                <div class="col-md-4 col-6">
                                    <div class="giftWrap-{{ $giftObject->id }} product-grid gift-wrap {{ checkSelectedCartGiftProducts($cartItem->attributes->product->id, $giftObject->id) ? 'active' : '' }}"
                                        data-id="{{ $cartItem->attributes->product->id }}"
                                        data-type="{{ $cartItem->attributes->product->product_type }}">
                                        <div class="product-image d-flex align-items-center">
                                            <img class="pic-1" src="{{ url($cartItem->attributes->image) }}">
                                        </div>
                                        <div class="product-content">
                                            @if ($cartItem->attributes->product_type == 'product')
                                                <h3 class="title">{{ $cartItem->attributes->product->title }}</h3>
                                            @else
                                                <h3 class="title">
                                                    {{ generateVariantProductData($cartItem->attributes->product->product, $cartItem->attributes->product->id, $cartItem->attributes->selectedOptionsValue)['name'] }}
                                                </h3>
                                            @endif
                                            <span class="price">{{ $cartItem->price }}
                                                {{ __('apps::frontend.master.kwd') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>

                <form class="form" method="POST">
                    @csrf
                    <div class="mb-20 mt-30 text-center">

                        <div class="loaderDiv">
                            <div class="my-loader"></div>
                        </div>

                        <button type="button" id="btnCheckGift-{{ $giftObject->id }}"
                            onclick="checkGiftProducts('{{ route('frontend.shopping-cart.add_gift', $giftObject->id) }}', '{{ $giftObject->id }}')"
                            class="btn btn-them w200 btnCheckGift">
                            {{ __('wrapping::frontend.wrapping.btn.choose') }}</button>

                        <button type="button" class="btn btn-them w200" class="close" data-dismiss="modal"
                            aria-label="Close">
                            {{ __('wrapping::frontend.wrapping.btn.close') }}
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
