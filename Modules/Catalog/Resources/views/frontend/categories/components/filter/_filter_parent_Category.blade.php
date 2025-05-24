
<li class="has-child arrow-sub cat-item cat-parent {{ !empty($category) && $cat->children()->active()->find($category->id) ? 'show-sub' : ''}}"><a href="#">
        {{ $cat->title }}<span>({{$cat->active_products_count}})</span>
    </a>
    <span class="arrow-cate"></span>

    <ul class="children"
        style="display:{{ !empty($category) && $cat->children()->active()->find($category->id) ? 'block' : 'none'}}">
        @foreach($cat->children()->active()->get() as $subCat)
            @if($subCat->children()->active()->count())
                @include('catalog::frontend.categories.components.filter._filter_parent_Category',[
                    'cat' => $subCat
                ])
            @else
                @include('catalog::frontend.categories.components.filter._filter_last_Category',[
                    'cat' => $subCat
                ])
            @endif
        @endforeach
    </ul>
</li>
