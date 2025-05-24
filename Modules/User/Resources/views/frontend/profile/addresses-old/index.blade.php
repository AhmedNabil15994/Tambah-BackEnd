@extends('apps::frontend.layouts.master')
@section('title', __('user::frontend.addresses.index.title'))
@push('styles')
    <style>

        .help-block {
            color: red;
            font-size: 10px;
            font-weight: 800;
        }
    </style>
@endpush
@section('content')
    @include('user::frontend.profile.components.header' , ['title' => __('user::frontend.addresses.index.title')])
    <div class="previous-address">

        <h2 class="cart-title">{{ __('user::frontend.addresses.index.title')}}</h2>
        @include('apps::frontend.layouts._alerts')

        <div id="address_container">
            @include('user::frontend.profile.addresses.components.addresses')
        </div>

        <div class="cart-footer pt-40 mb-20 mt-20 text-left">
            <button class="btn btn-them main-custom-btn" onclick="openAddressModal('addressModel')">
                {{ __('user::frontend.addresses.create.title') }}
            </button>
        </div>
    </div>

    @include('user::frontend.profile.addresses.components.address-model',['route' => route('frontend.profile.address.store')])

    @include('user::frontend.profile.components.footer')
@endsection
@push('scripts')
    @include('user::frontend.profile.addresses.components.address-model-scripts')
@endpush