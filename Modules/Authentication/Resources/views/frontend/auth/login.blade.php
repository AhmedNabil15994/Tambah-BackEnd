@extends('apps::frontend.layouts.master')
@section('title', __('authentication::frontend.login.login_or_signup') )
@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6 col-md-8 col-sm-11">
                    <div class="login">
                        <img class="img-fluid img-head" src="{{asset('frontend/images/login-img.png')}}" alt="" />
                        <h2>{{ __('authentication::frontend.login.login_welcome_msg') }}</h2>
                        <p>{{ __('authentication::frontend.login.title') }}</p>
                        <form class="contact-form margin-top-40" method="post" action="{{ route('frontend.post_login') }}" novalidate="true">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{$request['route']}}">
                            <input type="hidden" name="formName" value="loginForm">
                            <input type="hidden" name="type" value="{{ request()->get('type') ?? '' }}">

                            <div class="form-group margin-bottom-20">
                                <input type="text"
                                       name="email"
                                       id="email"
                                       class="form-control"
                                       required
                                       value="{{ old('email') }}"
                                       autocomplete="off"
                                       placeholder="{{ __('authentication::frontend.login.form.email_or_mobile')}}">

                                @if(old('formName') == 'loginForm')
                                    @error('email')
                                    <p class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                @endif
                            </div>
                            <div class="form-group margin-bottom-20 position-relative">
                                <i class="position-absolute fas eye-slash" id="password"></i>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       id="password"
                                       required
                                       placeholder="{{ __('authentication::frontend.login.form.password')}}">

                                @if(old('formName') == 'loginForm')
                                    @error('password')
                                    <p class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                @endif
                            </div>
                            <div class="form-group margin-bottom-30 d-flex align-items-center justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input id="check-e"
                                           type="checkbox"
                                           name="remember"
                                           class="custom-control-input"
                                            {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="check-e">{{ __('authentication::frontend.login.form.remember_me') }} </label>
                                </div>
                                <a class="link-muted" href="{{ route('frontend.password.request') }}">{{ __('authentication::frontend.login.form.btn.forget_password') }}</a>
                            </div>
                            <button class="btn btn-theme2 btn-block main-custom-btn" name="btnLogin"
                                    type="submit">{{  __('authentication::frontend.login.form.btn.login') }}
                            </button>
                        </form>

                        <div class="margin-top-40 text-center">
                            <span class="text-muted d-block margin-bottom-10">{{ __('authentication::frontend.register.register_new_account') }}</span>
                            <a class="btn btn-theme btn-block secondary-custom-btn" href="{{route('frontend.register')}}">{{__('apps::frontend.Sign Up')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
