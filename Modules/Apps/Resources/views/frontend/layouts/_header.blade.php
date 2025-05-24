<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '--') || {{ config('app.name') }} </title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="author" content="{{ config('setting.app_name.' . locale()) ?? '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon"
        href="{{ config('setting.favicon') ? url(config('setting.favicon')) : url('frontend/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.css') }}">
    <link href="{{ url('admin/assets/global/plugins/grapick/grapick.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('frontend/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('frontend/css/smoothproducts.css') }}" type="text/css">
    <link href="{{ asset('SewidanField/plugins/ck-editor-5/css/ckeditor.css') }}" rel="stylesheet"
        id="style_components" type="text/css" />
    <link rel="stylesheet" href="{{ asset('frontend/css/vars.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style-' . locale() . '.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link href="{{ asset('frontend/plugins/live-search/jquery.autocomplete.css') }}" rel="stylesheet"
        id="style_components" type="text/css" />

    @stack('plugins_styles')

    {{-- Start - Bind Css Code From Dashboard Daynamic --}}
    {!! config('setting.custom_codes.css_in_head') ?? null !!}
    {{-- End - Bind Css Code From Dashboard Daynamic --}}


    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    @stack('styles')

    {{-- Start - Bind Js Code From Dashboard Daynamic --}}
    {!! config('setting.custom_codes.js_before_head') ?? null !!}
    {{-- End - Bind Js Code From Dashboard Daynamic --}}

    <style>
        .img-block {
            /* max-height: 300px; */
            overflow: hidden;
        }

        figure img {
            max-width: 100%;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0;
        }

        @media print {
            a[href]:after {
                content: none !important;
            }

            .contentPrint {
                width: 100%;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }
        }
    </style>

</head>
