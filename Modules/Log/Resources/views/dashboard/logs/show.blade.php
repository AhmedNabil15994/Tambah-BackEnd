@extends('apps::dashboard.layouts.app')
@section('title', __('log::dashboard.logs.routes.details'))
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
                        <a href="{{ route('dashboard.logs.index') }}">{{ __('log::dashboard.logs.routes.index') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('log::dashboard.logs.routes.details') }}</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="no-print">
                        <div class="col-md-3">
                            <ul class="ver-inline-menu tabbable margin-bottom-10">
                                <li class="active">
                                    <a data-toggle="tab" href="#general">
                                        <i class="fa fa-cog"></i>
                                        {{ __('log::dashboard.logs.show.form.tabs.general') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9 contentPrint">
                        <div class="tab-content">

                            <div class="tab-pane active" id="general">
                                <div class="invoice-content-2 busered">
                                    <div class="row invoice-head">

                                        <div class="row invoice-body">
                                            <div class="col-xs-12 table-responsive" style="margin-top: 20px">
                                                @if (isset($model->changes()['old']))
                                                    <h4>
                                                        # {{ __('log::dashboard.logs.show.form.old') }}
                                                    </h4>
                                                    <table class="table table-bordered ">

                                                        <tbody>

                                                            @foreach ($model->changes()['old'] as $key => $item)
                                                                <tr>
                                                                    <td class="invoice-title" style="width: 200px">
                                                                        {{ $key }}
                                                                    </td>
                                                                    <td>
                                                                        {{ in_array($key, ['created_at', 'updated_at']) ? date('Y-m-d H:i a', strtotime($item)) : $item }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                        <thead>
                                                    </table>
                                                @endif

                                                @if (isset($model->changes()['attributes']))
                                                    <h4>
                                                        # {{ __('log::dashboard.logs.show.form.changes') }}
                                                    </h4>
                                                    <table class="table table-bordered ">

                                                        <tbody>

                                                            @foreach ($model->changes()['attributes'] as $key => $item)
                                                                <tr>
                                                                    <td class="invoice-title" style="width: 200px">
                                                                        {{ $key }}
                                                                    </td>
                                                                    <td>
                                                                        @if (is_array($item))
                                                                            @php
                                                                                $v = implode(',', $item);
                                                                            @endphp
                                                                            <strong>{{ $v }}</strong>
                                                                        @else
                                                                            {{ in_array($key, ['created_at', 'updated_at']) ? date('Y-m-d H:i a', strtotime($item)) : $item }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                        <thead>
                                                    </table>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-xs-4">
                        <a href="{{ url(route('dashboard.logs.index')) }}" class="btn btn-lg red">
                            {{ __('apps::dashboard.general.back_btn') }}
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
@stop

@section('scripts')
    <script>
        jQuery(document).ready(function() {});
    </script>

@stop
