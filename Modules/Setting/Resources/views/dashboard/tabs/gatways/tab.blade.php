
            <div class="row">
                <div class="col-md-6 col-md-offset-4">

                    <div class="form-group">

                        <div class="col-md-9">
                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline">
                                    {{ __('setting::dashboard.settings.form.payment_gateway.payment_mode.test_mode') }}
                                    <input onchange="paymentModeSwitcher('tap_switch','testModelData_tap')" type="radio" name="payment_gateway[tap][payment_mode]" value="test_mode"
                                           @if (config('setting.payment_gateway.tap.payment_mode') != 'live_mode')
                                           checked
                                            @endif>
                                    <span></span>
                                </label>
                                <label class="mt-radio mt-radio-outline">
                                    {{ __('setting::dashboard.settings.form.payment_gateway.payment_mode.live_mode') }}
                                    <input  onchange="paymentModeSwitcher('tap_switch','liveModelData_tap')" type="radio" name="payment_gateway[tap][payment_mode]" value="live_mode"
                                           @if (config('setting.payment_gateway.tap.payment_mode') == 'live_mode')
                                           checked
                                            @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-7 col-md-offset-2 tap_switch" id="testModelData_tap"
                     style="{{ config('setting.payment_gateway.tap.payment_mode') == 'live_mode' ? 'display: none': 'display: block' }}">

                    <h3 class="page-title text-center">Tap Gateway ( Test Mode )</h3>

                    {!! field()->text('payment_gateway[tap][test_mode][API_KEY]', 'API Key', config('setting.payment_gateway.tap.test_mode.API_KEY') ?? '') !!}
                </div>

                <div class="col-md-7 col-md-offset-2 tap_switch" id="liveModelData_tap"
                     style="{{ config('setting.payment_gateway.tap.payment_mode') == 'live_mode' ? 'display: block': 'display: none' }}">

                    <h3 class="page-title text-center">Tap Gateway ( Live Mode )</h3>

                    {!! field()->text('payment_gateway[tap][live_mode][API_KEY]', 'API Key',  config('setting.payment_gateway.tap.live_mode.API_KEY') ?? '') !!}

                </div>
                <div class="col-md-7 col-md-offset-2">
{{--                    @foreach (config('translatable.locales') as $code)--}}

{{--                        {!! field()->text('payment_gateway[tap][title_'.$code.']', __('setting::dashboard.settings.form.payment_gateway.payment_types.payment_title').'-'.$code ,--}}
{{--                        config('setting.payment_gateway.tap.title_'.$code)) !!}--}}

{{--                    @endforeach--}}

                        @foreach (config('translatable.locales') as $code)
                            <div class="form-group">
                                <label class="col-md-2">
                                    {{ __('setting::dashboard.settings.form.payment_gateway.title') .' - '.$code  }}
                                </label>
                                <div class="col-md-9">
                                    {!! field('payment_search_inputs')->text('payment_method[tap]['.$code.']', null,
                                        Setting::get('payment_icons.tap.' . $code)) !!}
                                </div>
                            </div>

                        @endforeach
                        <div class="form-group">
                            <label class="col-md-2">
                                {{__('setting::dashboard.settings.form.payment_gateway.icon')}}
                            </label>
                            <div class="col-md-9">
                                {!! field()->file(
                                    'payment_method[tapIcon]',null,
                                    Setting::get('payment_icons.tapIcon') ? asset(Setting::get('payment_icons.tapIcon')) : null
                                ) !!}
                            </div>
                        </div>

                    {!! field()->checkBox('payment_gateway[tap][status]', __('setting::dashboard.settings.form.payment_gateway.payment_types.payment_status') , null , [
                    (config('setting.payment_gateway.tap.status') == 'on' ? 'checked' : '') => ''
                    ]) !!}
                </div>
            </div>
