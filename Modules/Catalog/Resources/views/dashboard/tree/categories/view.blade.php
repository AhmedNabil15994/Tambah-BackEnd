@foreach ($mainCategories as $category)
<ul>
		<li id="{{$category->id}}" data-jstree='{"opened":true}'>
				{{$category->title}}
				@if($category->dashboardChildren->count() > 0)
						@include('catalog::dashboard.tree.categories.view',['mainCategories' => $category->dashboardChildren])
				@endif
		</li>
</ul>
@endforeach
