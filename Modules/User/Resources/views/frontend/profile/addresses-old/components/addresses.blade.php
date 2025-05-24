@if(count($addresses) > 0)

    @foreach($addresses as $k => $address)
        <div class="address-item media align-items-center">
            <div class="product-summ-det">
                @include('user::frontend.profile.addresses.components.address-card-data',[
                'address' => $address
                ])
            </div>
            <div class="text-left address-operations">
                <button class="btn edit-address"class="btn edit-address" data-toggle="modal"
                        data-target="#addressModel{{$address->id}}"><i
                            class="ti-pencil-alt"></i> {{ __('user::frontend.addresses.index.btn.edit') }}
                </button>
                <a class="btn delete-address"
                   href="{{ url(route('frontend.profile.address.delete', $address->id)) }}">
                    <i class="ti-trash"></i> {{ __('user::frontend.addresses.index.btn.delete') }}
                </a>
            </div>
        </div>

        @include('user::frontend.profile.addresses.components.address-model',[
        'route' => route('frontend.profile.address.update',$address->id),
        'model' => $address
        ])
    @endforeach
@else
    <b>{{ __('user::frontend.addresses.index.alert.no_addresses') }}</b>
@endif