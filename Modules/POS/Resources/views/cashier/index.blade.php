@extends('pos::cashier.layouts.app')
@section('title', __('pos::cashier.home.routes.index', 
    ["name"=> optional(optional(auth()->user()->branch)->translate(locale(), true))->title]
    )    )
@section('content')
{{-- content --}}
{{-- page --}}
<div class="inner-page">
    <div class="cust-pad">
         <products :auth="auth"
            :users="{{$users}}" :defult-client="{{config('setting.other.default_user_id', Modules\User\Entities\User::doesnthave('roles.perms')->select("id", "name","image","mobile","email")->first()->id )}}"
            {{-- :total-dispaly="totalPaied" --}}
            :routes="{{json_encode([
                'listOrders'=> route('cashier.orders.list') ,
                'orderRefund'=> route('cashier.orders.refund') ,
                'invoice'    => route('cashier.orders.invoice',':id'),
            ])}}"
            v-on:total="handleUpdatTotal"
            :settings="{{json_encode(Setting::get('pos'))}}"
          />
    </div>
</div>

{{-- end page --}}

<loading-screen :loading="false" />

{{-- product --}}

{{-- endproduct --}}




{{-- model customer --}}
<div class="modal fade" id="new-customer" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="lnr lnr-user"></i> New Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="dashboard-form" method="post" action="#" autocomplete="off">
                    <div class="form-group">
                        <label>First Name</label>
                        <input class="form-control" type="text" name="" value="Smith" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input class="form-control" type="text" name="" value="John" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input class="form-control" type="email" name="" value="johansmith@gmail.com" required="" autocomplete="off">
                    </div>
                    <div class="form-group phone-block">
                        <label>Phone No.</label>
                        <input class="form-control" id="phone" type="tel" name=""  value="98658 7856" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <select class="nice-select form-control">
                            <option>Egypt</option>
                            <option>Saudi Arabia</option>
                            <option>United arab emarat</option>
                            <option>Moracco</option>
                            <option>Palastin</option>
                            <option>Sodan</option>
                            <option>Bahrin</option>
                            <option>Kwuit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input class="form-control" type="text" name="" value="Alexandria" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input class="form-control" type="text" name="" required="" autocomplete="off">
                    </div>
                    <div class="form-group text-right">
                        <button class="btn btn-block btn-sumbit" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- end customer --}}

{{-- msg toasts --}}

@include('pos::cashier.layouts._msg')

{{-- end toasts --}}

{{-- end content --}}
<edit-profile :auth.sync="auth" @update="(auth)=> this.auth = auth " :url="'{{route('cashier.update-profile')}}'"/>
{{-- model --}}
<div class="modal fade" id="edit-profile" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="ti-pencil"></i> Edit Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="dashboard-form" method="post" action="#" autocomplete="off">
                    <div class="form-group">
                        <label>First Name</label>
                        <input class="form-control" type="text" name="" value="Smith" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input class="form-control" type="text" name="" value="John" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input class="form-control" type="email" name="" value="johansmith@gmail.com" required="" autocomplete="off">
                    </div>
                    <div class="form-group phone-block">
                        <label>Phone No.</label>
                        <input class="form-control" id="phone" type="tel" name=""  value="98658 7856" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Current Password</label>
                        <input class="form-control" type="text" name="" placeholder="Current Password" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input class="form-control" type="text" name="" placeholder="New Password" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Retype Password</label>
                        <input class="form-control" type="text" name="" placeholder="Retype Password" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-block btn-sumbit" type="submit">Update Your Information</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- end model --}}
@stop