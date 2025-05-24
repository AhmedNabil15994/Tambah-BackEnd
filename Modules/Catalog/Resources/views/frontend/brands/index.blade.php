@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.products.details.title') )

@section('content')
    <div class="page-content">
        <div class="container">
            <div class="sectio-title text-center">
                <p>{{ config('setting.app_name.'.locale()) ?? '' }}</p>
                <h2>{{__('apps::frontend.Behind the brand')}}</h2>
            </div>

            <div id="records_container"></div>

            @include('apps::frontend.components.ajax-loader')
        </div>
    </div>
@endsection

@push('scripts')
    @include('apps::frontend.components.paginator.paginator-scripts' ,
    ['defaultRoute' => route('frontend.articles.index')])
@endpush