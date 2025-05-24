<!DOCTYPE html>
<html dir="{{ locale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ locale() == 'ar' ? 'ar' : 'en' }}">

@include('apps::frontend.layouts._header')

<body>

    <div class="main-content">
        @include('apps::frontend.layouts.header-section', [
            'headerCategories' => $headerCategories,
            'aboutUs' => $aboutUs,
        ])
        <div class="site-main">
            @yield('content')
        </div>
        @include('apps::frontend.layouts.footer-section', compact('aboutUs', 'terms', 'privacyPage'))
    </div>

    @if (config('setting.contact_us.mobile'))
        <a href="https://wa.me/{{ config('setting.contact_us.mobile') }}" data-toggle="tooltip" data-placement="top"
            title="الدعم الفني" target="_blank" class="whatsapp-chat no-print">
            <img src="{{ asset('frontend/images/whatsapp.png') }}" alt="" />
        </a>
    @endif

    @include('apps::frontend.layouts.scripts')

</body>

</html>
