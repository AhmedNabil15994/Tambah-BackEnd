
            <div class="row">
                <div class="col-md-6 col-md-offset-4">

                    <div class="form-group">

                        <div class="col-md-9">
                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline">
                                    {{ __('setting::dashboard.settings.form.payment_gateway.payment_mode.test_mode') }}
                                    <input onchange="paymentModeSwitcher('upay_switch','testModelData_upay')" type="radio" name="payment_gateway[upayment][payment_mode]" value="test_mode"
                                           @if (config('setting.payment_gateway.upayment.payment_mode') != 'live_mode')
                                           checked
                                            @endif>
                                    <span></span>
                                </label>
                                <label onchange="paymentModeSwitcher('upay_switch','liveModelData_upay')" class="mt-radio mt-radio-outline">
                                    {{ __('setting::dashboard.settings.form.payment_gateway.payment_mode.live_mode') }}
                                    <input type="radio" name="payment_gateway[upayment][payment_mode]" value="live_mode"
                                           @if (config('setting.payment_gateway.upayment.payment_mode') == 'live_mode')
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
                <div class="col-md-7 col-md-offset-2 upay_switch" id="testModelData_upay"
                     style="{{ config('setting.payment_gateway.upayment.payment_mode') == 'live_mode' ? 'display: none': 'display: block' }}">

                    <h3 class="page-title text-center">UPayment Gateway ( Test Mode )</h3>
                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.merchant_id') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][test_mode][MERCHANT_ID]"
                               value="{{ config('setting.payment_gateway.upayment.test_mode.MERCHANT_ID') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.api_key') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][test_mode][API_KEY]"
                               value="{{ config('setting.payment_gateway.upayment.test_mode.API_KEY') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.username') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][test_mode][USERNAME]"
                               value="{{ config('setting.payment_gateway.upayment.test_mode.USERNAME') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.password') }}
                        </label>
                        <input type="text" class="form-control"
                               name="payment_gateway[upayment][test_mode][PASSWORD]"
                               value="{{ config('setting.payment_gateway.upayment.test_mode.PASSWORD') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            charges
                        </label>
                        <input type="text" class="form-control"
                               name="payment_gateway[upayment][test_mode][charges]"
                               value="{{ config('setting.payment_gateway.upayment.test_mode.charges') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            cc_charges
                        </label>
                        <input type="text" class="form-control"
                               name="payment_gateway[upayment][test_mode][cc_charges]"
                               value="{{ config('setting.payment_gateway.upayment.test_mode.cc_charges') ?? '' }}"/>
                    </div>
                </div>

                <div class="col-md-7 col-md-offset-2 upay_switch" id="liveModelData_upay"
                     style="{{ config('setting.payment_gateway.upayment.payment_mode') == 'live_mode' ? 'display: block': 'display: none' }}">

                    <h3 class="page-title text-center">UPayment Gateway ( Live Mode )</h3>
                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.merchant_id') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][live_mode][MERCHANT_ID]"
                               value="{{ config('setting.payment_gateway.upayment.live_mode.MERCHANT_ID') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.api_key') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][live_mode][API_KEY]"
                               value="{{ config('setting.payment_gateway.upayment.live_mode.API_KEY') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.username') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][live_mode][USERNAME]"
                               value="{{ config('setting.payment_gateway.upayment.live_mode.USERNAME') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.password') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][live_mode][PASSWORD]"
                               value="{{ config('setting.payment_gateway.upayment.live_mode.PASSWORD') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            {{ __('setting::dashboard.settings.form.payment_gateway.upayment.iban') }}
                        </label>
                        <input type="text" class="form-control" name="payment_gateway[upayment][live_mode][IBAN]"
                               value="{{ config('setting.payment_gateway.upayment.live_mode.IBAN') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            charges
                        </label>
                        <input type="text" class="form-control"
                               name="payment_gateway[upayment][live_mode][charges]"
                               value="{{ config('setting.payment_gateway.upayment.live_mode.charges') ?? '' }}"/>
                    </div>

                    <div class="form-group">
                        <label>
                            cc_charges
                        </label>
                        <input type="text" class="form-control"
                               name="payment_gateway[upayment][live_mode][cc_charges]"
                               value="{{ config('setting.payment_gateway.upayment.live_mode.cc_charges') ?? '' }}"/>
                    </div>

                </div>
                <div class="col-md-7 col-md-offset-2">

                    @foreach (config('translatable.locales') as $code)
                        {!! field('payment_search_inputs')->text('payment_method[upayment]['.$code.']', __('setting::dashboard.settings.form.payment_gateway.title').'-'.$code ,
                        Setting::get('payment_icons.upayment.' . $code)) !!}

                    @endforeach
                        <label>
                            {{__('setting::dashboard.settings.form.payment_gateway.icon')}}
                        </label>
                        {!! field()->file(
                        'payment_method[upaymentIcon]',null,
                        Setting::get('payment_icons.upaymentIcon') ? asset(Setting::get('payment_icons.upaymentIcon')) : null
                    ) !!}
                    {!! field()->checkBox('payment_gateway[upayment][status]', __('setting::dashboard.settings.form.payment_gateway.payment_types.payment_status') , null , [
                    (config('setting.payment_gateway.upayment.status') == 'on' ? 'checked' : '') => ''
                    ]) !!}
                </div>
            </div>
