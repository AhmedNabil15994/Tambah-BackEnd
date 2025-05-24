@extends('apps::dashboard.layouts.app')
@section('title', __('variation::dashboard.options.routes.update'))
@section('content')

    @include('variation::dashboard.html.option_values_html',['id' => '::index'])

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ url(route('dashboard.options.index')) }}">
                            {{__('variation::dashboard.options.routes.index')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('variation::dashboard.options.routes.update')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data" action="{{route('dashboard.options.update',$option->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">

                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#genral" data-toggle="tab">
                                                        {{ __('variation::dashboard.options.form.tabs.general') }}
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#option_values" data-toggle="tab">
                                                        {{ __('variation::dashboard.options.form.tabs.option_values') }}
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

                                {{-- UPDATE FORM --}}
                                <div class="tab-pane active fade in" id="genral">
                                    <h3 class="page-title">{{__('variation::dashboard.options.form.tabs.general')}}</h3>
                                    <div class="col-md-10">

                                        @foreach (config('translatable.locales') as $code)
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('variation::dashboard.options.form.title')}} - {{ $code }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="title[{{$code}}]" class="form-control"
                                                           data-name="title.{{$code}}"
                                                           value="{{ $option->getTranslation('title',$code) }}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('variation::dashboard.options.form.type')}}
                                            </label>
                                
                                            <div class="col-md-9">
                                                <div class="md-radio-inline">
                                                    @foreach(\Modules\Variation\Entities\Option::typesForSelect() as $type => $display_name)
                                
                                                        <label class="mt-radio" style="margin-right: 5px;margin-left: 5px;">
                                                            <input type="radio" name="value_type" data-name="value_type" id="value_type" value="{{$type}}"  
                                                            onchange="switchInputs()"
                                                                    {{$type == $option->value_type ? 'checked' : ''}}>
                                                            {{$display_name}}
                                                            <span></span>
                                                        </label>
                                
                                                    @endforeach
                                                    <div class="help-block" style="color: red"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('variation::dashboard.options.form.status')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                       name="status" {{($option->status == 1) ? ' checked="" ' : ''}}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('variation::dashboard.options.form.option_as_filter')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" data-size="small"
                                                       name="option_as_filter" {{($option->option_as_filter == 1) ? ' checked="" ' : ''}}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        @if ($option->trashed())
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('area::dashboard.update.form.restore')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                           data-size="small" name="restore">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="option_values">
                                    <h3 class="page-title">{{__('variation::dashboard.options.form.tabs.option_values')}}</h3>
                                    <div class="col-md-10">
                                        <div class="option_values_form">
                                            @foreach ($option->values as $key => $optionValues)
                                                @include('variation::dashboard.html.option_values_html',['id' => $optionValues->id,'model' => $optionValues,'option_type' => $option->value_type])
                                            @endforeach
                                        </div>

                                        <button id="copy" type="button"
                                                class="btn green btn-lg mt-ladda-btn ladda-button btn-circle btn-outline"
                                                data-style="slide-down" data-spinner-color="#333">
                                        <span class="ladda-label">
                                            <i class="icon-plus"></i>
                                        </span>
                                        </button>
                                    </div>
                                </div>
                                {{-- END UPDATE FORM --}}
                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{__('apps::dashboard.general.edit_btn')}}
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
        $(function () {
            var html = $("div.option_values_form_copier").html();

            $('#copy').click(function (event) {

                var rand = Math.floor(Math.random() * 9000000000) + 1000000000;
                var html2 = replaceAll(html, '::index', rand);
                $(".option_values_form").append(html2);
                switchInputs();
            });

            $(".option_values_form").on("click", ".remove_html", function (e) {
                e.preventDefault();
                $(this).closest('.content').remove();
            });

        });


        function checkFunction() {

            $('[name="option_value_status[]"]').change(function () {
                if ($(this).is(':checked'))
                    $(this).next().prop('disabled', true);
                else
                    $(this).next().prop('disabled', false);
            });
        }

        function switchInputs(){
            
            $('.color-input').hide();
            if($('input[name="value_type"]:checked').val() == 'color'){

                $('.color-input').show();
            }
        }

        $('#add_dates').change(function () {
            if (this.checked) {
                $('#dates_container').show();
            } else {

                $('#dates_container').hide();
            }
        });

        function escapeRegExp(string) {
            return string.replace(/[.+?^${}()|[]\]/g, "\$&");
        }

        function replaceAll(str, term, replacement) {
            return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
        }

        jQuery(document).ready(function() {
            switchInputs();
        });
    </script>
@stop
