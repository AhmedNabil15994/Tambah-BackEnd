<div class="modal fade" id="addition-details-{{ $addonsObject->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body wrap-det">
                <div class="choose-products-wrap mt-20">

                    <div class="row" style="justify-content: center;">

                        <div class="col-md-4 col-6">
                            <div
                                class="product-grid gift-wrap {{ checkSelectedCartAddons($addonsObject->id) ? 'active' : '' }}">
                                <div class="product-image d-flex align-items-center">
                                    <img class="pic-1" src="{{ url($addonsObject->image) }}">
                                </div>
                                <div class="product-content">
                                    <h3 class="title">{{ $addonsObject->title }}</h3>
                                    <span class="price">{{ priceWithCurrenciesCode($addonsObject->price) }}</span>
                                </div>
                            </div>
                            <div class="quantity mt-20 mb-20">
                                <div class="buttons-added">
                                    <button class="sign plus"><i class="fa fa-plus"></i></button>
                                    <input type="text"
                                        value="{{ checkSelectedCartAddons($addonsObject->id) ? checkSelectedCartAddons($addonsObject->id)['qty'] : '1' }}"
                                        title="Qty" class="input-text qty text" size="1"
                                        id="qty_{{ $addonsObject->id }}">
                                    <button class="sign minus"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <form class="form" method="POST">
                    @csrf
                    <div class="mb-20 mt-30 text-center">

                        <div class="loaderDiv">
                            <div class="my-loader"></div>
                        </div>

                        <a href="javascript:;" id="btnAddonsCart-{{ $addonsObject->id }}"
                            onclick="addOrUpdateCartAddons('{{ route('frontend.shopping-cart.add_addons', $addonsObject->id) }}', '{{ $addonsObject->id }}')"
                            class="btn btn-them w200"> {{ __('wrapping::frontend.wrapping.btn.choose') }}</a>

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
