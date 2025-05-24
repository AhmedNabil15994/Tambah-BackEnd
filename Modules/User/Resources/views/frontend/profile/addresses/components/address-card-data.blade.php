<div id="address_card_content_{{$address->id}}">
    <p class="d-flex">
        <span class="d-inline-block right-side">{{__('user::frontend.addresses.form.state_name')}} </span>
        <span class="d-inline-block left-side">   {{ optional($address->state)->title }}</span>
    </p>
    <p class="d-flex">
        <span class="d-inline-block right-side">{{__('user::frontend.addresses.form.address_details')}}</span>
        <span class="d-inline-block left-side">{{ $address->address ?? '---' }}</span>
    </p>
    <p class="d-flex">
        <span class="d-inline-block right-side">{{__('user::frontend.addresses.form.mobile')}}.</span>
        <span class="d-inline-block left-side">{{ $address->mobile }}</span>
    </p>
</div>