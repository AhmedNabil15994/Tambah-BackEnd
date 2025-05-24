@if(count(auth()->user()->addresses) > 0)
    @include('user::frontend.profile.addresses.components.address-model',[
        'route' => route('frontend.profile.address.update','lakfagd'),
        'model' => ['id' => 'lakfagd'],
        'view_type' => 'checkout'
        ])
    @foreach(auth()->user()->addresses as $k => $address)

        @include('catalog::frontend.address.components.address',compact('address'))

        @include('user::frontend.profile.addresses.components.address-model',[
        'route' => route('frontend.profile.address.update',$address->id),
        'model' => $address,
        'view_type' => 'checkout'
        ])
    @endforeach
@else
    <b>{{ __('user::frontend.addresses.index.alert.no_addresses') }}</b>
@endif