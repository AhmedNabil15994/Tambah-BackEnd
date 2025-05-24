@if(count($records) > 0)
    <div class="container">
        <div class="home-products">
            <h3 class="slider-title">   {{$home->title}}</h3>
            <div class="owl-carousel products-slider">
                @foreach($records as $k => $record)
                    @include('catalog::frontend.products.components.single-product',['product'=> $record])
                @endforeach
            </div>
        </div>
    </div>
@endif
