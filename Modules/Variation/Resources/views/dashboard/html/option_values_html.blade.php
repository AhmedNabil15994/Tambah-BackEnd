
<div class="option_values_form_copier" style="display:{{!empty($model) ? 'block' : 'none'}}">
    <div class="content">

        @isset($model)
            <input type="hidden" name="option_values_ids[]"
            value="{{ $model->id }}">
        @endisset
        <div class="hide-inputs-{{$id}}" id="text-input-{{$id}}">
            @foreach (config('translatable.locales') as $code)
                <div class="form-group">
                    <label class="col-md-2">
                        {{__('variation::dashboard.options.form.title')}} - {{ $code }}
                    </label>

                    <div class="col-md-9">
                        <input type="text" name="option_value_title[{{$code}}][{{$id}}]" class="form-control"
                               data-name="option_value_title.{{$code}}.{{$id}}"
                               value="{{!empty($model) ? $model->getTranslation('title',$code) : ''}}">
                        <div class="help-block"></div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class=" hide-inputs color-input" style="display: {{!empty($model) && !empty($option_type) && $option_type == 'color' ? 'block' : 'none'}}">
            <div class="form-group">
                <label class="col-md-2">
                    {{__('variation::dashboard.options.form.color')}}
                </label>

                <div class="col-md-4">
                    <input type="color" name="option_value_color[{{$id}}]" class="form-control" value="{{!empty($model) ? $model->color : ''}}"
                           data-name="option_value_color">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <a href="javascript:;" class="remove_html btn red">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <hr>
    </div>
</div>