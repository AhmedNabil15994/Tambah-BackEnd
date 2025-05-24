@if (count($records) > 0)
    <div class="categories mb-40">
        <div class="container">
            <h3 class="slider-title"> {{ $home->title }}</h3>
            <div class="row">
                @foreach ($records as $k => $record)
                    <div class="col-md-3 col-6">
                        <div class="cat-block text-center">
                            <a href="{{ route('frontend.categories.products', $record->slug) }}">
                                <div class="img-block" style="height: 255px;">
                                    <img src="{{ asset($record->image) }}" class="img-fluid"
                                        alt="{{ $record->title }}" />
                                </div>
                                <h4 style="padding: 7px 0px 18px 0px;">
                                    {{ $record->title }}
                                </h4>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
@endif
