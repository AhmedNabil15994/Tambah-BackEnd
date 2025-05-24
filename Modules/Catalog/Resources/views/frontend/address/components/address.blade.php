<div id="checkout-address-card{{$address->id}}">

    <div class="custom-control custom-radio address-block margin-bota0">
        <input type="radio" class="custom-control-input" value="{{ $address->id }}" name="selected_address_id"
               id="checkoutSelectedAddress-{{ $address->id }}"
               @if(!is_null(Cart::getCondition('company_delivery_fees')))
               {{ Cart::getCondition('company_delivery_fees')->getAttributes()['address_id'] == $address->id ? 'checked' : '' }}
               @else {{ old('selected_address_id') == $address->id ? 'checked' : '' }} @endif
               onclick="getDeliveryPriceOnStateChanged('{{ $address->state_id }}', '{{ $address->id }}')">
        <label class="custom-control-label w-100 pl-3" for="checkoutSelectedAddress-{{$address->id}}">
            <div class="address-item media align-items-center">
                <div class="product-summ-det">
                    @include('user::frontend.profile.addresses.components.address-card-data',[
                        'address' => $address
                        ])
                </div>
                <div class="text-left address-operations">
                    <a href="#" class="btn edit-address" onclick="openAddressModal('addressModel{{$address->id}}')">
                        <i class="ti-pencil-alt"></i>
                        {{ __('user::frontend.addresses.index.btn.edit') }}
                    </a>
                </div>
            </div>
        </label>
    </div>

</div>