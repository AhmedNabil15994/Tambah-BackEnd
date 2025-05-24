@extends('apps::dashboard.layouts.app')
@section('title', __('setting::dashboard.social.routes.index'))

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
                        <a href="#">{{ __('setting::dashboard.social.routes.index') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            @include('apps::dashboard.layouts._msg')

            <div class="row">
                <form role="form" class="form-horizontal form-row-seperated" method="post"
                      action="{{route('dashboard.social.update')}}" enctype="multipart/form-data">
                    <div class="col-md-12">
                        @csrf
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle">
                                                {{__('setting::dashboard.social.form.tabs.info')}}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('setting::dashboard.social.form.tabs.general') }}
                                                    </a>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">
                                {{--  tab for content --}}
                                <div class="tab-content">
                                    {!!  field()->textarea('social[markting][facebook]', 
                                        'Facebook', config("setting.social.markting.facebook"), ['class' => 'form-control']); !!}

                                    {!!  field()->textarea('social[markting][snap]', 
                                        'Snap', config("setting.social.markting.snap"), ['class' => 'form-control']); !!}

                                    {!!  field()->textarea('social[markting][google]', 
                                        'Google', config("setting.social.markting.google"), ['class' => 'form-control']); !!}

                                    {!!  field()->textarea('social[markting][tiktok]', 
                                        'Tiktok', config("setting.social.markting.tiktok"), ['class' => 'form-control']); !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-9">
                                <button type="submit" id="submit" class="btn btn-lg blue">
                                    {{__('apps::dashboard.general.edit_btn')}}
                                </button>
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
        var sideMenuReviewProducts = $('#sideMenuReviewProducts');


        function paymentModeSwitcher(hide_class,show_id){
            $('.'+hide_class).hide();
            $('#'+show_id).show();
        }
    </script>

@stop
