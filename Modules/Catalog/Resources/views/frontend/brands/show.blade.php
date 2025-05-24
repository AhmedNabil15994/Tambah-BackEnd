@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.products.details.title') )
    @section('meta_description', optional($article)->seo_description ?? '')
    @section('meta_keywords', $article->seo_keywords ?? '')

@section('content')
    <div class="page-content">
        <div class="container">
            <div class="single-post">
                <h1>{{$article->title}}</h1>
                <img class="post-img" class="img-fluid" src="{{asset($article->image)}}" alt="" />
                <div class="post-content">
                  {!! $article->description !!}
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    @include('apps::frontend.components.paginator.paginator-scripts' ,
    ['defaultRoute' => route('frontend.articles.index')])
@endpush