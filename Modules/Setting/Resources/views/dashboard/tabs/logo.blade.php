<div class="tab-pane fade" id="logo">
    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.logo') }}</h3>
    <div class="col-md-10">
        {!! field()->file('images[logo]' , __('setting::dashboard.settings.form.logo') , config('setting.logo') ? url(config('setting.logo')) : null) !!}
        {!! field()->file('images[mail_logo]' , __('setting::dashboard.settings.form.mail_logo') , Setting::get('mail_logo') ? asset(Setting::get('mail_logo')) : null) !!}
        {!! field()->file('images[white_logo]' , __('setting::dashboard.settings.form.white_logo') , config('setting.footer_logo') ? url(config('setting.footer_logo')) : null) !!}
        {!! field()->file('images[favicon]' , __('setting::dashboard.settings.form.favicon') , config('setting.favicon') ? url(config('setting.favicon')) : null) !!}
    </div>
</div>

{{--<div class="tab-pane fade" id="logo">--}}

{{--    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.logo') }}</h3>--}}

{{--    <div class="col-md-10">--}}

{{--        <div class="form-group">--}}
{{--            <label class="col-md-2">--}}
{{--                {{ __('setting::dashboard.settings.form.logo') }}--}}
{{--            </label>--}}
{{--            <div class="col-md-9">--}}
{{--                <div class="input-group">--}}
{{--                    <span class="input-group-btn">--}}
{{--                        <a data-input="logo" data-preview="holder" class="btn btn-primary lfm">--}}
{{--                            <i class="fa fa-picture-o"></i>--}}
{{--                            {{__('apps::dashboard.general.upload_btn')}}--}}
{{--                        </a>--}}
{{--                    </span>--}}
{{--                    <input name="images[logo]" class="form-control logo" type="text" readonly--}}
{{--                           value="{{ config('setting.logo') ? url(config('setting.logo')) : ''}}">--}}
{{--                </div>--}}
{{--                <span class="holder" style="margin-top:15px;max-height:100px;">--}}
{{--                    <img src="{{ config('setting.logo') ? url(config('setting.logo')) : ''}}" style="height: 15rem;">--}}
{{--                </span>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="form-group">--}}
{{--            <label class="col-md-2">--}}
{{--                {{ __('setting::dashboard.settings.form.white_logo') }}--}}
{{--            </label>--}}
{{--            <div class="col-md-9">--}}
{{--                <div class="input-group">--}}
{{--                    <span class="input-group-btn">--}}
{{--                        <a data-input="white_logo" data-preview="holder" class="btn btn-primary lfm">--}}
{{--                            <i class="fa fa-picture-o"></i>--}}
{{--                            {{__('apps::dashboard.general.upload_btn')}}--}}
{{--                        </a>--}}
{{--                    </span>--}}
{{--                    <input name="images[white_logo]" class="form-control white_logo" type="text" readonly--}}
{{--                           value="{{ config('setting.white_logo') ? url(config('setting.white_logo')) : ''}}">--}}
{{--                </div>--}}
{{--                <span class="holder" style="margin-top:15px;max-height:100px;">--}}
{{--                    <img src="{{ config('setting.white_logo') ? url(config('setting.white_logo')) : ''}}"--}}
{{--                         style="height: 15rem;">--}}
{{--                </span>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="form-group">--}}
{{--            <label class="col-md-2">--}}
{{--                {{ __('setting::dashboard.settings.form.favicon') }}--}}
{{--            </label>--}}
{{--            <div class="col-md-9">--}}
{{--                <div class="input-group">--}}
{{--                    <span class="input-group-btn">--}}
{{--                        <a data-input="favicon" data-preview="holder" class="btn btn-primary lfm">--}}
{{--                            <i class="fa fa-picture-o"></i>--}}
{{--                            {{__('apps::dashboard.general.upload_btn')}}--}}
{{--                        </a>--}}
{{--                    </span>--}}
{{--                    <input name="images[favicon]" class="form-control favicon" type="text" readonly--}}
{{--                           value="{{ config('setting.favicon') ? url(config('setting.favicon')) : ''}}">--}}
{{--                </div>--}}
{{--                <span class="holder" style="margin-top:15px;max-height:100px;">--}}
{{--                    <img src="{{ config('setting.favicon') ? url(config('setting.favicon')) : ''}}"--}}
{{--                         style="height: 15rem;">--}}
{{--                </span>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--</div>--}}
