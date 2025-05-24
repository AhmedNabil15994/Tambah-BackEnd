@if(!empty($first) && $first)
    <li class="has-child arrow-sub cat-item cat-parent show-sub">
        <a href="#" onclick="filterByCategory('{{$cat->slug}}')">
            {{ $cat->title }}<span>({{$cat->active_products_count}})</span>
        </a>
    </li>
@else
    <li class="cat-item {{ !empty($category) && $cat->id == $category->id ? 'active' : '' }}">
        <a href="#" onclick="filterByCategory('{{$cat->slug}}')">
            {{ $cat->title }}
            <span>({{$cat->active_products_count}})</span>
        </a>
    </li>
@endif
