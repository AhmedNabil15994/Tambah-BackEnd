<div class="row">
    <div class="col-md-7">

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.supported_countries') }}
            </label>
            <div class="col-md-9">
                <select name="payment_gateway[cash][supported_countries]" class="form-control select2" multiple=""
                    data-placeholder="{{ __('setting::dashboard.settings.form.all_countries') }}">
                    @foreach ($countries as $code => $country)
                        <option value="{{ $code }}" @if (collect(config('setting.payment_gateway.cash.supported_countries', []))->contains($code)) selected="" @endif>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @foreach (config('translatable.locales') as $code)
            <div class="form-group">
                <label class="col-md-2">
                    {{ __('setting::dashboard.settings.form.payment_gateway.title') .' - '.$code  }}
                </label>
                <div class="col-md-9">
                    {!! field('payment_search_inputs')->text('payment_method[cash]['.$code.']', null,
                        Setting::get('payment_icons.cash.' . $code)) !!}
                </div>
            </div>

        @endforeach
        <div class="form-group">
            <label class="col-md-2">
                {{__('setting::dashboard.settings.form.payment_gateway.icon')}}
            </label>
            <div class="col-md-9">
            {!! field()->file(
                'payment_method[cashIcon]',null,
                Setting::get('payment_icons.cashIcon') ? asset(Setting::get('payment_icons.cashIcon')) : null
            ) !!}
            </div>
        </div>
        {!! field()->checkBox(
            'payment_gateway[cash][status]',
            __('setting::dashboard.settings.form.payment_gateway.payment_types.payment_status'),
            null,
            [
                config('setting.payment_gateway.cash.status') == 'on' ? 'checked' : '' => '',
            ]
        ) !!}
    </div>
</div>
