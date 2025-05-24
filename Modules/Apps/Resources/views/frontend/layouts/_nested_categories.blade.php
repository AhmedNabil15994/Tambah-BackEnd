<ul class="submenu dropdown-menu nsted">
    @foreach ($children as $k => $category)
        @php $childrenCount = count($category->children); @endphp
        <li class="menu-item {{ $childrenCount ? 'menu-item-has-children arrowleft' : '' }}">
            <a href="{{ route('frontend.categories.products', $category->slug) }}" {{--  href="{{$childrenCount ? '#' : route('frontend.categories.products', $category->slug)}}"  --}}
                class="{{ $childrenCount ? 'dropdown-toggle' : '' }}">
                {{ $category->title }}
            </a>
            @if ($childrenCount)
                <span class="toggle-submenu hidden-mobile"></span>
                @include('apps::frontend.layouts._nested_categories', ['children' => $category->children])
            @endif
        </li>
    @endforeach
</ul>
