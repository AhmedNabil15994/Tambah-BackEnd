<!doctype html>
<html lang="{{ locale() == 'ar' ? 'ar' : 'en' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta description -->
    <meta name="author" content="">


    <!--title-->
    <title>
        @if (locale() == 'ar')
            شركة تمبه لتجارة الجملة والتجزئة
        @else
            Tambah Wholesale & Retail Trading
        @endif
    </title>

    <!--favicon icon-->
    <link rel="icon" href="{{ url('frontend/landing-v2/img/favicon.png') }}" type="image/png" sizes="16x16">

    <!--Bootstrap css-->
    <link rel="stylesheet" href="{{ url('frontend/landing-v2/css/bootstrap.min.css') }}">
    <!--Magnific popup css-->
    <link rel="stylesheet" href="{{ url('frontend/landing-v2/css/themify-icons.css') }}">
    <!--animated css-->
    <link rel="stylesheet" href="{{ url('frontend/landing-v2/css/animate.min.css') }}">
    @if (locale() == 'ar')
        <!--custom css-->
        <link rel="stylesheet" href="{{ url('frontend/landing-v2/css/main-ar.css') }}">
        <!--responsive css-->
        <link rel="stylesheet" href="{{ url('frontend/landing-v2/css/responsive-ar.css') }}">
    @else
        <!--custom css-->
        <link rel="stylesheet" href="{{ url('frontend/landing-v2/css/main-en.css') }}">
        <!--responsive css-->
        <link rel="stylesheet" href="{{ url('frontend/landing-v2/css/responsive-en.css') }}">
    @endif

</head>

<body>

    <!--header section start-->
    <header class="header">
        <!--start navbar-->
        <nav class="navbar navbar-expand-lg fixed-top bg-transparent">
            <div class="container">

                <a class="navbar-brand" href="index.php">
                    <img src="{{ url('frontend/landing-v2/img/logo-white-1x.png') }}" width="120" alt="logo"
                        class="img-fluid">
                </a>
                <ul class="navbar-nav mobile">
                    <li class="nav-item">
                        @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
                            @if ($localeCode != locale())
                                <a hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    class="nav-link page-scroll"> {{ $properties['native'] }} </a>
                            @endif
                        @endforeach
                    </li>
                </ul>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav desktop">
                        <li class="nav-item">
                            @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
                                @if ($localeCode != locale())
                                    <a hreflang="{{ $localeCode }}"
                                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                        class="nav-link page-scroll"> {{ $properties['native'] }} </a>
                                @endif
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--end navbar-->
    </header>
    <!--header section end-->

    <!--body content wrap start-->
    <div class="main">

        <!--hero section start-->
        <section class="hero-section hero-section-2 ptb-75">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-6 col-lg-6">
                        <div class="hero-content-left ptb-100 text-white">

                            @if (locale() == 'ar')
                                <p class="lead">تطبيق تمبه هو عبارة عن منصة على الإنترنت للألعاب ومنتجات ترفيه الأطفال
                                    في
                                    الكويت للعائلة والأطفال ، ويقدم مجموعة واسعة من الألعاب من مختلف العلامات التجارية ،
                                    مع
                                    أفكار فريدة ورائعة لتغليف الهدايا المجانية.</p>
                            @else
                                <p class="lead">
                                    Tambah App Is an Online Platform for Toys & Kids Entertainment Products in Kuwait
                                    for family and kids, offering wide range of toys from different brands , with unique
                                    & fascinating Free gifts wrapping ideas.
                                </p>
                            @endif

                            @if (config('setting.about_app.android_download_url') || config('setting.about_app.ios_download_url'))
                                <div class="button-group store-buttons d-flex">

                                    @if (config('setting.about_app.android_download_url'))
                                        <a href="{{ config('setting.about_app.android_download_url') }}">
                                            <img src="{{ url('frontend/landing-v2/img/google-play.png') }}"
                                                alt="">
                                        </a>
                                    @endif

                                    @if (config('setting.about_app.ios_download_url'))
                                        <a href="{{ config('setting.about_app.ios_download_url') }}">
                                            <img src="{{ url('frontend/landing-v2/img/app-store.png') }}"
                                                alt="">
                                        </a>
                                    @endif

                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="hero-animation-img">
                            <img class="img-fluid d-block animation-one"
                                src="{{ url('frontend/landing-v2/img/mob-1.png') }}" alt="animation image">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--hero section end-->
        <div class="container">
            <div class=" gams d-flex">
                <img src="{{ url('frontend/landing-v2/img/gams.png') }}" alt="">
            </div>
        </div>
    </div>
    <!--footer section start-->

    <!--footer bottom copyright start-->
    <div class="footer-bottom border-gray-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-7">
                    <div class="copyright-wrap small-text">
                        <p class="mb-0">
                            @if (locale() == 'ar')
                                شركة تمبه لتجارة الجملة والتجزئة
                            @else
                                Tambah Wholesale & Retail Trading
                            @endif
                        </p>
                        <span>
                            @if (locale() == 'ar')
                                القبلة ، مدينة الكويت ، شارع فهد السالم ، برج الوطنية ، الطابق الثاني ، مكتب رقم 3 ،
                                الرمز
                                البريدي 14000
                            @else
                                Qibla, Kuwait City, Fahad Al-Salem Street, Al Watiya Tower, 2nd Floor, Office No 3 Post
                                code 14000
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="social-list-wrap">
                        <ul class="social-list list-inline list-unstyled">

                            @if (config('setting.social.facebook'))
                                <li class="list-inline-item">
                                    <a href="{{ config('setting.social.facebook') }}" target="_blank" title="Facebook">
                                        <span class="ti-facebook"></span>
                                    </a>
                                </li>
                            @endif

                            @if (config('setting.social.twitter'))
                                <li class="list-inline-item">
                                    <a href="{{ config('setting.social.twitter') }}" target="_blank" title="Twitter">
                                        <span class="ti-twitter"></span>
                                    </a>
                                </li>
                            @endif

                            @if (config('setting.social.instagram'))
                                <li class="list-inline-item">
                                    <a href="{{ config('setting.social.instagram') }}" target="_blank"
                                        title="Instagram">
                                        <span class="ti-instagram"></span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--footer bottom copyright end-->

    <!--jQuery-->
    <script src="{{ url('frontend/landing-v2/js/jquery-3.5.0.min.js') }}"></script>
    <!--Bootstrap js-->
    <script src="{{ url('frontend/landing-v2/js/bootstrap.min.js') }}"></script>
    <!--custom js-->
    <script src="{{ url('frontend/landing-v2/js/scripts.js') }}"></script>

</body>

</html>
