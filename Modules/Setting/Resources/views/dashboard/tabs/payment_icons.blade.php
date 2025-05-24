<div class="tab-pane fade" id="payment_icons">
    {{--    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.app') }}</h3> --}}
    <div class="col-md-10">

        {{--  tab for lang --}}
        <ul class="nav nav-tabs">
            @foreach (config('translatable.locales') as $code)
                <li class="@if ($loop->first) active @endif">
                    <a data-toggle="tab"
                        href="#payment_first_{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
                </li>
            @endforeach
        </ul>

        {{--  tab for content --}}
        <div class="tab-content">

            @foreach (config('translatable.locales') as $code)
                <div id="payment_first_{{ $code }}"
                    class="tab-pane fade @if ($loop->first) in active @endif">

                    <div class="form-group">
                        <label class="col-md-2">
                            {{ __('setting::dashboard.settings.form.payment_icon.cash') }} - {{ $code }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="payment_method[cash][{{ $code }}]"
                                value="{{ Setting::get('payment_icons.cash.' . $code) }}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2">
                            {{ __('setting::dashboard.settings.form.payment_icon.upayment') }} - {{ $code }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="payment_method[upayment][{{ $code }}]"
                                value="{{ Setting::get('payment_icons.upayment.' . $code) }}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2">
                            {{ __('setting::dashboard.settings.form.payment_icon.tap') }} - {{ $code }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="payment_method[tap][{{ $code }}]"
                                value="{{ Setting::get('payment_icons.tap.' . $code) }}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2">
                            {{ __('setting::dashboard.settings.form.payment_icon.my_fatoorah') }} - {{ $code }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="payment_method[my_fatoorah][{{ $code }}]"
                                   value="{{ Setting::get('payment_icons.my_fatoorah.' . $code) }}" />
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        <div class="form-group">
            {!! field()->file(
            'payment_method[cashIcon]',
            __('setting::dashboard.settings.form.payment_icon.cashIcon'),
            Setting::get('payment_icons.cashIcon') ? asset(Setting::get('payment_icons.cashIcon')) : null
        ) !!}
        </div>
        <div class="form-group">
            {!! field()->file(
            'payment_method[upaymentIcon]',
            __('setting::dashboard.settings.form.payment_icon.upaymentIcon'),
            Setting::get('payment_icons.upaymentIcon') ? asset(Setting::get('payment_icons.upaymentIcon')) : null
        ) !!}
        </div>

        <div class="form-group">
            {!! field()->file(
            'payment_method[tapIcon]',
            __('setting::dashboard.settings.form.payment_icon.tapIcon'),
            Setting::get('payment_icons.tapIcon') ? asset(Setting::get('payment_icons.tapIcon')) : null
        ) !!}
        </div>

        <div class="form-group">
            {!! field()->file(
            'payment_method[my_fatoorahIcon]',
            __('setting::dashboard.settings.form.payment_icon.my_fatoorahIcon'),
            Setting::get('payment_icons.my_fatoorahIcon') ? asset(Setting::get('payment_icons.my_fatoorahIcon')) : null
        ) !!}
        </div>

    </div>
</div>
