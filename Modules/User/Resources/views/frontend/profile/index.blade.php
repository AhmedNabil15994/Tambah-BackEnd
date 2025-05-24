@extends('apps::frontend.layouts.master')
@section('title', __('user::frontend.profile.index.title'))
@section('content')
    @include('user::frontend.profile.components.header' , ['title' => __('user::frontend.profile.index.title')])
    <form method="post" action="{{ url(route('frontend.profile.update')) }}" class="contact-form">
        @csrf
        <input type="hidden" name="type" value="profile">

        <div class="previous-address">
            @include('apps::frontend.layouts._alerts')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" value="{{ auth()->user()->name }}" name="name"
                               class="form-control" placeholder="{{ __('user::frontend.profile.index.form.name') }}"/>

                        @error('name')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control"
                               placeholder="{{ __('user::frontend.profile.index.form.email') }}"/>
                        @error('email')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" name="mobile"
                               value="{{ auth()->user()->mobile }}"
                               placeholder="{{ __('user::frontend.profile.index.form.mobile') }}"/>

                        @error('mobile')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                {!! field('frontend_no_label')->select('currency_id',
                  __('user::frontend.addresses.form.select_currency'),$supported_currencies ,
                    isset(session()->get('currency_data')['selected_currency']) ? optional(session()->get('currency_data')['selected_currency'])->id :
                   defaultCurrency('id'),[
                      'class' => 'select-detail select2 form-control',
                ]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="password" class="form-control"
                               placeholder="{{ __('user::frontend.profile.index.form.password') }}"/>
                        @error('password')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="{{ __('user::frontend.profile.index.form.password_confirmation') }}"/>

                        @error('password_confirmation')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="mb-20 mt-20 text-left">
            <button class="btn btn-them main-custom-btn"
                    type="submit">{{ __('user::frontend.profile.index.form.btn.update') }}</button>
            <a href="{{route('frontend.logout')}}" class="btn btn-them main-custom-btn"
               type="submit">{{ __('user::frontend.profile.index.form.btn.logout') }}</a>
        </div>
    </form>
    @include('user::frontend.profile.components.footer')
@endsection
