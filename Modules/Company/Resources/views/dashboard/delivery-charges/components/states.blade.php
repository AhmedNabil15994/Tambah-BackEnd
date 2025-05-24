<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th style="padding: 15px 5px 15px 0;">
                {{__('company::dashboard.delivery_charges.update.state')}}
            </th>
            <th>
                <span>{{__('company::dashboard.delivery_charges.update.delivery_price')}}</span>
                <button type="button" class="btn btn-success btn-sm pull-right"
                        onclick="copyToAllInputValues('delivery_price','delivery-price-input-{{$city->id}}')"
                        title="{{__('company::dashboard.delivery_charges.update.btn.copy')}}">
                    <i class="fa fa-copy"></i>
                </button>
            </th>
            <th>
                <span>{{__('company::dashboard.delivery_charges.update.delivery_time')}}</span>
                <span class="help-tip">
                    <i class="fa fa-question-circle"></i>
                    <p>{{__('company::dashboard.delivery_charges.update.delivery_time_note')}}</p>
                </span>
                <button type="button" class="btn btn-success btn-sm pull-right"
                        onclick="copyToAllInputValues('delivery_time','delivery-time-input-{{$city->id}}')"
                        title="{{__('company::dashboard.delivery_charges.update.btn.copy')}}">
                    <i class="fa fa-copy"></i>
                </button>
            </th>
            <th>
                <span>{{__('company::dashboard.delivery_charges.update.min_order_amount')}}</span>
                <button type="button" class="btn btn-success btn-sm pull-right"
                        onclick="copyToAllInputValues('min_order_amount','min-order-amount-input-{{$city->id}}')"
                        title="{{__('company::dashboard.delivery_charges.update.btn.copy')}}">
                    <i class="fa fa-copy"></i>
                </button>
            </th>
            <th>
                <span>{{__('company::dashboard.delivery_charges.update.status')}}</span>
                <div class="pull-right"
                     title="{{__('company::dashboard.delivery_charges.update.btn.activate_all')}}">
                    <input type="checkbox" class="make-switch makeAllActiveCheckbox" onchange="makeAllActiveCheckbox(this, 'delivery-status-input-{{$city->id}}')"
                           data-size="small" name="active_all_statuses">
                </div>
            </th>
            </thead>
            <tbody>
            @foreach ($city->states as $state)
                <tr>
                    <td>{{ $state->title }}</td>
                    <td>
                        <input type="text" name="delivery[{{$state->id}}]"
                               class="form-control delivery-price-input delivery-price-input-{{$city->id}}"
                               value="{{!array_key_exists($state->id, $charges) ? "" : $charges[$state->id]}}"
                               placeholder="{{__('company::dashboard.delivery_charges.update.charge')}}">
                        <input type="hidden" name="state[{{$state->id}}]" value="{{ $state->id }}">
                    </td>
                    <td>
                        <input type="text" name="delivery_time[{{$state->id}}]"
                               class="form-control delivery-time-input delivery-time-input-{{$city->id}}"
                               value="{{!array_key_exists($state->id, $times) ? "" : $times[$state->id]}}"
                               placeholder="{{__('company::dashboard.delivery_charges.update.time')}}">
                    </td>
                    <td>
                        <input type="text" name="min_order_amount[{{$state->id}}]"
                               class="form-control min-order-amount-input min-order-amount-input-{{$city->id}}"
                               value="{{!array_key_exists($state->id, $min_order_amount) ? "" : $min_order_amount[$state->id]}}"
                        >
                    </td>
                    <td>
                        <input type="checkbox" class="make-switch delivery-status-input delivery-status-input-{{$city->id}}"
                               data-size="small" name="status[{{ $state->id }}]"
                                {{ array_key_exists($state->id, $statuses) && $statuses[$state->id] == 1 ? "checked" : "" }}>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>