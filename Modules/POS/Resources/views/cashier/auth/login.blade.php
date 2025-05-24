<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ __('pos::cashier.login.routes.index') }}  || {{ config('app.name') }}</title>
        <link rel="shortcut icon" href="{{url(config('setting.favicon'))}}" />
        <link rel=" icon" href="{{url(config('setting.favicon'))}}" />
        <link rel="stylesheet" href="/poss/css/bootstrap.min.css">
        <link rel="stylesheet" href="/poss/css/themify-icons.css">
        <link rel="stylesheet" href="/poss/css/linearicons.min.css">
        <link rel="stylesheet" href="/poss/css/style.css">
        <style>
            .help-block{
                color: red;
                display: inline-block;
                margin-bottom: 15px;
            }
            .has-error{
                border-color: red !important;
            }
        </style>
    </head> 
    <body>
        <div class="login-page d-flex align-items-center justify-content-center">
            <div class="login-block">
                <div class="login-head text-center">
                    <img class="img-fluid" src="/poss/images/favicon.png" alt="" />
                    <h5>{{ __('pos::cashier.login.form.header') }}</h5>
                </div>
                <form method="POST" method="{{ route('cashier.login.post') }}">
                    @csrf
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <div class="form-group   input-withicon">
                        <input type="text" 
                            class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" 
                            placeholder=" {{ __('pos::cashier.login.form.email') }}" 
                            value="{{old('email')}}"
                            name="email" value=""
                        >
                        <i class="lnr lnr-envelope"></i>
                        
                    </div>
                   
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <div class="form-group input-withicon">
                        <input type="password" 
                                 class="form-control  {{ $errors->has('password') ? ' has-error' : '' }}" 
                                 placeholder="{{ __('pos::cashier.login.form.password') }}" 
                                 name="password"
                        >
                        <i class="lnr lnr-lock"></i>

                    </div>
                    <div class="">
                        <button class="btn btn-block btn-sumbit" type="submit"><i class="lnr lnr-enter"></i>{{ __('pos::cashier.login.form.btn.login') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
