@push('styles')
    <style>
        .inline-selector {
            display: inline !important;
        }

        .input-xsmall {
            width: 88px !important;
        }

        .inline-selector-label {
            margin: 0px 3px;
        }
    </style>
@endpush

<div class="tab-pane  fade in" id="viewability">
    <h3 class="page-title">{{ __('attribute::dashboard.attributes.form.tabs.viewability') }}</h3>

    <div class="form-group">
        <label class="col-md-2">
            {{ __('apps::dashboard.app_homes.form.type') }}
        </label>

        <div class="col-md-9">
            <div class="md-radio-inline">

                <label class="mt-checkbox" style="margin-right: 5px;margin-left: 5px;" v-for="attr in catalogTypes"
                    :key="attr.type">
                    <input type="checkbox" name="catalog_type[]" data-name="catalog_type" id="catalog_type"
                        :value="attr.type" @click="switchCheckBox(attr.type)" v-model="attr.checked"
                        v-html="attr.name">
                    @{{ attr.name }}
                    <span></span>
                </label>
                <div class="help-block" style="color: red"></div>
            </div>
        </div>
    </div>



    @foreach ($catalogTypes as $attr)
        @php
            $type = $attr['type'];
            $input_type = $attr['input_type'];
        @endphp

        @switch($input_type)
            @case('multiSelect')
                <div class=" hide-inputs" id="{{ $type }}-input"
                    style="display: {{ $attr['checked'] ? 'block' : 'none' }}">
                    {!! field()->multiSelect(
                        $type,
                        $attr['placeholder'],
                        Modules\Attribute\Entities\Attribute::getClassByType($type)->pluck('title', 'id')->toArray(),
                        $attr['checked'] ? $model->$type->pluck('id')->toArray() : [],
                    ) !!}
                </div>
            @break

            @case('childAttributes')
                <div class=" hide-inputs" id="{{ $type }}-input"
                    style="display: {{ $attr['checked'] ? 'block' : 'none' }}">
                    @include('attribute::dashboard.attributes.components.child-attributes-rules')
                </div>
            @break
        @endswitch
    @endforeach
</div>
