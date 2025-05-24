@extends('apps::dashboard.layouts.app')
@section('title', __('catalog::dashboard.products.routes.index'))

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
                        <a href="#">{{ __('catalog::dashboard.products.routes.index') }}</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">

                        @permission('add_products')
                            <div class="table-toolbar">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group">
                                            <a href="{{ url(route('dashboard.products.create')) }}" class="btn sbold green">
                                                <i class="fa fa-plus"></i> {{ __('apps::dashboard.general.add_new_btn') }}
                                            </a>
                                        </div>

                                        <div class="btn-group">

                                            @include('apps::dashboard.components.import-excel-modal', [
                                                'module_name' => 'Catalog',
                                                'view_path' => 'products.html.import-cols-selectors',
                                            ])

                                        </div>

                                        <div class="btn-group">
                                            @include('catalog::dashboard.products.components.multi-photo-uploader')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endpermission


                        {{-- DATATABLE FILTER --}}
                        <div class="row">
                            <div class="portlet box grey-cascade">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>
                                        {{ __('apps::dashboard.datatable.search') }}
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="filter_data_table">
                                        <div class="panel-body">
                                            <form id="formFilter" class="horizontal-form">
                                                <div class="form-body">
                                                    <div class="row">

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{ __('apps::dashboard.datatable.form.status') }}
                                                                </label>
                                                                <div class="mt-radio-list">
                                                                    <label class="mt-radio">
                                                                        {{ __('apps::dashboard.datatable.form.active') }}
                                                                        <input type="radio" value="1"
                                                                            name="status" />
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        {{ __('apps::dashboard.datatable.form.unactive') }}
                                                                        <input type="radio" value="0"
                                                                            name="status" />
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{ __('apps::dashboard.datatable.form.categories') }}
                                                                </label>
                                                                <select name="categories"
                                                                    class="form-control select2-allow-clear">
                                                                    <option value="">
                                                                        ---
                                                                        {{ __('apps::dashboard.datatable.form.select_category') }}
                                                                        ---
                                                                    </option>
                                                                    @foreach ($allCategories as $k => $category)
                                                                        <option value="{{ $category['id'] }}">
                                                                            {{ $category->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{ __('apps::dashboard.datatable.form.vendors') }}
                                                                </label>
                                                                <select name="vendor_id"
                                                                    class="form-control select2-allow-clear">
                                                                    <option value="">
                                                                        ---
                                                                        {{ __('apps::dashboard.datatable.form.select_vendor') }}
                                                                        ---
                                                                    </option>
                                                                    @foreach ($activeVendors as $k => $vendor)
                                                                        <option value="{{ $vendor['id'] }}">
                                                                            {{ $vendor->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                            <div class="form-actions">
                                                <button class="btn btn-sm green btn-outline filter-submit margin-bottom"
                                                    id="search">
                                                    <i class="fa fa-search"></i>
                                                    {{ __('apps::dashboard.datatable.search') }}
                                                </button>
                                                <button class="btn btn-sm red btn-outline filter-cancel">
                                                    <i class="fa fa-times"></i>
                                                    {{ __('apps::dashboard.datatable.reset') }}
                                                </button>
                                            </div>
                                            @include('apps::dashboard.components.datatable.show-deleted-btn')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- END DATATABLE FILTER --}}


                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase">
                                    {{ __('catalog::dashboard.products.routes.index') }}
                                </span>
                            </div>
                        </div>

                        {{-- DATATABLE CONTENT --}}
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <a href="javascript:;" onclick="CheckAll()">
                                                {{ __('apps::dashboard.general.select_all_btn') }}
                                            </a>
                                        </th>
                                        <th>#</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.image') }}</th>
                                        @permission('status_products')
                                        <th>{{ __('catalog::dashboard.products.datatable.status') }}</th>
                                        @endpermission
                                        <th>{{ __('catalog::dashboard.products.datatable.title') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.sku') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.categories') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.vendor') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.price') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.qty') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.updated_at') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.created_at') }}</th>
                                        <th>{{ __('catalog::dashboard.products.datatable.options') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        @permission('delete_all')
                        <div class="row">
                            <div class="form-group">
                                <button type="submit" id="deleteChecked" class="btn red btn-sm"
                                    onclick="deleteAllChecked('{{ url(route('dashboard.products.deletes')) }}')">
                                    {{ __('apps::dashboard.datatable.delete_all_btn') }}
                                </button>
                            </div>
                        </div>
                        @endpermission
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')

    @include('apps::dashboard.components.datatable.image-uploading.scripts', [
        'uploading_route' => route('dashboard.products.update.photo'),
    ])

    @include('apps::dashboard.components.import-excel-scripts', [
        'route' => route('dashboard.products.import.excel'),
    ])

    <script>
        function tableGenerate(data = '') {

            var dataTable =
                $('#dataTable').DataTable({
                    "createdRow": function(row, data, dataIndex) {
                        if (data["deleted_at"] != null) {
                            $(row).addClass('danger');
                        }
                    },
                    ajax: {
                        url: "{{ url(route('dashboard.products.datatable')) }}",
                        type: "GET",
                        data: {
                            req: data,
                        },
                    },
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/{{ ucfirst(LaravelLocalization::getCurrentLocaleName()) }}.json"
                    },
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    responsive: !0,
                    order: [
                        [1, "desc"]
                    ],
                    columns: [{
                            data: 'id',
                            className: 'dt-center'
                        },
                        {
                            data: 'id',
                            className: 'dt-center'
                        },
                        {
                            data: "image",
                            orderable: false,
                            width: "1%",
                            render: function(data, type, row) {
                                return `<div id="photo_content_` + row.id + `">
                                            <span class="photo">
                                                <img  class="edit-photo" onclick="browseFile(` + row.id + `)" src="{{ asset('images/icons/upload-image.svg') }}"/>
                                                <img class="product_photo" src="` + data + `" width="50px"/>
                                            </span>
                                        </div>`;
                            }
                        },
                        @permission('status_products')
                        {
                            data: 'status',
                            className: 'dt-center'
                        },
                        @endpermission
                        {
                            data: 'title',
                            className: 'dt-center',
                            orderable: false
                        },
                        {
                            data: 'sku',
                            className: 'dt-center',
                            orderable: false
                        },
                        {
                            data: 'categories',
                            className: 'dt-center',
                            orderable: false,
                            width: '140px'
                        },
                        {
                            data: 'vendor',
                            className: 'dt-center',
                            orderable: false,
                            render: function(data, type, row) {
                                if (data != null) {
                                    var editUrl = '{{ route('dashboard.vendors.edit', ':id') }}';
                                    editUrl = editUrl.replace(':id', data.id);
                                    return `<a href="${editUrl}">${data.title}</a>`;
                                } else {
                                    return '---';
                                }
                            }
                        },
                        {
                            data: 'price',
                            className: 'dt-center',
                            orderable: false
                        },
                        {
                            data: 'qty',
                            className: 'dt-center',
                        },
                        {
                            data: 'updated_at',
                            className: 'dt-center',
                            width: '75px'
                        },
                        {
                            data: 'created_at',
                            className: 'dt-center',
                            width: '75px'
                        },
                        {
                            data: 'id'
                        },
                    ],
                    "fnDrawCallback": function() {
                        //Initialize checkbos for enable/disable user
                        $("[name='switch']").bootstrapSwitch({
                            size: "small",
                            onColor: "success",
                            offColor: "danger"
                        });
                    },
                    columnDefs: [{
                            targets: 0,
                            width: '20px',
                            className: 'dt-center',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return `<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                          <input type="checkbox" value="` + data + `" class="group-checkable" name="ids">
                          <span></span>
                        </label>`;
                            },
                        },
                        {
                            targets: -1,
                            responsivePriority: 1,
                            width: '255px',
                            title: '{{ __('catalog::dashboard.products.datatable.options') }}',
                            className: 'dt-center',
                            orderable: false,
                            render: function(data, type, full, meta) {

                                {{-- // Show Details
                                var showUrl = '{{ route("dashboard.products.show", ":id") }}';
                                showUrl = showUrl.replace(':id', data); --}}

                                // Edit
                                var editUrl = '{{ route('dashboard.products.edit', ':id') }}';
                                editUrl = editUrl.replace(':id', data);

                                // AddOns
                                var addOnsUrl = '{{ route('dashboard.products.add_ons', ':id') }}';
                                addOnsUrl = addOnsUrl.replace(':id', data);

                                // Delete
                                var deleteUrl = '{{ route('dashboard.products.destroy', ':id') }}';
                                deleteUrl = deleteUrl.replace(':id', data);

                                return `
                                {{-- @permission('show_products')
                                    <a href="` + showUrl + `" class="btn btn-sm yellow" title="Show">
                                    <i class="fa fa-eye"></i>
                                    </a>
                                @endpermission --}}
                                        @if (config('setting.products.toggle_addons') == 1)
                                    <a href="` + addOnsUrl + `" class="btn btn-sm green" title="Add Ons"><i class="fa fa-plus"></i></a>
                                @endif
                                    @permission('edit_products')
                                        <a href="` + editUrl + `" class="btn btn-sm blue" title="Edit">
                                    <i class="fa fa-edit"></i>
                                    </a>
                                @endpermission

                                @permission('delete_products')
                                @csrf
                                    <a href="javascript:;" onclick="deleteRow('` + deleteUrl + `')" class="btn btn-sm red">
                                    <i class="fa fa-trash"></i>
                                </a>
                                @endpermission`;
                            },
                        },
                    ],
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [10, 25, 50, 100, 500],
                        ['10', '25', '50', '100', '500']
                    ],
                    buttons: [{
                            extend: "pageLength",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.pageLength') }}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: "print",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.print') }}",
                            url: "{{ url(route('dashboard.products.export', 'print')) }}",
                            data: {
                                req: data,
                            },
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: "pdfHtml5",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.pdf') }}",
                            url: "{{ url(route('dashboard.products.export', 'pdf')) }}",
                            data: {
                                req: data,
                            },
                            exportOptions: {
                                stripHtml: true,
                                columns: ':visible',
                                columns: [1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                            }
                        },
                        /* {
                            extend: "excelHtml5",
                            className: "btn blue btn-outline ",
                            text: "{{ __('apps::dashboard.datatable.excel') }}",
                            url: "{{ url(route('dashboard.products.export', 'excel')) }}",
                            data: {
                                req: data,
                            },
                            exportOptions: {
                                stripHtml: true,
                                columns: ':visible',
                                columns: [1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                            }
                        }, */
                        {
                            extend: "excel",
                            className: "btn blue btn-outline ",
                            text: "{{ __('apps::dashboard.datatable.excel') }}",
                            url: "{{ url(route('dashboard.products.export', 'excel')) }}",
                            data: {
                                req: data,
                            },
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: "colvis",
                            className: "btn blue btn-outline",
                            text: "{{ __('apps::dashboard.datatable.colvis') }}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        }
                    ]
                });
        }

        jQuery(document).ready(function() {
            tableGenerate();
        });

        @permission('status_products')
        $(".product_status").prop("disabled", false)
        @endpermission
    </script>

@stop
