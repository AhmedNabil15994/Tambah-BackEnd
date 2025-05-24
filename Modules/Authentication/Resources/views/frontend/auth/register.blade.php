@extends('apps::frontend.layouts.master')
@section('title', __('authentication::frontend.login.login_or_signup') )
@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6 col-md-8 col-sm-11">
                    <div class="login">
                        <img class="img-fluid img-head" src="{{asset('frontend/images/login-img.png')}}" alt=""/>
                        <h2>{{ __('authentication::frontend.register.register_welcome_msg') }}</h2>
                        <p>{{ __('authentication::frontend.register.register_new_account') }}</p>
                        @if(old('formName') == 'registerForm')
                            @error('email_or_mobile')
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $message }}</li>
                                    </ul>
                                </div>
                            @enderror
                        @endif
                        <form class="contact-form margin-top-40" method="post" action="{{ route('frontend.register') }}"
                              novalidate="true">
                            @csrf
                            <input type="hidden" name="formName" value="registerForm">
                            <input type="hidden" name="type" value="{{ request()->get('type') ?? '' }}">
                            <div class="form-group margin-bottom-20">
                                <input type="text" name="name" autocomplete="off" value="{{ old('name') }}"
                                       class="form-control"
                                       placeholder="{{ __('authentication::frontend.register.form.name') }}">

                                @if(old('formName') == 'registerForm')
                                    @error('name')
                                    <p class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                @endif
                            </div>
                            <div class="form-group margin-bottom-20">
                                <input type="email" name="email" autocomplete="off" value="{{ old('email') }}"
                                       class="form-control"
                                       placeholder="{{ __('authentication::frontend.register.form.email') }}">

                                @if(old('formName') == 'registerForm')
                                    @error('email')
                                    <p class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                @endif
                            </div>

                            <div class="form-group margin-bottom-20">
                                <input type="text" name="mobile" id="mobile"
                                       placeholder="{{ __('authentication::frontend.register.form.mobile') }}"
                                       class="form-control"
                                       value="{{ old('mobile') }}">
                                <input type="hidden" id="country_code" name="country_code">

                                @if(old('formName') == 'registerForm')
                                    @error('mobile')
                                    <p class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                @endif
                            </div>
                            <div class="form-group margin-bottom-20 position-relative">
                                <i class="position-absolute fas eye-slash" id="showPass"></i>
                                <input type="password" name="password" id="passInput" class="form-control"
                                       placeholder="{{ __('authentication::frontend.register.form.password') }}">

                                @if(old('formName') == 'registerForm')
                                    @error('password')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                @endif
                            </div>
                            {{-- <div class="form-group margin-bottom-20 position-relative">
                                <i class="position-absolute fas eye-slash" id="showPass2"></i>
                                <input type="password" name="password_confirmation" id="passInput2" class="form-control"
                                       placeholder="{{ __('authentication::frontend.register.form.password_confirmation') }}">
                            </div> --}}

                            <button class="btn btn-theme2 btn-block main-custom-btn" name="btnRegister"
                                    type="submit"> {{ __('authentication::frontend.register.btn.register') }}
                            </button>
                        </form>

                        <div class="margin-top-40 text-center">
                            <span class="text-muted d-block margin-bottom-10">{{ __('authentication::frontend.login.login_welcome_msg') }}</span>
                            <a class="btn btn-theme btn-block secondary-custom-btn" href="{{route('frontend.login')}}">{{__('apps::frontend.Login')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
