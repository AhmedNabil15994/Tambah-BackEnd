
<!-- Button trigger modal -->
<button type="button" class="btn btn-danger home_sections_filter_btn" data-toggle="modal" data-target="#home_sections_filter" >
    <i class="fa fa-filter"></i>
    Filter Home Sections
</button>

<!-- Modal -->
<div class="modal fade" id="home_sections_filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="caption">
                    <i class="fa fa-filter"></i>
                    Filter Home Sections
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="tools">
                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="filter_data_table">
                            <div class="panel-body">
                                <form id="sections_filter_form" class="horizontal-form">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">
                                                        {{__('apps::dashboard.datatable.form.date_range')}}
                                                    </label>
                                                    <div id="reportrange" class="btn default form-control">
                                                        <i class="fa fa-calendar"></i> &nbsp;
                                                        <span> </span>
                                                        <b class="fa fa-angle-down"></b>
                                                        <input type="hidden" name="from">
                                                        <input type="hidden" name="to">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">
                                                        {{__('apps::dashboard.datatable.form.status')}}
                                                    </label>
                                                    <div class="mt-radio-list">
                                                        <label class="mt-radio">
                                                            {{__('apps::dashboard.datatable.form.active')}}
                                                            <input type="radio" value="1" name="status" />
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            {{__('apps::dashboard.datatable.form.unactive')}}
                                                            <input type="radio" value="0" name="status" />
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger sections_filter_cancel">
                    <i class="fa fa-times"></i>
                    {{__('apps::dashboard.datatable.reset')}}
                </button>
                <button type="button" class="btn btn-primary filter-submit" id="sections_filter">
                    <i class="fa fa-search"></i>
                    {{__('apps::dashboard.datatable.search')}}
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="/admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="/admin/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="/admin/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
@if (is_rtl() == 'rtl')
    <script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-rtl.min.js"
            type="text/javascript">
    </script>
@else
    <script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript">
    </script>
@endif
<script>
    $(document).ready(function () {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            if (start.isValid() && end.isValid()) {
                $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                $('input[name="from"]').val(start.format('YYYY-MM-DD'));
                $('input[name="to"]').val(end.format('YYYY-MM-DD'));
            } else {
                $('#reportrange .form-control').val('Without Dates');
                $('input[name="from"]').val('');
                $('input[name="to"]').val('');
            }
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '{{__('apps::dashboard.general.date_range.today')}}': [moment(), moment()],
                '{{__('apps::dashboard.general.date_range.yesterday')}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '{{__('apps::dashboard.general.date_range.7days')}}': [moment().subtract(6, 'days'), moment()],
                '{{__('apps::dashboard.general.date_range.30days')}}': [moment().subtract(29, 'days'), moment()],
                '{{__('apps::dashboard.general.date_range.month')}}': [moment().startOf('month'), moment().endOf('month')],
                '{{__('apps::dashboard.general.date_range.last_month')}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            @if (is_rtl() == 'rtl')
            opens: 'left',
            @endif
            buttonClasses: ['btn'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-danger',
            format: 'YYYY-MM-DD',
            separator: 'to',
            locale: {
                applyLabel: '{{__('apps::dashboard.general.date_range.save')}}',
                cancelLabel: '{{__('apps::dashboard.general.date_range.cancel')}}',
                fromLabel: 'from',
                toLabel: 'to',
                customRangeLabel: '{{__('apps::dashboard.general.date_range.custom')}}',
                firstDay: 1
            }
        }, cb);

        cb(start, end);

        $('#sections_filter').click(function () {

            var $form = $("#sections_filter_form");
            var data = getFormData($form);
            $("#records_container").text('');
            $('#home_sections_filter').modal('hide');
            global_data = data;
            getPagination(1,'{{route('frontend.home')}}',null,data);

        });

        $('.sections_filter_cancel').click(function () {

            document.getElementById("sections_filter_form").reset();
            $("#records_container").text('');
            $('#home_sections_filter').modal('hide');
            global_data = '';
            getPagination(1,'{{route('frontend.home')}}',null);

        });
    });

</script>