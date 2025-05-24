@push('styles')
<style>
    .inline-selector {
        display: inline !important;
        padding:0px 12px;
        
    }

    .input-xsmall {
        width: 88px !important;
        margin: 4px 9px;
    }

    .note {
        padding: 20px 50px;
        background-color: #f1f4f7;
    }

    .inline-selector-label {
        margin: 0px 3px;
    }

    .inline-selector-label-actions {
        margin: 0px 28px;
    }

    .inline-selector-label-actions i{
        margin: 0px 6px;
        cursor: pointer;
    }

    .inline-selector-label-actions .fa-plus{
        color: rgb(63, 198, 211);
    }

    .inline-selector-label-actions .fa-remove{
        color: rgb(244, 67, 54);
    }

    .inline-selector-label-actions .active{
        cursor: pointer;
    }

    .inline-selector-label-actions .deactive{
        color: rgb(213 213 213);
        cursor: not-allowed;
    }

</style>
@endpush

<div class="portlet light bordered" style="    border: 1px solid #e7ecf1!important">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <span class="caption-subject bold uppercase">{{$attr['placeholder']}}</span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row" style="margin: 0px;">
            <div class="col-lg-12">
                <div class="form-group">
                    <select class="form-control input-xsmall inline-selector" style="padding:0px 12px" name="json_data[show]">
                        <option value="1" {{$model && isset($model->json_data['show']) && $model->json_data['show'] == 1 ? 'selected' : ''}}>
                            {{ __('attribute::dashboard.attributes.form.show') }}
                        </option>
                        <option value="0" {{$model && isset($model->json_data['show']) && $model->json_data['show'] == 0 ? 'selected' : ''}}>
                            {{ __('attribute::dashboard.attributes.form.hide') }}
                        </option>
                    </select>
                    <label class="inline-selector-label">{{ __('attribute::dashboard.attributes.form.this_field_if') }}</label>

                    <select class="form-control input-xsmall inline-selector" style="padding:0px 12px" name="json_data[case]">
                        <option value="any"  {{$model && isset($model->json_data['case']) && $model->json_data['case'] == 'any' ? 'selected' : ''}}>
                            {{ __('attribute::dashboard.attributes.form.any') }}
                        </option>
                        <option value="all" {{$model && isset($model->json_data['case']) && $model->json_data['case'] == 'all' ? 'selected' : ''}}>
                            {{ __('attribute::dashboard.attributes.form.all') }}
                        </option>
                    </select>
                    <label class="inline-selector-label">{{ __('attribute::dashboard.attributes.form.of_these_rules_match') }}</label>
                </div>

            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <select class="form-control input-medium  inline-selector" v-model="attributesRules.slectedAttr">
                        <option 
                            v-for="attribute in attributesRules.allAttributes" 
                            :key="attribute.id" 
                            :value="attribute"
                        >
                            @{{attribute.name[locale]}} (@{{attribute.type}})
                        </option>
                    </select>

                    <select class="form-control input-xsmall inline-selector" v-model="attributesRules.slectedAction">
                        <option 
                            v-for="(option,index) in attributesRules.actions" 
                            :key="index" 
                            :value="index"
                        >
                            @{{option}}
                        </option>
                    </select>

                    <template v-if="attributesRules.slectedAttr != null">

                        <template v-if="allowOptions.includes(attributesRules.slectedAttr.type)">
                            <select class="form-control input-medium  inline-selector"  v-model="attributesRules.slectedoption">
                                <option 
                                    v-for="option in attributesRules.slectedAttr.options" 
                                    :key="option.id" 
                                    :value="option"
                                >
                                    @{{option.value[locale]}}
                                </option>
                            </select>
                        </template>
                        <template v-else>
                            <input type="text" class="form-control input-medium  inline-selector" v-model="attributesRules.slectedoption"/>
                        </template>

                    </template>
                    <template v-else>
                        <select class="form-control input-medium  inline-selector">
                            <option></option>
                        </select>
                    </template>

                    <label class="inline-selector-label-actions">
                    <i @click="addAttributesRules"  :class="`fa fa-plus ${
                        attributesRules.slectedAction && attributesRules.slectedAttr && attributesRules.slectedoption ? 'active' : 'deactive'}`"></i>
                    
                    <i @click="clearSelectedAttributesRules" :class="`fa fa-remove ${
                        attributesRules.slectedAction || attributesRules.slectedAttr || attributesRules.slectedoption ? 'active' : 'deactive'}`"></i>
                    </label>
                </div>

            </div>

            <div class="col-lg-12 note" v-if="newAttributesRules.length">
                <div v-for="rule in newAttributesRules" :key="rule.id">
                    <div class="form-group">
                        <select class="form-control input-medium  inline-selector" v-model="rule.slectedAttr">
                            <option 
                                v-for="attribute in rule.allAttributes" 
                                :key="attribute.id" 
                                :value="attribute"
                            >
                                @{{attribute.name[locale]}} (@{{attribute.type}})
                            </option>
                        </select>
                        <input type="hidden" name="{{$type}}[attributes][]" :value="rule.slectedAttr.id">

                        <select class="form-control input-xsmall inline-selector" v-model="rule.slectedAction">
                            <option 
                                v-for="(option,index) in rule.actions" 
                                :key="index" 
                                :value="index"
                            >
                                @{{option}}
                            </option>
                        </select>
                        <input type="hidden" name="{{$type}}[action][]" :value="rule.slectedAction">

                        <template v-if="rule.slectedAttr != null">

                            <template v-if="allowOptions.includes(rule.slectedAttr.type)">
                                <select class="form-control input-medium  inline-selector"  v-model="rule.slectedoption">
                                    <option 
                                        v-for="option in rule.slectedAttr.options" 
                                        :key="option.id" 
                                        :value="option"
                                    >
                                        @{{option.value[locale]}}
                                    </option>
                                </select>
                                <input v-if="rule.slectedoption && rule.slectedoption.hasOwnProperty('id')" 
                                    type="hidden" name="{{$type}}[option][]" :value="rule.slectedoption.id">
                            </template>
                            <template v-else>
                                <input type="text" class="form-control input-medium  inline-selector" v-model="rule.slectedoption"/>
                                <input type="hidden" name="{{$type}}[option][]" :value="rule.slectedoption">
                            </template>

                        </template>
                        <template v-else>
                            <select class="form-control input-medium  inline-selector">
                                <option></option>
                            </select>
                        </template>

                        <label class="inline-selector-label-actions">
                            <i @click="removeRule(rule.id)" :class="`fa fa-remove ${
                                rule.slectedAction || rule.slectedAttr || rule.slectedoption ? 'active' : 'deactive'}`"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>