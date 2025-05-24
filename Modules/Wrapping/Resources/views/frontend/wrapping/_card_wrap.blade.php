<div class="modal fade" id="card-details-{{ $cardObject->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="img-box text-center">
                    <img src="{{ url($cardObject->image) }}" alt="{{ $cardObject->title }}"
                        style="width: 100%; height: 350px;" />
                </div>
                <div class="wrap-det">
                    <h4>{{ $card->title }}</h4>
                    <span class="warp-price d-block mb-20">{{ priceWithCurrenciesCode($cardObject->price) }}</span>
                    <form class="card-form mt-20" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <input type="text" id="card_sender_name_{{ $cardObject->id }}"
                                        value="{{ checkSelectedCartCards($cardObject->id) ? checkSelectedCartCards($cardObject->id)['sender_name'] : '' }}"
                                        placeholder="{{ __('wrapping::frontend.cards.form.sender_name') }}"
                                        autocomplete="off" name="sender_name" />
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <input type="text" id="card_receiver_name_{{ $cardObject->id }}"
                                        value="{{ checkSelectedCartCards($cardObject->id) ? checkSelectedCartCards($cardObject->id)['receiver_name'] : '' }}"
                                        placeholder="{{ __('wrapping::frontend.cards.form.receiver_name') }}"
                                        autocomplete="off" name="receiver_name" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="{{ __('wrapping::frontend.cards.form.message') }}" rows="5"
                                id="card_message_{{ $cardObject->id }}" name="message">{{ checkSelectedCartCards($cardObject->id) ? checkSelectedCartCards($cardObject->id)['message'] : '' }}</textarea>
                        </div>
                        <div class="mb-20 mt-30 text-center">

                            <div class="loaderDiv">
                                <div class="my-loader"></div>
                            </div>

                            <button type="button" id="btnCardCart-{{ $cardObject->id }}"
                                onclick="addOrUpdateCartCard('{{ route('frontend.shopping-cart.add_card', $cardObject->id) }}', '{{ $cardObject->id }}')"
                                class="btn btn-them w200">
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
</div>
