
<div class="container">
    <div class="page-crumb mt-30">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend.home')}}"><i
                                class="ti-home"></i> {{__('apps::frontend.master.home')}}</a></li>
                <li class="breadcrumb-item"><a
                            href="{{route('frontend.profile.index')}}">{{ __('user::frontend.profile.index.my_account') }}</a>
                </li>
                <li class="breadcrumb-item active text-muted" aria-current="page">{{$title}}</li>
            </ol>
        </nav>
    </div>
    <div class="inner-page">
        <div class="row">

            @include('user::frontend.profile._user-side-menu')
            <div class="col-md-9">
                <div class="cart-inner">
