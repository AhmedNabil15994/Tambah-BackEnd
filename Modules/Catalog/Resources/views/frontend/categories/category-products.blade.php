@extends('apps::frontend.layouts.master')
{{--  @extends('apps::frontend.layouts.master',['headerCategories' => $categories])  --}}
@section('title', __('catalog::frontend.category_products.title'))
@push('styles')
    <style>
        .second-header.category-headr h1 {
            color: #000;
        }
    </style>
@endpush
@section('content')
    @if (!empty($category))
        <div class="second-header category-headr d-flex align-items-center"
            style="background-image: url('{{ asset($category->banner_image ?? Setting::get('default_banner_categories')) }}')">
            <div class="container">
                <h1> {{ $category->title }}</h1>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="inner-page">
            <div class="row">
                @include('catalog::frontend.categories._filter')
                <div class="col-md-9">
                    <div class="toolbar-products">
                        <div class="row">
                            <div class="col-md-6 col-7">
                                <div class="toolbar-per">
                                    <span>
                                        {{ __('catalog::frontend.category_products.show_result') }}
                                        <b><span id="productsCount">{{ $products_count }}</span></b>

                                        {{ __('catalog::frontend.category_products.product_from') }}
                                        <b>{{ $count_all_products }}</b> </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-5 pr0">
                                <div class="toolbar-sort">
                                    <select class="sorter-options form-control">
                                        <!--                                    <option selected="selected" disabled value=""> رتب حسب</option>-->
                                        <option value="price">
                                            {{ __('catalog::frontend.category_products.newly added') }}
                                        </option>
                                        <option value="price">
                                            {{ __('catalog::frontend.category_products.most bought') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-products">
                        <div class="row" id="records_container">
                            {!! $products !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('plugins-scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endpush

@push('scripts')
    <script>
        // Slider range price

        $('.slider-range-price').each(function() {
            var min = parseInt($(this).data('min'));
            var max = parseInt($(this).data('max'));
            var unit = $(this).data('unit');
            var value_min = parseInt($(this).data('value-min'));
            var value_max = parseInt($(this).data('value-max'));
            var label_reasult = $(this).data('label-reasult');
            var t = $(this);
            $(this).slider({
                range: true,
                min: min,
                max: max,
                values: [value_min, value_max],
                slide: function(event, ui) {
                    var result = label_reasult + " <span>" + unit + ui.values[0] +
                        ' </span> - <span> ' + unit + ui.values[1] + '</span>';
                    t.closest('.price_slider_wrapper').find('.price_slider_amount').html(result);

                    /************* Edited By Mahmoud Elzohairy **************/
                    t.closest('.price_slider_wrapper').find('#hiddenPriceSliderAmount #priceFrom').val(
                        ui.values[0]);
                    t.closest('.price_slider_wrapper').find('#hiddenPriceSliderAmount #priceTo').val(ui
                        .values[1]);
                }
            });
        });
    </script>
@endpush
