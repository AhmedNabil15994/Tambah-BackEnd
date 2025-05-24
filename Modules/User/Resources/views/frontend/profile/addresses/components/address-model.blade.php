@php
$model = !empty($model) ? $model : null;
@endphp

<div class="modal fade" id="addressModel{{ optional($model)['id'] }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('user::frontend.addresses.edit.title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="mt-20" method="post" action="{{ $route }}">
                    @csrf
                    <input type="hidden" name="view" value="{{ !empty($view_type) ? $view_type : '' }}">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            {!! field('frontend_no_label')->text(
                                'username',
                                __('user::frontend.addresses.form.username'),
                                optional($model)->username,
                            ) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! field('frontend_no_label')->number(
                                'mobile',
                                __('user::frontend.addresses.form.mobile'),
                                optional($model)->mobile,
                            ) !!}
                        </div>

                        @include('user::frontend.profile.addresses.components.country-selector.selector', [
                            'selected_country' =>
                                !empty($model) && $model
                                    ? optional(optional(optional($model)->state)->city)->country_id
                                    : null,
                            'selected_state' => !empty($model) && $model ? optional($model)->state_id : null,
                        ])
                        <div class="col-md-6 col-12">
                            {!! field('frontend_no_label')->text(
                                'street',
                                __('user::frontend.addresses.form.street'),
                                optional($model)->street,
                            ) !!}
                        </div>
                        <div class="col-md-6 col-12 block_container"
                            style="display: {{ empty($model) || (!empty($model) && optional(optional(optional(optional($model)->state)->city)->country)->iso2 == 'KW') ? 'block' : 'none' }}">
                            {!! field('frontend_no_label')->text(
                                'block',
                                __('user::frontend.addresses.form.block'),
                                optional($model)->block,
                            ) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! field('frontend_no_label')->text(
                                'building',
                                __('user::frontend.addresses.form.building'),
                                optional($model)->building,
                            ) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! field('frontend_no_label')->text(
                                'address',
                                __('user::frontend.addresses.form.address_details'),
                                optional($model)->address,
                            ) !!}
                        </div>

                        <div class="col-md-12 col-12">

                            <div class="form-group">
                                <textarea class="form-control" name="land_mark" placeholder="{{ __('apps::frontend.Add Land Mark') }}">{{ optional($model)->land_mark }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-20 mt-20 text-left">
                        <button class="btn btn-them main-custom-btn" type="button"
                            onclick="submitForm(this,'{{ $model ? 'update' : 'create' }}')">
                            <span class="btn-title">{{ __('apps::frontend.Save changes') }}</span>

                            <span class="spinner-border spinner-border-md btn_spinner" role="status" aria-hidden="true"
                                style="display: none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
