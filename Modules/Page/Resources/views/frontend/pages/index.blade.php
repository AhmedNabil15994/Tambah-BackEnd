@extends('apps::frontend.layouts.master')
@section('title', $page->title)
@section('content')
    <div class="page-content">

            <div class="second-header category-headr d-flex align-items-center" style="background-image: url('{{asset($page->banner_image ?? Setting::get('default_banner_pages') )}}')">
                <div class="container">
                    <h1> {{$page->title}}</h1>
                </div>
            </div>
        <div class="container">
            <div class="page-crumb mt-30">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('frontend.home') }}">
                                <i class="ti-home"></i> {{ __('apps::frontend.master.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active text-muted"
                            aria-current="page">{{ $page->title }}</li>
                    </ol>
                </nav>
            </div>

            {!! $page->description !!}
            <div class="single-post">
                <br><br>
                <div class="post-content">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('externalJs')

    <script></script>

@endsection