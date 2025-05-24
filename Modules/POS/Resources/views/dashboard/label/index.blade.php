@extends('apps::dashboard.layouts.app')
@section('title', __('pos::dashboard.labels.routes.index'))
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css"
        integrity="sha512-TsNN9S3X3jnaUdLd+JpyR5yVSBvW9M6ruKKqJl5XiBpuzzyIMcBavigTAHaH50MJudhv5XIkXMOwBL7TbhXThQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.themes.min.css"
        integrity="sha512-5EKwOr+n8VmXDYfE/EObmrG9jmYBj/c1ZRCDaWvHMkv6qIsE60srmshD8tHpr9C7Qo4nXyA0ki22SqtLyc4PRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.css"
        integrity="sha512-zrPsLVYkdDha4rbMGgk9892aIBPeXti7W77FwOuOBV85bhRYi9Gh+gK+GWJzrUnaCiIEm7YfXOxW8rzYyTuI1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .item-barcode {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            width: 100%;
        }

        .barcode {
            border: 1px dashed #000;
            padding: 8px 0px;
        }

        .printer-container {
            /* border: 1px solid #000 */
        }

        .label-border-outer {
            border: 1px solid #000
        }

        .label-border-internal {
            display: flex !important;
            flex-wrap: wrap
        }

        .label-print {
            display: inline-block;
            border: 1px dashed gray !important;
            color: #000;
            -webkit-box-sizing: border-box;
            /* Safari/Chrome, other WebKit */
            -moz-box-sizing: border-box;
            /* Firefox, other Gecko */
            box-sizing: border-box;
            /* Opera/IE 8+ */
        }

        .label-print .items {
            height: inherit;
            padding: 5px 0;
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap
        }

        .page-break {

            page-break-after: always;
        }

        /* ============ ============ */
        @media print {

            .label,
            .label-print,
            .barcode {

                border: none !important
            }

            .col-sm-1,
            .col-sm-2,
            .col-sm-3,
            .col-sm-4,
            .col-sm-5,
            .col-sm-6,
            .col-sm-7,
            .col-sm-8,
            .col-sm-9,
            .col-sm-10,
            .col-sm-11,
            .col-sm-12 {
                float: left;
            }

            .col-sm-12 {
                width: 100%;
            }

            .col-sm-11 {
                width: 91.66666667%;
            }

            .col-sm-10 {
                width: 83.33333333%;
            }

            .col-sm-9 {
                width: 75%;
            }

            .col-sm-8 {
                width: 66.66666667%;
            }

            .col-sm-7 {
                width: 58.33333333%;
            }

            .col-sm-6 {
                width: 50%;
            }

            .col-sm-5 {
                width: 41.66666667%;
            }

            .col-sm-4 {
                width: 33.33333333%;
            }

            .col-sm-3 {
                width: 25%;
            }

            .col-sm-2 {
                width: 16.66666667%;
            }

            .col-sm-1 {
                width: 8.33333333%;
            }

            .barcode-image img {
                width: 70% !important;
            }

            .item-barcode {
                text-align: center;

            }

            .printButton {
                display: none !important;
            }

            #printer {
                border: none !important;
                overflow: hidden !important;
            }
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
                        <a href="#">{{ __('pos::dashboard.labels.routes.index') }}</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">


                        {{-- start form --}}
                        <form class="horizontal-form" id="form-button">
                            {{-- DATATABLE FILTER --}}
                            <div class="row">
                                <div class="portlet box grey-cascade">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gift"></i>
                                            {{ __('apps::dashboard.datatable.search') }}
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="filter_data_table">
                                            <div class="panel-body">
                                                {{-- start form --}}

                                                @csrf
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="search"
                                                                    id="searchProduct"
                                                                    placeholder="Search By Product Name Or Sku">
                                                                {{-- <div class="input-group-btn">
                                                                  <button class="btn btn-default" id="searchProduct" type="submit">
                                                                    <i class="glyphicon glyphicon-search"></i>
                                                                  </button>
                                                                </div> --}}
                                                            </div>
                                                            {{-- start form --}}

                                                            <div id="result-search" style="display: none">
                                                                <table class="table">
                                                                    <thead>
                                                                        <th>Product</th>
                                                                        <th>No. Labels</th>
                                                                    </thead>
                                                                    <tbody id="result-body">

                                                                    </tbody>
                                                                </table>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>






                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- END DATATABLE FILTER --}}

                            {{-- DATATABLE FILTER --}}
                            <div class="row">
                                <div class="portlet box grey-cascade">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gift"></i>
                                            {{ __('pos::dashboard.labels.datatable.show_in') }}
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="show_info">
                                            <div class="panel-body">
                                                {{-- start form --}}
                                                <div>
                                                    <div class="row">
                                                        @foreach (['show_product_name', 'show_sku', 'show_price', 'show_variation', 'show_supplier'] as $item)
                                                            <div class="col-md-3" style="margin-top: 25px">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label
                                                                            style="text-transform: capitalize">{{ str_replace(['show_', '_'], ['', ' '], $item) }}</label>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input style="width: 50px" type="checkbox"
                                                                            value="1" name="{{ $item }}" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <div class="col-md-3" style="margin-top: 25px">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label style="text-transform: capitalize">set Size to
                                                                        Paper</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input style="width: 50px" type="checkbox"
                                                                        value="1" name="setSizePage" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2" style="margin-top: 25px">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <label style="text-transform: capitalize">Barcode
                                                                        Type</label>
                                                                </div>
                                                                <div class="col-md-7">


                                                                    <select class="form-control" name="barcode_type">
                                                                        @foreach (config('barcode.type') as $item)
                                                                            <option
                                                                                {{ $item == config('barcode.default_type') ? 'selected' : '' }}>
                                                                                {{ $item }}</option>
                                                                        @endforeach

                                                                    </select>




                                                                </div>
                                                            </div>


                                                        </div>

                                                        <div class="col-md-5" style="margin-top: 25px">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label style="text-transform: capitalize">Barcode
                                                                        Setting</label>
                                                                </div>
                                                                <div class="col-md-9">




                                                                    <select class="form-control" name="barcode_id">
                                                                        @foreach ($barcodes as $barcode)
                                                                            <option value="{{ $barcode->id }}">
                                                                                {{ $barcode->name }}</option>
                                                                        @endforeach

                                                                    </select>


                                                                </div>
                                                            </div>


                                                        </div>




                                                    </div>
                                                </div>

                                                {{-- end form --}}
                                                <div class="form-actions"
                                                    style="margin-top: 25px; display: flex; justify-content: flex-end;">
                                                    <button
                                                        class="btn btn-sm green btn-outline  btn-lg filter-submit margin-bottom"
                                                        style="width: 400px" id="showResult">
                                                        <i class="fa fa-building"></i>
                                                        {{ __('pos::dashboard.labels.datatable.preview') }}
                                                    </button>
                                                    <button class="btn btn-sm red btn-outline restPrint ">
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

                        </form>
                        {{-- end form --}}


                        {{-- <input type="text"  name="search" id="searchProduct" placeholder="Search By Product Name Or Sku"> --}}

                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase">
                                    {{ __('pos::dashboard.labels.routes.index') }}
                                </span>
                            </div>
                        </div>

                        {{-- DATATABLE CONTENT --}}
                        <div class="portlet-body">
                            <div id="printer"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"
        integrity="sha512-Z/2pIbAzFuLlc7WIt/xifag7As7GuTqoBbLsVTgut69QynAIOclmweT6o7pkxVoGGfLcmPJKn/lnxyMNKBAKgg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"
        integrity="sha512-t3XNbzH2GEXeT9juLjifw/5ejswnjWWMMDxsdCg4+MmvrM+MwqGhxlWeFJ53xN/SBHPDnW0gXYvBx/afZZfGMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        //  loaded
        $(function() {
            var products = []
            var _searchInput = $("#searchProduct"),
                resultBody = $("#result-body")
            resultContainer = $("#result-search")
            printer = $("#printer");


            // =========
            var options = {

                url: function(phrase) {

                    return "{{ route('dashboard.labels.search') }}";
                },

                getValue: function(element) {

                    return element.sku;
                },

                ajaxSettings: {
                    dataType: "json",
                    method: "get",
                    data: {
                        dataType: "json"
                    }
                },
                list: {

                    onChooseEvent: function() {
                        var data = _searchInput.getSelectedItemData()
                        products.push(data.id)
                        if (products.length > 0) resultContainer.show()
                        resultBody.append(handleChoseProduct(data))
                        // resultBody.html(handleChoseProduct(data))   
                    }

                },
                template: {
                    type: "custom",
                    method: function(value, item) {

                        return `
                        <div style="margin-botton:5px">
                            <img class="img-fluid" width="50" height="50" src="${item.image}"/>
                            <span>${item.title}</span>
                            - <span style="color:black;font-weight:bold">( ${item.sku} )</span>
                        </div>
                    `
                    }
                },
                preparePostData: function(data) {
                    data.search = _searchInput.val();
                    data.excludeIds = products
                    return data;
                },

                requestDelay: 200
            };

            _searchInput.easyAutocomplete(options);
            // ===========

            function handleChoseProduct(data) {
                var tbody = `
                <tr>
                        <td>
                             ${data.title}   
                        </td>
                        <td>
                            <input type="hidden" name="product[${products.length}][id]" value="${data.id}"  class="form-control" />
                            <input type="number" name="product[${products.length}][num]" value="1" min="1" class="form-control" />

                        </td>
                </tr>
            `
                var key = 0
                for (const variant of data.variations_values) {
                    tbody += `
                <tr>
                        <td>
                            ${data.title} <span style="color:red"> ( ${variant.title} ) </span> 
                        </td>
                        <td>
                            <input type="number" name="product[${products.length}][variants][${key}][num]" value="1" min="1" class="form-control" />
                            <input type="hidden" name="product[${products.length}][variants][${key}][id]" value="${variant.id}"  class="form-control" />
                        </td>
                </tr>
                `
                    key++;
                }
                // console.log(data, tbody)
                return tbody
            }

            $("#showResult").click(function(event) {
                event.preventDefault();
                if (products.length == 0) {

                    alert("You must choose at last one product")
                    return
                }
                var _form = $("#form-button")

                $.ajax({
                    url: "{{ route('dashboard.labels.renderLabel') }}",
                    type: "post",
                    data: _form.serializeArray(),
                    success: function(res) {
                        printer.html(res.html)
                    },
                    error: function(data) {
                        var getJSON = $.parseJSON(data.responseText);
                        toastr["error"](getJSON.errors['barcode_id'][0]);
                    },
                });
            })

            $("body").on("click", ".printButton", function(event) {
                event.preventDefault()
                printer.print()
            })

            $("body").on("click", ".restPrint", function(event) {
                event.preventDefault()
                printer.html("")
                products = [];
                resultContainer.hide()
                resultBody.html("")
                _searchInput.val("")
                _searchInput.change()


            })






        });
    </script>



@stop
