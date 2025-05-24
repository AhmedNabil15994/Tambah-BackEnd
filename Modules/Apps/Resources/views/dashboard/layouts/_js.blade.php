@if (is_rtl() == 'rtl')
    <script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-rtl.min.js"
            type="text/javascript">
    </script>
@else
    <script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript">
    </script>
@endif

<script src="/vendor/laravel-filemanager/js/single-stand-alone-button.js"></script>


<script>
    $(document).ready(function () {
        $('#clickmewow').click(function () {
            $('#radio1003').attr('checked', 'checked');
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(".emojioneArea").emojioneArea();
    });
</script>

<style>
    .emojionearea .emojionearea-picker.emojionearea-picker-position-top {
        margin-bottom: -286px !important;
        right: -14px;
        z-index: 90000000000000;
    }

    .emojionearea .emojionearea-button.active + .emojionearea-picker-position-top {
        margin-top: 0px !important;
    }
</style>

<script>
    // DELETE ROW FROM DATATABLE
    function deleteRow(url, rowId = '', flag = '') {
        var _token = $('input[name=_token]').val();

        bootbox.confirm({
            message: '{{__('apps::dashboard.general.delete_message')}}',
            buttons: {
                confirm: {
                    label: '{{__('apps::dashboard.general.yes_btn')}}',
                    className: 'btn-success'
                },
                cancel: {
                    label: '{{__('apps::dashboard.general.no_btn')}}',
                    className: 'btn-danger'
                }
            },

            callback: function (result) {
                if (result) {

                    $.ajax({
                        method: 'DELETE',
                        url: url,
                        data: {
                            _token: _token
                        },
                        success: function (msg) {
                            toastr["success"](msg[1]);
                            if (flag === 'advertising_groups') {
                                $('#advertisingGroup-' + rowId).remove();
                            } else {
                                $('#dataTable').DataTable().ajax.reload();
                            }
                        },
                        error: function (msg) {
                            toastr["error"](msg[1]);
                            $('#dataTable').DataTable().ajax.reload();
                        }
                    });

                }
            }
        });
    }

    function deleteAllChecked(url) {
        var someObj = {};
        someObj.fruitsGranted = [];

        $("input:checkbox").each(function () {
            var $this = $(this);

            if ($this.is(":checked")) {
                someObj.fruitsGranted.push($this.attr("value"));
            }
        });

        var ids = someObj.fruitsGranted;

        bootbox.confirm({
            message: '{{__('apps::dashboard.general.deleteAll_message')}}',
            buttons: {
                confirm: {
                    label: '{{__('apps::dashboard.general.delete_yes_btn')}}',
                    className: 'btn-success'
                },
                cancel: {
                    label: '{{__('apps::dashboard.general.delete_no_btn')}}',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {

                    $.ajax({
                        type: "GET",
                        url: url,
                        data: {
                            ids: ids,
                        },
                        success: function (msg) {

                            if (msg[0] == true) {
                                toastr["success"](msg[1]);
                                $('#dataTable').DataTable().ajax.reload();
                            } else {
                                toastr["error"](msg[1]);
                            }

                        },
                        error: function (msg) {
                            toastr["error"](msg[1]);
                            $('#dataTable').DataTable().ajax.reload();
                        }
                    });

                }
            }
        });
    }

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

    });

</script>

<script>
    $('.lfm').filemanager('image');

    $('.delete').click(function () {
        $(this).closest('.form-group').find($('.' + $(this).data('input'))).val('');
        $(this).closest('.form-group').find($('.' + $(this).data('preview'))).html('');
    });

</script>

<script src="https://js.pusher.com/5.0/pusher.min.js"></script>

<script>
    var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: '{{env('PUSHER_APP_CLUSTER')}}',
        forceTLS: true
    });

    pusher.subscribe('{{ config('core.config.constants.DASHBOARD_CHANNEL') }}').bind('{{ config('core.config.constants.DASHBOARD_ACTIVITY_LOG') }}', function (data) {

        $('#dataTable').DataTable().ajax.reload();

        if (data.activity.type === 'orders') {
            openActivity(data.activity);
        }

    });

    function playSound() {
        var audio = new Audio('{{ url('uploads/media/doorbell-5.mp3') }}');
        audio.play();
    }

    function openActivity(response) {
        playSound();
        // Show
        var showUrl = response.url;

        swal({
            title: response.description_{{locale()}},
            icon: "success",
            buttons: true,
            dangerMode: true,
        })
            .then((willDone) => {
                if (willDone) {
                    window.location.href = showUrl;
                }
            });
    }

    $("#showAllDeleted").bootstrapSwitch({
        onSwitchChange: function (e, state) {
            var data = {};
            if (state)
                data['deleted'] = 'only';

            $('#dataTable').DataTable().destroy();
            tableGenerate(data);
        }
    });

    function toggleBooleanSwitch(el,toggle_show_element) {
        var checked = $(el).is(':checked');
        if (checked) {
            $(toggle_show_element).show();
        } else {

            $(toggle_show_element).hide();
        }
    }
    
</script>

<script>
    var attributes = $("#attributeElements");
    var key = getRandomInt();
    $('#attributesSelect').on('select2:select', function (e) {
        var vData = e.params.data;
        var selectedText = vData.text.trim();
        var selectedValue = vData.id;
        var attributeLoader = $('#attributeLoader');

        if (vData.id != null) {

            attributeLoader.show();
            $('#attributeElement-' + selectedValue).empty();

            $.ajax({
                url: "{{route('api.get_by_id')}}?id=" + selectedValue + "&lang=" + "{{locale()}}",
                type: 'get',
                headers: {
                    "lang": "{{locale()}}",
                    'Content-Type': 'application/json'
                },
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                },
                success: function (data) {
                    attributeLoader.hide();
                },
                error: function (data) {
                    attributeLoader.hide();
                    displayErrors(data);
                },
                complete: function (data) {
                    handleProductAttributeDrawing(data.responseJSON.data);
                },
            });

        }
    });

    $('#attributesSelect').on('select2:unselect', function (e) {
        var data = e.params.data;
        $('#attributeElement-' + data.id).remove();
    });

    function handleProductAttributeDrawing(data) {
        var attributeInput = "";
        for (const attribute of data) {
            attributeInput += drawAttribute(attribute, key)
            key = getRandomInt();
        }
        attributes.prepend(attributeInput);
    }

    function drawAttribute(data, key = 0) {

        var input = inputDraw(data, key)

        var html = `
            <div class="form-group" id="attributeElement-${data.id}">
                <label class="col-md-2">
                    ${data.name}
                </label>`;

        if (data.type == "text") {
            html += `<div><input type="hidden" name="productAttributes[${key}][attribute_id]" value="${data.id}"/>${input}</div>`;
        } else {
            html += `<div class="col-md-9">
                        <input type="hidden" name="productAttributes[${key}][attribute_id]" value="${data.id}"/>
                         ${input}
                       <div class="help-block"></div>
                     </div></div>`;
        }
        return html;
    }

    function inputDraw(data, key = 0) {
        var input = "";

        if (data.type == "drop_down") {
            var options = "";
            for (const option of data.options) {
                options += `<option value="${option.id}">${option.value}</option>`
            }
            input = `<select class="form-control" ${data.validation.required == 1 ? 'required' : ''}  name="productAttributes[${key}][option_id]">
                    ${options}
                </select>`
        } else if (data.type == "radio") {

            let radio = `<div class"row">`
            for (const option of data.options) {
                radio += `
                   <div class="col-md-4">
                       <label for="radi_${option.id}">${option.value}</label>
                       <input type="radio" name="productAttributes[${key}][option_id]" id="radi_${option.id}" value="${option.id}" ${data.validation.required == 1 ? 'required' : ''}>
                   </div>
                `
            }
            input += radio + "</div>"
        } else if (data.type == "boolean") {
            input = `<input type="checkbox" class="" name="productAttributes[${key}][value]" ${data.validation.required == 1 ? 'required' : ''}>`
        } else if (data.type == "text") {
            input = drawMultiLangTextInput(data, key);
        } else if (data.type == "file") {
            input = drawFileInputWithPreview(data, key);
        } else {
            input = `<input type="${data.type}" ${data.validation.required == 1 ? 'required' : ''}  class="form-control"  name="productAttributes[${key}][value]" >`
        }

        return input
    }

    function drawMultiLangTextInput(data, key) {
        var input = `<ul class="nav nav-tabs">
            @foreach (config('translatable.locales') as $code)
        <li class="@if($loop->first) active @endif">
            <a data-toggle="tab"
               href="#txtInputs_{{$code}}_${key}">{{__('catalog::dashboard.products.form.tabs.input_lang',["lang"=>$code])}}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach (config('translatable.locales') as $code)
        <div id="txtInputs_{{$code}}_${key}" class="tab-pane fade @if($loop->first) in active @endif">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-2">${data.name}- {{ $code }}</label>
                    <div class="col-md-9">
                        <input type="${data.type}" ${data.validation.required == 1 ? 'required' : ''}  class="form-control"  name="productAttributes[${key}][value][{{$code}}]">
                    </div>
                </div>
            </div>
        </div>
            @endforeach
        </div>`;
        return input;
    }

    function drawFileInputWithPreview(data, key) {
        var input = `<input type="${data.type}" ${data.validation.required == 1 ? 'required' : ''}
                        class="form-control"  name="productAttributes[${key}][value]" onchange="readURL(this, 'imgUploadPreview-${key}', 'single');">`;
        input += `<img src="#" id="imgUploadPreview-${key}"
                     style="display: none;"
                     class="img-thumbnail img-responsive img-preview"
                     style="height: 100px; width: 100px;"
                     alt="attribute image">`;
        return input;
    }

    function getRandomInt() {
        return Math.floor(Math.random() * 9000000000) + 1000000000;
    }

</script>

@include('order::dashboard.shared._bulk_order_actions_js')
