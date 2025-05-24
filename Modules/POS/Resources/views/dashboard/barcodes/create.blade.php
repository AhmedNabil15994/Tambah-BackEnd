@extends('apps::dashboard.layouts.app')
@section('title', __('pos::dashboard.barcode.routes.create'))
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
                        <a href="{{ url(route('dashboard.barcode.index')) }}">
                            {{__('pos::dashboard.barcode.routes.index')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('pos::dashboard.barcode.routes.create')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data" action="{{route('dashboard.barcode.store')}}">
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
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('pos::dashboard.barcode.form.tabs.general') }}
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

                                <div class="tab-pane active fade in" id="global_setting">


                                
                                    <div class="tab-content px-1 pt-1">

                                        

                                        <div class="col-md-10">

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.name')}}
                                                  
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" max="255" name="name"
                                                           class="form-control"
                                                           data-name="name">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.description')}}
                                                  
                                                </label>
                                                <div class="col-md-9">
                                                    <textarea  name="description"
                                                           class="form-control"
                                                           data-name="description"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.is_continuous')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                           data-size="small"
                                                           value="1"
                                                           
                                                           name="is_continuous">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.top_margin')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="top_margin"
                                                           
                                                        
                                                           name="top_margin">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.left_margin')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="left_margin"
                                                           
                                                        
                                                           name="left_margin">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.width')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="width"
                                                           
                                                        
                                                           name="width">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.height')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="height"
                                                           
                                                        
                                                           name="height">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.paper_width')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="paper_width"
                                                           
                                                        
                                                           name="paper_width">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.paper_height')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="paper_height"
                                                           
                                                        
                                                           name="paper_height">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.stickers_in_one_row')}} 
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="stickers_in_one_row"
                                                           min="1"
                                                          
                                                           name="stickers_in_one_row">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.row_distance')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="row_distance"
                                                           
                                                        
                                                           name="row_distance">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.col_distance')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="col_distance"
                                                           
                                                        
                                                           name="col_distance">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.stickers_in_one_sheet')}} 
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="number"
                                                           class="form-control"
                                                           data-name="stickers_in_one_sheet"
                                                           min="1"
                                                          
                                                           name="stickers_in_one_sheet">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                           

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('pos::dashboard.barcode.form.status')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                           data-size="small"
                                                           value="1"
                                                           checked
                                                           name="status">
                                                    <div class="help-block"></div>
                                                </div>
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
                                    <a href="{{url(route('dashboard.barcode.index')) }}" class="btn btn-lg red">
                                        {{__('apps::dashboard.general.back_btn')}}
                                    </a>
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

    <script></script>

@endsection
