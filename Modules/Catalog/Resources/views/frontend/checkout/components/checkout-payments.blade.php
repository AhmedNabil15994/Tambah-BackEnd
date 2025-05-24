<div class="order-payment">

    @foreach($paymentMethods as $k => $payment)
        @if(in_array($payment->code, config('setting.other.supported_payments') ?? []))

            @if($payment->code == 'online' && count(config('setting.payment_gateway.upayment')))
                @foreach(config('setting.payment_gateway') as $k => $gateway)
                    @if($gateway['status'] == 'on')
                        <div class="checkboxes radios mb-20">
                            <input type="radio"
                                   value="{{$k}}"
                                   id="gateway-{{$k}}"
                                   name="payment" {{ (old('payment') ?? ($loop->index == 0 ? $k : null) ) == $k  ? 'checked' : '' }}
                            >
                            <label for="gateway-{{$k}}">{{ isset($gateway['title_'.locale()]) ? $gateway['title_'.locale()] : $payment->title}} </label>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="checkboxes radios mb-20">
                    <input type="radio"
                           value="{{$payment->code}}"
                           id="payment-{{ $payment->id }}"
                           name="payment" {{ (old('payment') ?? 'online') == $payment->code  ? 'checked' : '' }}
                    >
                    <label for="payment-{{ $payment->id }}">{{ $payment->title}} </label>
                </div>
            @endif

        @endif
    @endforeach
</div>