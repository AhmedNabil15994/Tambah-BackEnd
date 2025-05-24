@if (isset(Setting::get('theme_sections')['top_footer']) && Setting::get('theme_sections')['top_footer'])
    <footer class="footer no-print">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-12 footer-logo-icon">
                    <img class="footer-logo"
                        src="{{ config('setting.logo') ? url(config('setting.logo')) : url('frontend/images/header-logo.png') }}" />
                    <div class="links">
                        <ul>
                            @if (config('setting.contact_us.address'))
                                <li>{{ config('setting.contact_us.address') }}</li>
                            @endif
                        </ul>
                    </div>
                    @if (isset(Setting::get('theme_sections')['footer_social_media']) &&
                        Setting::get('theme_sections')['footer_social_media'])
                        <div class="footer-social">
                            @if (!config('setting.social')['facebook'] || config('setting.social')['facebook'] != '#')
                                <a href="{{ config('setting.social')['facebook'] }}" class="social-icon"><i
                                        class="ti-facebook"></i></a>
                            @endif
                            @if (!config('setting.social')['instagram'] || config('setting.social')['instagram'] != '#')
                                <a href="{{ config('setting.social')['instagram'] }}" class="social-icon"><i
                                        class="ti-instagram"></i></a>
                            @endif
                            @if (!config('setting.social')['linkedin'] || config('setting.social')['linkedin'] != '#')
                                <a href="{{ config('setting.social')['linkedin'] }}" class="social-icon"><i
                                        class="ti-linkedin"></i></a>
                            @endif
                            @if (!config('setting.social')['twitter'] || config('setting.social')['twitter'] != '#')
                                <a href="{{ config('setting.social')['twitter'] }}" class="social-icon"><i
                                        class="ti-twitter-alt"></i></a>
                            @endif
                        </div>
                    @endif
                </div>

                @if (!$terms || !$privacyPage)
                    <div class="col-md-2 col-6">
                        <h3 class="title-of-footer"> {{ __('apps::frontend.master.important_links') }}</h3>
                        <div class="links">
                            <ul>
                                @if ($terms)
                                    <li>
                                        <a
                                            href="{{ $terms ? route('frontend.pages.index', $terms->slug) : '#' }}">{{ __('apps::frontend.Terms & Conditions') }}</a>
                                    </li>
                                @endif

                                @if ($privacyPage)
                                    <li>
                                        <a
                                            href="{{ $privacyPage ? route('frontend.pages.index', $privacyPage->slug) : '#' }}">{{ __('apps::frontend.Privacy & Policy') }}</a>
                                    </li>
                                @endif

                                @guest()
                                    <li>
                                        <a href="{{ route('frontend.login') }}">
                                            {{ __('authentication::frontend.login.title') }}</a>
                                    </li>
                                @endguest
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="col-md-2 col-6">
                    <h3 class="title-of-footer">{{ __('apps::frontend.master.website_links') }}</h3>
                    <div class="links">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('apps::frontend.master.home') }}</a></li>
                            @if ($aboutUs)
                                <li>
                                    <a
                                        href="{{ $aboutUs ? route('frontend.pages.index', $aboutUs->slug) : '#' }}">{{ __('apps::frontend.master.about_us') }}</a>
                                </li>
                            @endif
                            <li>
                                <a
                                    href="{{ route('frontend.contact_us') }}">{{ __('apps::frontend.master.contact_us') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-12 footer-subscribe">
                    {{-- <h3 class="title-of-footer"> {{__('apps::frontend.Subscribe to get offers')}}</h3>
                    <div class="subscribe-form">
                        {!! Form::open([
                                 'url'=> route('frontend.subscribe'),
                                 'id'=>'subscribe-form',
                                 'role'=>'form',
                                 'method'=>'POST',
                                 'class'=>'subscribe-form',
                                 ])!!}
                        <input type="email" class="form-control" name="subscribe_email"
                               placeholder="{{ __('apps::frontend.contact_us.form.email')}}"/>
                        <button class="btn main-custom-btn"
                                type="submit">{{ __('apps::frontend.master.subscribe') }}</button>
                        {!! Form::close()!!}
                    </div> --}}
                    <h3 class="title-of-footer">{{ __('apps::frontend.Payment Method') }}</h3>
                    <div class="pay-men">
                        <a href="#"><img src="{{ asset('frontend/images/payment.svg') }}" alt="pay1"></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endif

@if (isset(Setting::get('theme_sections')['bottom_footer']) && Setting::get('theme_sections')['bottom_footer'])
    <div class="footer-copyright text-center no-print">
        <p>{{ \Carbon\Carbon::now()->format('Y') }} © {{ __('apps::frontend.footer.designed_and_developed_by') }} <a
                target="_blank" href="https://tocaan.com">{{ __('apps::frontend.footer.tocaan') }}</a></p>
    </div>
@endif
