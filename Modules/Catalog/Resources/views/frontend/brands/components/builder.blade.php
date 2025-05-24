@foreach($articles as $record)
    @include('catalog::frontend.brands.components.single-card',compact('record'))
@endforeach
@include('apps::frontend.components.paginator.paginator', ['paginator' => $articles , 'getMoreRoute' => route('frontend.articles.index')])