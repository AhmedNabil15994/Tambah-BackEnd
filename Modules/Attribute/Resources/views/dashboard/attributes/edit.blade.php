@extends('apps::dashboard.layouts.app')
@section('title', __('attribute::dashboard.attributes.routes.update'))
@inject('attributeType', 'Modules\Attribute\Enums\AttributeType')
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
                        <a href="{{ url(route('dashboard.attributes.index')) }}">
                            {{ __('attribute::dashboard.attributes.routes.index') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('attribute::dashboard.attributes.routes.update') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.attributes.update', $model->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12" id="appVue">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a class="accordion-toggle"></a></h4>
                                    </div>
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#general" data-toggle="tab">
                                                        {{ __('attribute::dashboard.attributes.form.tabs.general') }}
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#validation" data-toggle="tab">
                                                        {{ __('attribute::dashboard.attributes.form.tabs.validation') }}
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#viewability" data-toggle="tab">
                                                        {{ __('attribute::dashboard.attributes.form.tabs.viewability') }}
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
                                    <h3 class="page-title">{{ __('attribute::dashboard.attributes.form.tabs.general') }}
                                    </h3>
                                    <div class="col-md-10">
                                        {{--  tab for lang --}}
                                        <ul class="nav nav-tabs">
                                            @foreach (config('translatable.locales') as $code)
                                                <li class="@if ($loop->first) active @endif"><a
                                                        data-toggle="tab"
                                                        href="#first_{{ $code }}">{{ $code }}</a>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{--  tab for content --}}
                                        <div class="tab-content">

                                            @foreach (config('translatable.locales') as $code)
                                                <div id="first_{{ $code }}"
                                                    class="tab-pane fade @if ($loop->first) in active @endif">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('attribute::dashboard.attributes.form.name') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text"
                                                                value="{{ $model->translate('name', $code) }}"
                                                                name="name[{{ $code }}]" class="form-control"
                                                                data-name="name.{{ $code }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>


                                                </div>
                                            @endforeach

                                        </div>

                                        <div class="form-group" v-if="!allowSetOptions">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.price') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" step="0.001" min="0" name="price"
                                                    class="form-control" data-name="price"
                                                    value="{{ $model->price ?? '' }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.sort') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="sort" class="form-control"
                                                    value="{{ $model->sort ?? '' }}" data-name="sort">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.icon') }}
                                            </label>
                                            <div class="col-md-9">
                                                @include('core::dashboard.shared.file_upload', [
                                                    'image' => $model->icon,
                                                    'name' => 'icon',
                                                ])
                                                <div class="help-block"></div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.type') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select class="form-control type" v-model="type" name="type">

                                                    @foreach ($attributeType::getConstList() as $type)
                                                        <option
                                                            data-allow-options="{{ $attributeType::checkAllowOptions($type) }}"
                                                            value="{{ $type }}">
                                                            {{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                                    @endforeach
                                                </select>

                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group hidden" ref="loadingHidden" v-if="allowSetOptions">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.options') }}
                                            </label>

                                            <div class="col-md-10">

                                                @foreach (config('translatable.locales') as $code)
                                                    <div class="row">
                                                        <label class="col-md-2">
                                                            {{ __('attribute::dashboard.attributes.form.name') }}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control"
                                                                v-model="option.{{ $code }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <button class="btn btn-block btn-info" :disabled="!isvalid"
                                                    @click.prevent="addOptions">
                                                    {{ __('apps::dashboard.buttons.add_new') }}
                                                </button>

                                                <div id="loading-wait">
                                                    {{-- list the option --}}
                                                    <table class="table">
                                                        <thead>
                                                            @foreach (config('translatable.locales') as $code)
                                                                <th>
                                                                    {{ __('attribute::dashboard.attributes.form.name') }}
                                                                    - {{ $code }}
                                                                </th>
                                                            @endforeach
                                                            <th>
                                                                {{ __('attribute::dashboard.attributes.form.price') }}
                                                            </th>
                                                            <th>
                                                                {{ __('attribute::dashboard.attributes.datatable.status') }}
                                                            </th>
                                                            <th>
                                                                #
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            {{-- old options --}}
                                                            <tr v-for="(option, index) in old_options"
                                                                :key="'edit_add' + index">
                                                                <template v-for="code of lang">

                                                                    <td>
                                                                        <template
                                                                            v-if="editIndexElment == index && editOption != null && typeOfEdit == 'edit' ">
                                                                            <input type="text"
                                                                                :class="{
                                                                                    invalid: code in editOption.value &&
                                                                                        editOption.value[code].length <=
                                                                                        0
                                                                                }"
                                                                                class="form-control"
                                                                                v-model="editOption.value[code]" />
                                                                            <input type="hidden" class="form-control"
                                                                                :value="option.value[code]"
                                                                                :name="`edit_option[${index}][value][${code}]`" />
                                                                        </template>
                                                                        <template v-else>
                                                                            <span>@{{ option.value.hasOwnProperty(code) ? option.value[code] : '' }}</span>
                                                                            <input type="hidden" class="form-control"
                                                                                :value="option.value[code]"
                                                                                :name="`edit_option[${index}][value][${code}]`" />
                                                                        </template>
                                                                    </td>
                                                                </template>
                                                                <td>
                                                                    <input type="number" step="0.001" min="0"
                                                                        class="form-control" v-model="option.price"
                                                                        style="width: 100px;"
                                                                        :name="`edit_option[${index}][price]`" />
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" v-model="option.status"
                                                                        :value="option.status"
                                                                        :name="`edit_option[${index}][status]`" />
                                                                    <input type="hidden" v-model="option.id"
                                                                        :name="`edit_option[${index}][id]`" />
                                                                    <input class="hidden" type="checkbox"
                                                                        v-model="option.status" :value="option.is_default"
                                                                        :name="`edit_option[${index}][is_default]`" />
                                                                </td>
                                                                <td>
                                                                    <template
                                                                        v-if="editIndexElment == index && editOption != null && typeOfEdit == 'edit'">
                                                                        <button class="btn btn-warning"
                                                                            @click.prevent="saveEditOption()"><i
                                                                                class="fa fa-check-square"
                                                                                aria-hidden="true"></i></button>
                                                                        <button class="btn btn-danger"
                                                                            @click.prevent="cancleEditOption()"><i
                                                                                class="fa fa-times"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </template>
                                                                    <template v-else>
                                                                        <button class="btn btn-warning"
                                                                            @click.prevent="editOptionData(index, 'edit')">
                                                                            <i class="fa fa-pencil"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger"
                                                                            @click.prevent="removeOldOption(index)"><i
                                                                                class="fa fa-trash"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </template>
                                                                </td>


                                                            </tr>
                                                            {{-- new options --}}
                                                            <tr v-for="(option, index) in options"
                                                                :key="'option_add' + index">
                                                                <template v-for="code of lang">

                                                                    <td>
                                                                        <template
                                                                            v-if="editIndexElment == index && editOption != null &&  typeOfEdit == 'new' ">

                                                                            <input type="text"
                                                                                :class="{
                                                                                    invalid: editOption[code].length <=
                                                                                        0
                                                                                }"
                                                                                class="form-control"
                                                                                v-model="editOption[code]" />
                                                                            <input type="hidden" class="form-control"
                                                                                :value="option[code]"
                                                                                :name="`option[${index}][value][${code}]`" />
                                                                        </template>

                                                                        <template v-else>
                                                                            <span>@{{ option[code] }}</span>
                                                                            <input type="hidden" class="form-control"
                                                                                :value="option[code]"
                                                                                :name="`option[${index}][value][${code}]`" />
                                                                        </template>
                                                                    </td>
                                                                </template>
                                                                <td>
                                                                    <input type="number" step="0.001" min="0"
                                                                        class="form-control" v-model="option.price"
                                                                        style="width: 100px;"
                                                                        :name="`option[${index}][price]`" />
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" v-model="option.status"
                                                                        :value="option.status"
                                                                        :name="`option[${index}][status]`" />
                                                                    <input class="hidden" type="checkbox"
                                                                        v-model="option.status" :value="option.is_default"
                                                                        :name="`option[${index}][is_default]`" />
                                                                </td>
                                                                <td>
                                                                    <template
                                                                        v-if="editIndexElment == index && editOption != null && typeOfEdit == 'new' ">
                                                                        <button class="btn btn-warning"
                                                                            @click.prevent="saveEditOption()"><i
                                                                                class="fa fa-check-square"
                                                                                aria-hidden="true"></i></button>
                                                                        <button class="btn btn-danger"
                                                                            @click.prevent="cancleEditOption()"><i
                                                                                class="fa fa-times"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </template>
                                                                    <template v-else>
                                                                        <button class="btn btn-warning"
                                                                            @click.prevent="editOptionData(index)"><i
                                                                                class="fa fa-pencil"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger"
                                                                            @click.prevent="removeOption(index)"><i
                                                                                class="fa fa-trash"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </template>
                                                                </td>


                                                            </tr>
                                                            {{-- default options --}}
                                                            <tr v-if="options.length > 0 || old_options.length > 0">
                                                                <td colspan="4">
                                                                    <div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4">
                                                                                {{ __('attribute::dashboard.attributes.form.option_default') }}
                                                                            </label>
                                                                            <div class="col-md-8">
                                                                                <select class="form-control type"
                                                                                    v-model="option_default"
                                                                                    v-on:change="setDefault()">

                                                                                    <option
                                                                                        v-for="(option, index)  in old_options"
                                                                                        :value="'edit_' + index"
                                                                                        :key="'edit_' + index">
                                                                                        @{{ option.value.hasOwnProperty(locale) ?
    option.value[locale] : '' }}
                                                                                    </option>

                                                                                    <option
                                                                                        v-for="(option, index)  in options"
                                                                                        :value="'new_' + index"
                                                                                        :key="'new_' + index">
                                                                                        @{{ option[locale] }}
                                                                                    </option>

                                                                                </select>

                                                                                <div class="help-block"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="hidden" v-if="deleteOptions.length > 0">
                                            <input type="hidden" name="deleteOptions[]"
                                                v-for="objectDelete in deleteOptions" :key="`delete_${objectDelete.id}`"
                                                v-model="objectDelete.id" />
                                        </div>


                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch"
                                                    @if ($model->status) checked @endif id="test"
                                                    data-size="small" name="status">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.show_in_search') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch"
                                                    @if ($model->show_in_search) checked @endif id="test2"
                                                    data-size="small" name="show_in_search">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        @if ($model->trashed())
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('catalog::dashboard.products.form.restore') }}
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

                                {{-- END CREATE FORM --}}

                                {{-- CREATE FORM --}}
                                <div class="tab-pane  fade in" id="validation">
                                    @php
                                        $validations = collect($model->validation);
                                    @endphp
                                    <h3 class="page-title">
                                        {{ __('attribute::dashboard.attributes.form.tabs.validation') }}</h3>
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('attribute::dashboard.attributes.form.validation.required') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch"
                                                    @if ($validations->get('required') == 1) checked @endif id="test"
                                                    data-size="small" name="validation[required]" value="1">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{-- handle show the validation number --}}
                                        <div v-show="allowValidationNumber == true">
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('attribute::dashboard.attributes.form.limit') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="">
                                                                <input type="text" :disabled="!allowValidationNumber"
                                                                    value="{{ $validations->get('min') }}"
                                                                    name="validation[min]"
                                                                    placeholder="  {{ __('attribute::dashboard.attributes.form.validation.min') }}"
                                                                    class="form-control " data-name="validation.min">
                                                                <div class="help-block"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="">
                                                                <input type="text" :disabled="!allowValidationNumber"
                                                                    value="{{ $validations->get('max') }}"
                                                                    name="validation[max]"
                                                                    placeholder="  {{ __('attribute::dashboard.attributes.form.validation.max') }}"
                                                                    class="form-control " data-name="validation.max">
                                                                <div class="help-block"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('attribute::dashboard.attributes.form.allow_limit') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <label class="col-md-7">
                                                                    {{ __('attribute::dashboard.attributes.form.validation.validate_min') }}
                                                                </label>
                                                                <div class="col-md-5">
                                                                    <input type="checkbox"
                                                                        @if ($validations->get('validate_min') == 1) checked @endif
                                                                        :disabled="!allowValidationNumber" class=""
                                                                        id="test" data-size="small"
                                                                        name="validation[validate_min]" value="1">
                                                                    <div class="help-block"></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <label class="col-md-7">
                                                                    {{ __('attribute::dashboard.attributes.form.validation.validate_max') }}
                                                                </label>
                                                                <div class="col-md-5">
                                                                    <input type="checkbox"
                                                                        @if ($validations->get('validate_max') == 1) checked @endif
                                                                        :disabled="!allowValidationNumber" class=""
                                                                        id="test" data-size="small"
                                                                        name="validation[validate_max]" value="1">
                                                                    <div class="help-block"></div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{ __('attribute::dashboard.attributes.form.validation.is_int') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class=""
                                                        @if ($validations->get('is_int') == 1) checked @endif:disabled="!allowValidationNumber"
                                                        id="test" data-size="small" name="validation[is_int]"
                                                        value="1">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                @php $catalogTypes = Modules\Attribute\Entities\Attribute::typesForSelect($model);@endphp
                                @include('attribute::dashboard.attributes.form-products')
                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{ __('apps::dashboard.general.edit_btn') }}
                                    </button>
                                    <a href="{{ url(route('dashboard.attributes.index')) }}" class="btn btn-lg red">
                                        {{ __('apps::dashboard.general.back_btn') }}
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
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>
        var lang = @json(config('translatable.locales'));
        var catalogTypes = @json($catalogTypes);
        var allowOptions = @json($attributeType::$allowOptions);
        var allowValidationNumber = @json($attributeType::$allowValidationNumber);
        var locale = "{{ locale() }}";
        var model = @json($model);

        let allAttributes = @json($allAttributes);

        let existsChildAttributes = @json($childAttributes);
        let attributesRulesActions = @json($attributeType::attributesRulesActions());

        var afterSucessAjex = function() {
            vm.$data.type = "text"

        }

        $(function() {
            $("#loading-wait").removeClass("hidden");
            vm.$data.loading = true;

        })
        var vm = new Vue({
            el: '#appVue',
            data: {
                "type": "text",
                "allowSetOptions": false,
                editIndexElment: -1,
                typeOfEdit: null,
                editOption: {},
                locale: "ar",
                option: {},
                options: [],
                allowOptions: allowOptions,
                option_default: null,
                old_options: [],
                loading: false,
                model: null,
                catalogTypes: catalogTypes,
                deleteOptions: [],

                attributesRules: {
                    id: 1,
                    allAttributes: null,
                    actions: null,
                    slectedAction: null,
                    slectedAttr: null,
                    slectedoption: null,
                },
                newAttributesRules: []
            },
            created: function() {
                this.lang = lang
                this.loading = true;
                this.locale = locale;
                this.model = model;
                this.type = model.type;
                this.old_options = model.options;
                var defaultOptionIndex = model.options.findIndex((option) => option.is_default == 1)
                this.option_default = "edit_" + defaultOptionIndex;

                this.attributesRules.allAttributes = allAttributes;
                this.attributesRules.actions = attributesRulesActions;
                this.setNewAttributesRules();
                //    .classList.remove("hidden");
            },

            methods: {
                setDefault: function() {
                    var select = this.option_default.split("_")
                    console.log(select)
                    if (this.option_default) {

                        var options = this.options.map((option, index) => {
                            var option = JSON.parse(JSON.stringify(option))
                            option.is_default = 0
                            return option
                        })
                        if (select[0] == "new") {
                            options[parseInt(select[1])].is_default = 1
                        }

                        var old_options = this.old_options.map((option, index) => {
                            var option = JSON.parse(JSON.stringify(option))
                            option.is_default = 0
                            return option
                        })

                        if (select[0] == "edit") {
                            old_options[parseInt(select[1])].is_default = 1
                        }


                        this.options = options;
                        this.old_options = old_options;

                    }

                },
                switchCheckBox: function(type) {

                    $('#' + type + '-input').toggle();

                },
                addOptions: function() {
                    var obj = JSON.parse(JSON.stringify(this.option))
                    // console.log(this.validationOption(obj), obj)
                    if (this.validationOption(obj)) {
                        this.options.push({
                            ...obj,
                            status: 1,
                            is_default: 0
                        })
                        this.clearOptions()
                    }

                },
                clearOptions: function() {
                    for (const code of this.lang) {
                        this.$set(this.option, code, "")
                    }
                },
                validationOption: function(option) {
                    if (Object.keys(option).length === 0) return false
                    for (const attr of lang) {
                        if (!option.hasOwnProperty(attr) || option[attr].length <= 0) return false
                    }
                    return true;
                },
                validationOptionEdit: function(option) {
                    if (Object.keys(option.value).length === 0) return false
                    for (const attr of lang) {
                        if (!option.value.hasOwnProperty(attr) || option.value[attr].length <= 0) return false
                    }
                    return true;
                },
                removeOption: function(index) {
                    if (confirm("Are You Sure")) this.options.splice(index, 1)
                },
                removeOldOption: function(index) {
                    if (confirm("Are You Sure")) {
                        var objectDelete = this.old_options.splice(index, 1)
                        objectDelete.length > 0 && this.deleteOptions.push(...objectDelete)
                    }
                },
                editOptionData: function(index, type = "new") {
                    this.editIndexElment = index;
                    this.editOption = type == "new" ? JSON.parse(JSON.stringify(this.options[index])) : JSON
                        .parse(JSON.stringify(this.old_options[index]))
                    // console.log(this.editOption)
                    this.typeOfEdit = type
                },
                saveEditOption: function() {
                    var obj = JSON.parse(JSON.stringify(this.editOption))
                    // console.log(obj)
                    if (this.validationOption(obj) || this.validationOptionEdit(obj)) {

                        this.typeOfEdit == "new" ? this.$set(this.options, this.editIndexElment, obj) : this
                            .$set(this.old_options, this.editIndexElment, obj)
                        this.editOption = null,
                            this.editIndexElment = -1
                        this.typeOfEdit = null
                    }


                },
                cancleEditOption: function() {
                    this.editOption = null,
                        this.editIndexElment = -1
                },
                clearSelectedAttributesRules: function() {

                    this.attributesRules.slectedAction = null;
                    this.attributesRules.slectedAttr = null;
                    this.attributesRules.slectedoption = null;
                },
                setNewAttributesRules: function() {
                    let oldData = [];
                    existsChildAttributes.map((attr) => {
                        let jsonData = JSON.parse(attr.pivot.json_data);
                        let option = jsonData.hasOwnProperty('option') ? jsonData.option : null;

                        oldData.push({
                            id: `${attr.id}_exists`,
                            allAttributes: this.attributesRules.allAttributes,
                            actions: this.attributesRules.actions,
                            slectedAction: jsonData.hasOwnProperty('action') ? jsonData.action :
                                null,
                            slectedAttr: this.attributesRules.allAttributes.find((x) => x.id ==
                                attr.id),
                            slectedoption: this.allowOptions.includes(attr.type) ?
                                attr.options.find((opt) => opt.id == option) : option,
                        });
                    });
                    this.newAttributesRules = oldData;
                },
                addAttributesRules: function() {

                    if (this.attributesRules.slectedAction && this.attributesRules.slectedAttr && this
                        .attributesRules.slectedoption) {
                        this.newAttributesRules.push({
                            id: this.attributesRules.id,
                            allAttributes: this.attributesRules.allAttributes,
                            actions: this.attributesRules.actions,
                            slectedAction: this.attributesRules.slectedAction,
                            slectedAttr: this.attributesRules.slectedAttr,
                            slectedoption: this.attributesRules.slectedoption,
                        });

                        this.attributesRules.id++;
                        this.clearSelectedAttributesRules();
                    }
                },

                removeRule: function(id) {
                    let removingElement = this.newAttributesRules.map((rule) => rule.id).indexOf(id);
                    if (removingElement || removingElement == 0) {
                        this.newAttributesRules.splice(removingElement, 1);
                        return true;
                    }

                    return false;
                },

                getSlectedAttrWithId: function(id) {
                    return this.attributesRules.allAttributes.find((attr) => attr.id == id);
                },
            },
            computed: {
                isvalid: function() {
                    return this.validationOption(this.option)
                },
                "allowValidationNumber": function() {
                    return allowValidationNumber.includes(this.type)
                }
            },
            watch: {
                "type": function(val, old) {
                    if (allowOptions.includes(val)) {
                        this.allowSetOptions = true;
                        this.$nextTick(function() {
                            this.$refs.loadingHidden.classList.remove("hidden");
                        })
                        this.deleteOptions = [];
                    } else {
                        this.allowSetOptions = false
                        this.options = [];
                        this.deleteOptions = JSON.parse(JSON.stringify(this.old_options))
                        this.option = {};
                    }
                }
            }
        })
    </script>
@stop
