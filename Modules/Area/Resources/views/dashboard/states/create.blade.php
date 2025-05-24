@extends('apps::dashboard.layouts.app')
@section('title', __('area::dashboard.states.routes.create'))
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="{{ url(route('dashboard.states.index')) }}">
                        {{__('area::dashboard.states.routes.index')}}
                    </a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="#">{{__('area::dashboard.states.routes.create')}}</a>
                </li>
            </ul>
        </div>

        <h1 class="page-title"></h1>

        <div class="row">
            <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                enctype="multipart/form-data" action="{{route('dashboard.states.store')}}">
                @csrf
                <div class="col-md-12">

                    {{-- RIGHT SIDE --}}
                    <div class="col-md-3">
                        <div class="panel-group accordion scrollable" id="accordion2">
                            <div class="panel panel-default">

                                <div id="collapse_2_1" class="panel-collapse in">
                                    <div class="panel-body">
                                        <ul class="nav nav-pills nav-stacked">
                                            <li class="active">
                                                <a href="#general" data-toggle="tab">
                                                    {{ __('area::dashboard.states.form.tabs.general') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- PAGE CONTENT --}}
                    <div class="col-md-9">
                        <div class="tab-content">

                            {{-- CREATE FORM --}}
                            <div class="tab-pane active fade in" id="general">

                                <div class="col-md-10">
                                    {{-- tab for lang --}}
                                    <ul class="nav nav-tabs">
                                        @foreach (config('translatable.locales') as $code)
                                        <li class="@if($loop->first) active @endif"><a data-toggle="tab"
                                                href="#first_{{$code}}">{{__('catalog::dashboard.products.form.tabs.input_lang',["lang"=>$code])}}</a>
                                        </li>
                                        @endforeach
                                    </ul>

                                    {{-- tab for content --}}
                                    <div class="tab-content">

                                        @foreach (config('translatable.locales') as $code)
                                        <div id="first_{{$code}}"
                                            class="tab-pane fade @if($loop->first) in active @endif">

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('area::dashboard.states.form.title')}} - {{ $code }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="title[{{$code}}]" class="form-control"
                                                        data-name="title.{{$code}}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>



                                        </div>
                                        @endforeach

                                    </div>
                                    {!! field()->select('country_id',
                                    __('user::frontend.addresses.form.select_country'),$countries->pluck('title','id')->toArray()
                                    ,null,[
                                    'class' => 'select-detail select2 form-control',
                                    'onchange' => 'getCitiesByCountryId(this)',
                                    ]) !!}

                                    <div id="state_container">
                                        {!! field()->select('state_id', __('area::dashboard.states.form.cities'),[]) !!}
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('area::dashboard.states.form.status')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                name="status">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- END CREATE FORM --}}
                        </div>
                    </div>

                    {{-- PAGE ACTION --}}
                    <div class="col-md-12">
                        <div class="form-actions">
                            @include('apps::dashboard.layouts._ajax-msg')
                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-lg blue">
                                    {{__('apps::dashboard.general.add_btn')}}
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function getCitiesByCountryId(country) {

        country = $(country);
        var state_container = $('#state_container');
        var area_selector = $('#state_id');
        var id = country.val();

        $.ajax({
            method: "GET",
            url: '{{route('dashboard.states.get_child_area_by_parent')}}?type=city&parent_id=' + id,
            beforeSend: function () {
                area_selector.empty();
                state_container.hide();
            },
            success: function (data) {
                area_selector.append('<option selected>{{__('user::frontend.addresses.form.states')}}</option>');
                var options = '';
                $.each(data.data, function (index, city) {
                    options += '<option value="' + city.id + '">' + city.title + '</option>';
                });

                area_selector.append(options);
                state_container.show();
            }
        });
    }
</script>
@stop