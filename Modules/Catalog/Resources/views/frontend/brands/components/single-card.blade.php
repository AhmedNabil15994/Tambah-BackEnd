<div class="row article-row">
    <div class="col-md-6 wow fadeInLeft">
        <div class="article-block">
            <div class="img-block">
                <img class="img-fluid" src="{{asset($record->image)}}" alt=""/>
            </div>
        </div>
    </div>
    <div class="col-md-6 article-content-block wow fadeInRight">
        <div class="article-block">
            <h3 class="article-title"><a href="{{route('frontend.articles.show',$record->slug)}}">{{$record->title}}</a>
            </h3>
            <p>
                {!! $record->short_description !!}
            </p>
            <a class="btn btn-theme main-custom-btn" href="{{route('frontend.articles.show',$record->slug)}}">{{__('apps::frontend.Learn more')}}</a>
        </div>
    </div>
</div>