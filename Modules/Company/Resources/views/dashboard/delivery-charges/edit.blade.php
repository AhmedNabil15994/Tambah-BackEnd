@extends('apps::dashboard.layouts.app')
@section('title', __('company::dashboard.delivery_charges.update.title'))
@section('css')
    <style>
        .loading-card {
            background-color: #f3f7fb;
            padding: 14px 21px;
            border-radius: 5px;
            border: 1px solid #9e9e9e4f;
        }
    </style>
@stop
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
                        <a href="{{ url(route('dashboard.delivery-charges.index')) }}">
                            {{__('company::dashboard.delivery_charges.index.title')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('company::dashboard.delivery_charges.update.title')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data"
                      action="{{route('dashboard.delivery-charges.update',$company->id)}}">
                    @csrf
                    @method('PUT')

                    <div class="tab-content">
                        @php $cities = $country->cities;@endphp

                        <div class="col-md-12">

                            {{--                                    RIGHT SIDE--}}
                            <div class="col-md-3">
                                <div class="panel-group accordion scrollable" id="accordion2">
                                    <div class="panel panel-default">

                                        <div id="collapse_2_1" class="panel-collapse in">
                                            <div class="panel-body">
                                                <ul class="nav nav-pills nav-stacked">
                                                    @foreach ($cities as $key => $city)
                                                        <li class="{{ $key == 0 ? 'active' : '' }}">
                                                            <a href="#cities_{{ $city->id }}" data-toggle="tab"
                                                               onclick="getStates(this,'{{$city->id}}')"
                                                               data-loaded="{{ $loop->first ? 'true' : 'false' }}">
                                                                {{ $city->title }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--PAGE CONTENT--}}
                            <div class="col-md-9">
                                <div class="tab-content">
                                    @foreach ($cities as $key2 => $city)
                                        <div class="tab-pane fade in {{ $key2 == 0 ? 'active' : '' }}"
                                             id="cities_{{ $city->id }}">
                                            @if($loop->first)
                                                @include('company::dashboard.delivery-charges.components.states', compact('city'))
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{--                                    PAGE ACTION--}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @include('apps::dashboard.layouts._ajax-msg')
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-lg green">
                                            {{__('apps::dashboard.general.edit_btn')}}
                                        </button>
                                        <a href="{{url(route('dashboard.delivery-charges.index')) }}"
                                           class="btn btn-lg red">
                                            {{__('apps::dashboard.general.back_btn')}}
                                        </a>
                                    </div>
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

        function makeAllActiveCheckbox(element, elementClass) {

            var checked = $(element).is(':checked');

            $('.' + elementClass).each(function (event) {
                $(this).prop('checked', checked).change();
            });
        }


        function copyToAllInputValues(type, elementClass) {

            var deliveryTimeInputVal = $('#updateForm').find('.' + elementClass).filter(':visible:first').val();
            console.log(deliveryTimeInputVal);
            $('.' + elementClass).each(function () {
                $(this).val(deliveryTimeInputVal);
            });
        }

        function getStates(element, city) {
            if ($(element).attr('data-loaded') == 'false') {

                var url = '{{ route("dashboard.delivery-charges.get-states", [":city", $company->id]) }}';
                url = url.replace(':city', city);

                $.ajax({

                    url: url,
                    type: 'GET',

                    beforeSend: function () {
                        $('#cities_' + city).text('').append('<center style="margin-top: 100px;"><span class ="loading-card">{{__('apps::dashboard.general.loader')}} ....</span></center>');
                    },
                    success: function (data) {
                        $('#cities_' + city).text('').append(data.html);
                        $(element).attr('data-loaded', 'true');
                        $('.make-switch').bootstrapSwitch();
                    }
                });
            }
        }
    </script>
@stop
