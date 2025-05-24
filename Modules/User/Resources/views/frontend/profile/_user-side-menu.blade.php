<div class="col-md-3">
    <div class="user-side-menu">
        <h4>{{__('apps::frontend.master.my_account')}}</h4>
        <ul>
            <li class="{{ url()->current() == route('frontend.profile.index') ? 'active' : '' }}">
                <a href="{{route('frontend.profile.index')}}">
                    <i class="ti-user"></i>
                    <span>{{ __('user::frontend.profile.index.my_account') }}</span>
                </a>
            </li>
            <li class="{{ url()->current() == route('frontend.profile.address.index') ? 'active' : '' }}">
                <a href="{{route('frontend.profile.address.index')}}">
                    <i class="ti-truck"></i>
                    <span>{{ __('user::frontend.profile.index.addresses') }}</span>
                </a>
            </li>
            <li class="{{ url()->current() == route('frontend.profile.favourites.index') ? 'active' : '' }}">
                <a href="{{route('frontend.profile.favourites.index')}}">
                    <i class="ti-heart"></i>
                    {{ __('user::frontend.profile.index.favourites') }}
                </a>
            </li>
            <li class="{{ url()->current() == route('frontend.orders.index') ? 'active' : '' }}">
                <a href="{{route('frontend.orders.index')}}">
                    <i class="ti-map-alt"></i>
                    <span>{{ __('user::frontend.profile.index.my_orders') }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>