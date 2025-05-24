<template>
    <div>
        <div class="bg-white list-items">

            <div class="search-customer">
                <div class="d-flex align-items-center row">
                    <div class="col-md-8">
                        <select class="select form-control" v-select2="clientId" @change="handleClient"
                            name="customers">
                            <option selected disabled>
                                {{ $t("main.Select_Client") }}
                            </option>
                            <option :selected="user.id == clientId" v-for="user in users" :value="user.id"
                                :key="'customer_' + user.id">
                                {{ user.name }} ( {{ user.mobile }} )
                            </option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <button :title="$t('main.New_Customer')" @click="newCustomer">
                            <i class="ti-plus"></i>
                            {{ $t("main.New") }}
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button :title="$t('main.Edit_Customer')" @click="editCustomer">
                            <i class="ti-eye"></i>
                            {{ $t("main.Edit") }}
                        </button>
                    </div>
                </div>
            </div>

            <template v-if="addressLoading == false">
                <div class="search-customer">

                    <div class="d-flex align-items-center row">
                        <div class="col-md-8">
                            <select class="select form-control" v-select2="addressId" @change="handleAddress"
                                data-placeholder="Select Address" name="customers">
                               
                                <option :selected="address.id == addressId" v-for="address in addresses"
                                    :value="address.id" :key="'address_' + address.id">
                                    {{ address.country.title }} ,
                                    {{ address.city.title }} ,
                                    {{ address.state }} ,
                                    {{ address.street }}
                                </option>

                            </select>
                        </div>
                    <div class="col-md-2">
                            <button :title="$t('main.New_Address')" @click="newAddress"><i class="ti-plus"></i>
                                {{ $t("main.New") }}
                            </button>
                        </div>
                    <div class="col-md-2">
                            <button :title="$t('main.Edit_Address')" data-toggle="modal" data-target="#new-customer"
                                @click="editAddress"><i class="ti-eye"></i>
                                {{ $t("main.Edit") }}
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <loading />
            </template>
            <hr />

            <!--  -->

            <template v-if="carts && carts.items.length > 0">
                <!--  items -->
                <div class="table-items">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">{{ $t("main.product") }}</th>
                                <th scope="col">{{ $t("main.type") }}</th>
                                <th scope="col">{{ $t("main.qty") }}</th>
                                <th scope="col">{{ $t("main.subtotal") }}</th>
                                <th scope="col">{{ $t("main.action") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item) in carts.items " :key="'cart_' + item.id">
                                <th scope="row">
                                    <div class="product-list d-flex">
                                        <div class="p-img">
                                            <img class="img-fluid" :src="item.image" alt="" />
                                        </div>
                                        <h6 class="pr-name">
                                            {{ item.title }} 
                                            <span v-if="item.product_type == 'variation' && item.hasOwnProperty('variation_title')"> <br> {{item.variation_title}}</span>
                                            <br>
                                            {{ item.sku }}
                                        </h6>
                                    </div>
                                </th>
                                <td><span class="badge "
                                        :class="{ 'badge-info': item.product_type == 'variation', 'badge-primary': item.product_type != 'variation' }">{{
                                                item.product_type
                                        }}
                                    </span> </td>
                                <td>
                                    <div class="buttons-added quantity">
                                        <template v-if="!laodingActionsOrder.hasOwnProperty(`item_${item.id}`)">
                                            <button class="sign plus" @click.prevent="() => increase(item)"><i
                                                    class="fa fa-plus"></i></button>
                                            <input type="number" :value="item.qty" min="1" :max="item.max_qty"
                                                @keyup.enter="(event) => changeInput(item, event.target.value)"
                                                title="Qty" class="input-text qty text" size="1">
                                            <button class="sign minus" @click.prevent="() => decrease(item)"><i
                                                    class="fa fa-minus"></i></button>
                                        </template>
                                        <loading v-else />
                                    </div>
                                </td>
                                <td><span class="p-price">{{ currency }}
                                        <template v-if="item.price != item.old_price">
                                            <del class="text-danger">{{ item.old_price }}</del>
                                            {{ item.price }}
                                        </template>
                                        <template v-else>
                                            {{ item.price }}
                                        </template>
                                    </span></td>
                                <td>
                                    <template v-if="!laodingActionsOrder.hasOwnProperty(`item_${item.id}`)">
                                        <button class="remove-item" @click="(event) => delteItem(item, event.target)"
                                            type="submit"><i class="ti-trash"></i></button>
                                        <button class="edit-item" @click="(event) => editPrice(item, event.target)"
                                            type="submit"><i class="ti-brush"></i></button>
                                    </template>
                                    <loading v-else />

                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>

                <!-- summary -->
                <div class="total-summary">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="price-block d-flex align-items-center">
                                <h6>{{$t("main.No_Items")}}</h6>
                                <p>{{ $t("main.items_count", { number: 'count' in carts ? carts.count : 0 }) }}</p>
                            </div>
                            <div class="price-block d-flex align-items-center">
                                <h6>{{$t("main.Sub_Total")}}</h6>
                                <p>{{ currency }} {{ 'subTotal' in carts ? carts.subTotal : 0 }}</p>
                            </div>
                            <template v-for="(conditon, index) in  carts.conditions">
                                <div class="price-block d-flex align-items-center" :key="'condtion_' + index"
                                    v-if="conditon.type != 'coupon_discount'">
                                    <h6>Total {{ conditon.name }} </h6>
                                    <p>{{ currency }} {{ conditon.value }}</p>
                                </div>
                            </template>
                            <!-- <div class="price-block d-flex align-items-center">
                                            <h6>Total Shipping</h6>
                                            <p>KD 27.00</p>
                                        </div> -->
                        </div>
                        <div class="col-md-6">
                            <div class="price-block d-flex align-items-center">
                                <h6>{{$t("main.Discount")}}</h6>
                                <input type="text" :value="couponCondtion ? couponCondtion.value : ''" readonly
                                    class="form-control discount-inpt" />
                                <span></span>
                            </div>
                            <div class="price-block d-flex align-items-center">
                                <h6>{{$t("main.Coupon")}}</h6>
                                <input type="text" @keypress.enter="(event) => applyCoupon(event.target.value)"
                                    :value="couponCondtion ? couponCondtion.code : ''"
                                    class="form-control discount-inpt" />
                                <button style="padding: 7px 12px;" class="btn btn-danger" v-if="couponCondtion" @click="deleteCoupon"><i
                                        class="fa fa-trash"></i> </button>
                            </div>
                            <div class="price-block d-flex align-items-center">
                                <h6>{{$t("main.add_delivery_price")}}</h6>
                                <input type="checkbox" @change="handleDeliveryPrice" class="form-control discount-inpt" style="width: 19px;"/>
                            </div>
                            <div class="d-flex total-price align-items-center">
                                <h6>{{$t("main.Total")}}</h6>
                                <p>{{ currency }} {{ 'total' in carts ? carts.total : 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- buttons -->
                <div class="total-summary final">
                    <div class="summ-actions d-flex align-items-center">
                        <template v-if="loading == false">
                            <button class="btn btn-danger" @click="(event) => deleteCart(event.target)"><i
                                    class="ti-trash"></i>{{ $t("main.clear_carts") }}</button>
                            <!-- <a class="btn btn-sumbit3" href="invoice.html" target="_blank"><i class="ti-files"></i> Quotation</a> -->
                            <button class="btn btn-sumbit2" @click.prevent="handleDraft"><i class="ti-save-alt"></i>
                                {{ $t("main.Draft") }}
                                </button>
                            <button class="btn  btn-success" data-toggle="modal" data-target="#pay-methods"><i
                                    class="ti-credit-card"></i> {{ $t("main.Pay") }}
                                    </button>
                        </template>
                        <template v-else>
                            <loading />
                        </template>

                    </div>
                </div>
            </template>
            <template v-else>
                <div style="min-height:360px" class="d-flex d-flex justify-content-center  align-items-center">
                    <p class="message-nofound text-center"><i class="ti-face-sad"></i> {{ $t("main.There_is_no_items_in") }} </p>
                </div>
            </template>
        </div>

        <draft-component :user-id="userId" v-on:selected="(carts) => $emit('update', carts)" />
        <edit-price-modal :item="editItem" @updateItem="updateCartItem" :user-id="userId" />

        <!-- customer edit and add  model -->

        <div class="modal fade" id="customerModel" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="lnr lnr-user"></i>
                            {{ customerModel.title ? customerModel.title :  $t("main.New_Customer") }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" v-model="customerModel.customerId">
                        <div>
                            <label>{{$t("main.Name")}}</label>
                            <input style="margin-bottom:0px" class="form-control" type="text" name="name"
                                v-model="customerModel.name" required="" autocomplete="off">
                            <label style="color: red;">
                                {{ errors.name }}
                            </label>
                        </div>
                        <div>
                            <label>{{$t("main.Mobile")}}</label>
                            <input style="margin-bottom:0px" class="form-control" id="phone" type="tel" name="mobile"
                                v-model="customerModel.mobile" required="" autocomplete="off">
                            <label style="color: red;">
                                {{ errors.mobile }}
                            </label>
                        </div>
                        <div>
                            <label>{{$t("main.Email")}}</label>
                            <input style="margin-bottom:0px" class="form-control" type="text" name="email"
                                v-model="customerModel.email" required="" autocomplete="off">
                            <label style="color: red;">
                                {{ errors.email }}
                            </label>
                        </div>
                        <div class="form-group text-right">

                            <template v-if="customerModel.loading == false">
                                <button class="btn btn-block btn-sumbit" @click="saveCustomer"
                                    type="button">{{$t("main.Save")}}</button>
                            </template>
                            <template v-else>
                                <center>
                                    <loading />
                                </center>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end customer edit and add  model -->

        <!-- Addresses edit and add  model -->

        <div class="modal fade" id="addressModel" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="lnr lnr-user"></i>
                            {{ addressModel.title ? addressModel.title : $t("main.New_Address") }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                             <template v-if="cityLoading == false">
                                <div class="col-md-6">
                                    <label>{{$t("main.State")}}</label>
                                    <select v-model="addressModel.state" class="select-detail select2 form-control area_selector" tabindex="-1"
                                        aria-hidden="true" data-placeholder="Select State" name="state" v-select2=""
                                        @change="setState">
                                        <option>
                                            {{$t("main.Select State")}}
                                        </option>
                                        <optgroup v-for="city in cities" :label="city.title">
                                            <option v-for="state in city.states" v-select2="" :value="state.id"
                                                :key="'state_' + state.id">
                                                {{ state.title }}
                                            </option>
                                        </optgroup>
                                    </select>

                                    <label style="color: red;">
                                        {{ errors.city_id }}
                                    </label>
                                </div>

                            </template>

                            <div class="col-md-6">
                                <label>{{$t("main.street")}}</label>
                                <input style="margin-bottom:0px" class="form-control" id="street" type="text"
                                    name="street" v-model="addressModel.street" required="" autocomplete="off">
                                <label style="color: red;">
                                    {{ errors.street }}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label>{{$t("main.block")}}</label>
                                <input style="margin-bottom:0px" class="form-control" id="block" type="text"
                                    name="block" v-model="addressModel.block" required="" autocomplete="off">
                                <label style="color: red;">
                                    {{ errors.block }}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label>{{$t("main.building")}}</label>
                                <input style="margin-bottom:0px" class="form-control" id="building" type="text"
                                    name="building" v-model="addressModel.building" required="" autocomplete="off">
                                <label style="color: red;">
                                    {{ errors.building }}
                                </label>
                            </div>
                            <div class="col-md-12">
                                <label>{{$t("main.address")}}</label>
                                <input style="margin-bottom:0px" class="form-control" id="block" type="text"
                                    name="block" v-model="addressModel.address" required="" autocomplete="off">
                                <label style="color: red;">
                                    {{ errors.address }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group text-right">

                            <template v-if="addressModel.loading == false">
                                <button class="btn btn-block btn-sumbit" @click="saveAddress"
                                    type="button">{{$t("main.Save")}}</button>
                            </template>
                            <template v-else>
                                <center>
                                    <loading />
                                </center>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end customer edit and add  model -->
    </div>
</template>

<script>

import DraftComponent from '../draft/DraftComponent.vue';
import LoadingScreen from '../loading/LoadingScreen.vue';
import services from '../../services/index.js';
import EditPriceModal from '../modals/EditPriceModal.vue';
export default {
    components: { DraftComponent, LoadingScreen, EditPriceModal },
    props: ["carts", "userId", "users", "client", "address","delivery_price"],
    emits: ["update:addressValue", "update:clientValue","update:deliveryPriceValue"],
    created() {
        this.clientId = this.client;
        this.addressId = this.address && this.address.hasOwnProperty('id') ?  this.address.id : null;
    },
    mounted() {

        this.updateClientId(this.clientId);        
        this.listCities();
        // edit 
        $("#edit-price-modal").on("hidden.bs.modal", () => {

            this.editItem = null
        });

        $(".select-ajex").select2({
            ajax: {
                "url": "/api/users/list",
                delay: 500,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        selectId: this.clientId,
                        "user_id": this.userId
                    };
                },
                processResults: (data) => {
                    this.users = data.data
                    return {
                        results: data.data
                    };
                },
            },
        });

    },
    methods: {
        increase(item) {
            if (this.checxMaxQuqntity(item)) {
                item.qty += 1
                this.updateItem(item)
            }else{
                this.alertMsg(this.$t("main.qty_max_alert"))
            }

        },
        decrease(item) {
            if (this.checxMinQuqntity(item)) {
                item.qty -= 1
                this.updateItem(item)
            }

        },
        changeInput(item, value) {
            if (value > item.max_qty) {
                this.alertMsg(this.$t("main.qty_max_alert"))
                value = item.max_qty
            }
            if (value < 1) {
                this.alertMsg(this.$t("main.qty_min_alert"))
                value = 1
            }
            item.qty = value
            this.updateItem(item)



        },
        checxMaxQuqntity(item) {
            if (item.max_qty == null || item.qty < item.max_qty) {
                return true
            }
            return false;
        },
        checxMinQuqntity(item) {
            if (item.qty >= 1) {
                return true
            };
            this.alertMsg(this.$t("main.qty_min_alert"))
        },

        updateCartItem(newItem){
            let oldItem = this.getItemFromCartListById(newItem.id);
            oldItem = newItem;
            this.successUpdateItem();
        },

        delteItem(item, button) {
            button.disabled = true

            this.$set(this.laodingActionsOrder, `item_${item.id}`, item.id)
            let removingElement = this.deleteItemFromCartListById(item.id);

            if (removingElement) {

                button.disabled = false;
                this.$delete(this.laodingActionsOrder, `item_${item.id}`);
                this.successDelteItem()
                this.$delete(this.laodingActionsOrder, `item_${item.id}`)
            } else {

                button.disabled = false;
                this.$delete(this.laodingActionsOrder, `item_${item.id}`)
            }

        },

        getItemFromCartListById(productId) {
            return this.carts.items.find(item => item.id == productId);
        },

        deleteItemFromCartListById(productId) {

            let removingElement = this.carts.items.map(cartItem => cartItem.id).indexOf(productId);
            if (removingElement || removingElement == 0) {
                this.carts.items.splice(removingElement, 1);
                return true;
            }

            return false;
        },

        updateItem(item, oldQty) {
            let id = item.id;

            if (item.product_type == "variation") {
                id = (id.split("-"))[1]
            }
            
            if (item.qty <= 0) {
                let removingElement = this.deleteItemFromCartListById(item.id);

                if (removingElement) {
                    this.successDelteItem()
                }
            } else {
                this.successUpdateItem();
            }
        },
        successUpdateItem() {

            toast.success()
            this.$emit("update", this.carts)
        },
        successDelteItem() {

            this.$toast.success(this.$t("main.remove_success"));
            toast.success()
            this.$emit("remove", this.carts)
        },
        handleErrorInAjex(error) {
            let res = error.response

            this.loading = false

            if ("data" in res) this.$toast.error(res.data.message);
        },
        clearErrors() {
            for (var error in this.errors) {
                this.errors[error] = null;
            }
        },
        showErrors(errors) {
            for (var error in this.errors) {
                if (errors.hasOwnProperty(error)) {
                    this.errors[error] = errors[error][0];
                }
            }
        },
        alertMsg(msg, type = "error") {

            this.$toast.open({
                message: msg,
                type: type,
                // all of other options may go here
            });

        },
        getVendors() {
            return [...new Set(this.carts.items.map((object) => object.vendor_id ?? 0))]
        },
        applyCoupon(coupon) {

            if (coupon.length > 0) {
                services.couponService.applyCoupon({
                    code: coupon,
                    cart: this.carts,
                    user_token: this.userId,
                    vendors: this.getVendors()
                }).then((res) => {
                    
                    this.carts.conditions.push(res.data.data);
                    this.successUpdateItem();
                })
                    .catch(this.handleErrorInAjex)
            } else {

            }
        },
        deleteCoupon() {

            let removingElement = this.carts.conditions.map(coupon => coupon.type).indexOf("coupon_discount");
            if (removingElement || removingElement == 0) {
                this.carts.conditions.splice(removingElement, 1);
            }
            
            this.successUpdateItem();
        },
        deleteCart(button) {
            button.disabled = true
            swal({
                title: "",
                text: this.$t('main.Are_you_sure_removing_this_Cart?'),
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                closeOnConfirm: false,
                animation: false,
                customClass: {
                    popup: 'animated tada'
                }
            }, (isConfirm) => {

                if (isConfirm) {
                    this.loading = true;
                    this.carts.items = [];
                    this.alertMsg(this.$t("main.clear_carts"), "success")
                    this.$emit("update", this.carts);
                    button.disabled = false;
                    this.loading = false;
                    swal.close()
                } else {
                    button.disabled = false
                }
            })

        },
        handleDraft() {
            this.loading = true
            let draft = this.carts;
            if (draft) {
                draft.user = this.users.find((user) => user.id == this.clientId)
                let drafts = JSON.parse(localStorage.getItem("drafts"))
                drafts ? drafts.push(draft) : drafts = [draft]
                localStorage.setItem("drafts", JSON.stringify(drafts))
                this.loading = false
                this.$emit("update", null)
                this.$root.$emit("draft_update", draft)
            }
        },
        repalceCart() {
            let drafts = JSON.parse(localStorage.getItem("drafts"))
            drafts = drafts ? drafts : []
            this.loading = true
            services.cartService.replaceCart({ user_token: this.userId, cart: drafts[0] })
                .then(res => {
                    let carts = res.data.data;
                    this.$emit("update", carts)
                    this.loading = false
                    console.log(carts);
                }).catch(this.handleErrorInAjex)
        },

        handleDeliveryPrice(event) {
            this.$emit('update:deliveryPriceValue', this.delivery_price ? false : true);
        },

        /////////////////////////////////
        //////  handling clients ////////

        openCustomerModal() {
            this.clearErrors();
            $('#customerModel').modal('show');
        },

        closeCustomerModal() {
            $('#customerModel').modal('hide');
        },

        saveCustomer() {

            if (this.customerModel.customerId == null) {

                return this.saveNewCustomer();
            } else {

                return this.updateCustomer();
            }
        },

        newCustomer() {

            for (var element in this.customerModel) {
                this.customerModel[element] = this.customerModel[element] == true || this.customerModel[element] == false ? false : null;
            }

            this.openCustomerModal();
        },

        saveNewCustomer() {

            this.clearErrors();
            this.customerModel.loading = true;

            services.CustomerService.store(this.customerModel)
                .then((res) => {
                    var customer = res.data.data.user;
                    
                    this.$toast.success(this.$t("main.add_success"));
                    toast.success();
                    this.customerModel.loading = false;
                    this.closeCustomerModal();
                    this.users.push({
                        id: customer.id,
                        image: customer.image,
                        mobile: customer.mobile,
                        name: customer.name,
                        email: customer.email,
                    });

                    this.updateClientId(customer.id);
                }
                )
                .catch(err => {
                    var errors = err.response.data.errors;
                    this.handleErrorInAjex(err);
                    this.customerModel.loading = false;
                    this.showErrors(errors);
                });
        },

        editCustomer() {

            if (this.clientId) {

                var customer = this.getCustomerFromListById(this.clientId);

                this.customerModel.title = 'Edit Customer : ' + customer.name;
                this.customerModel.customerId = customer.id;
                this.customerModel.name = customer.name;
                this.customerModel.email = customer.email;
                this.customerModel.mobile = customer.mobile;
                this.openCustomerModal();

            } else {
                this.$toast.error("please , select Client");
            }
        },

        updateCustomer() {

            this.clearErrors();
            this.customerModel.loading = true;

            services.CustomerService.update(this.customerModel, this.customerModel.customerId)
                .then((res) => {
                    var oldCustomer = this.getCustomerFromListById(this.clientId);
                    var newCustomer = res.data.data.user;

                    oldCustomer.image = newCustomer.image;
                    oldCustomer.mobile = newCustomer.mobile;
                    oldCustomer.name = newCustomer.name;
                    oldCustomer.email = newCustomer.email;

                    this.$toast.success(this.$t("main.add_success"));
                    toast.success();
                    this.customerModel.loading = false;
                    this.closeCustomerModal();
                }
                )
                .catch(err => {
                    var errors = err.response.data.errors;
                    this.handleErrorInAjex(err);
                    this.customerModel.loading = false;
                    this.showErrors(errors);
                })
                .finally(() => {
                    $(".select").select2();
                });
        },

        getCustomerFromListById(CustomerId) {
            return this.users.find(customer => customer.id == CustomerId);
        },

        updateClientId(ClientId) {
            this.addressLoading = true;

            this.clientId = ClientId;

            services.AddressService.list(ClientId)
                .then((res) => {

                    this.addresses = res.data.data;
                    this.addresses.length > 0 ?  this.updateAddressId(this.addresses[0].id) : null;
                    this.addressLoading = false;
                })
                .catch((error) => {
                    this.addressLoading = false;
                });

            this.$emit('update:clientValue', ClientId);
        },

        handleClient(event) {

            this.updateClientId(event.target.value)
        },
        ///////////////////////////////////////

        //handling addresses on select new address

        openAddressModal() {

            this.clearErrors();
            $('.selectModal').select2({

                dropdownParent: $('#addressModel')
            });
            $('#addressModel').modal('show');
        },

        closeAddressModal() {
            $('#addressModel').modal('hide');
        },

        setState(event) {
            this.addressModel.state = event.target.value
        },

        saveAddress() {

            if (this.addressModel.addressId == null) {

                return this.saveNewAddress();
            } else {

                return this.updateAddress();
            }
        },

        newAddress() {

            for (var element in this.addressModel) {
                this.addressModel[element] = this.addressModel[element] === true || this.addressModel[element] === false ? false : null;
            }

            this.openAddressModal();
        },

        saveNewAddress() {

            this.clearErrors();
            this.addressModel.loading = true;
            this.addressModel.user_id = this.clientId;

            services.AddressService.store(this.addressModel)
                .then((res) => {
                    var address = res.data.data;
                    this.addresses.push(address);
                    address.length > 0 ?  this.updateAddressId(address[0].id) : null;
                    
                    this.$toast.success(this.$t("main.add_success"));
                    toast.success();
                    this.addressModel.loading = false;
                    this.closeAddressModal();
                }
                )
                .catch(err => {
                    var errors = err.response.data.errors;
                    this.handleErrorInAjex(err);
                    this.addressModel.loading = false;
                    this.showErrors(errors);
                });
        },

        editAddress() {

            if (this.addressId) {

                var address = this.getAddressFromListById(this.addressId);

                this.addressModel.addressId = address.id;
                this.addressModel.add = false;
                this.addressModel.title = 'Edit Address : #' + address.id;

                for (var element in address) {

                    if (this.addressModel.hasOwnProperty(element)) {
                        this.addressModel[element] = address[element];
                    }
                }
                this.addressModel.state = address.state_id;
                $(".select").select2();

                $('.selectModal').select2({
                    dropdownParent: $('#addressModel')
                });
                this.openAddressModal();
            } else {
                this.$toast.error("please , select address");
            }
        },

        updateAddress() {

            this.clearErrors();
            this.addressModel.loading = true;

            services.AddressService.update(this.addressModel, this.addressModel.addressId)
                .then((res) => {

                    this.$toast.success(this.$t("main.add_success"));
                    toast.success();
                    this.addressModel.loading = false;
                    this.closeAddressModal();
                    var newAddress = res.data.data;
                    this.updateClientId(this.clientId);
                    this.updateAddressId(newAddress.id);
                }
                )
                .catch(err => {
                    var errors = err.response.data.errors;
                    this.handleErrorInAjex(err);
                    this.addressModel.loading = false;
                    this.showErrors(errors);
                })
                .finally(() => {
                    $(".select").select2();
                    $('.selectModal').select2({
                        dropdownParent: $('#addressModel')
                    });
                });
        },

        getAddressFromListById(AddressId) {
            return this.addresses.find(address => address.id == AddressId);
        },

        handleAddress(event) {
            this.updateAddressId(event.target.value)
        },

        updateAddressId(id) {
            this.addressId = id;
            this.$emit('update:addressValue', id);
        },


        ////////////////////

        listCities() {

            this.cityLoading = true;
            services.AddressService.citiesWithStates()
                .then((res) => {
                    this.cities = res.data.data;
                    this.cityLoading = false;
                })
                .catch()
                .finally(() => {
                    $(".select").select2();

                    $('.selectModal').select2({
                        dropdownParent: $('#addressModel')
                    });
                });
        },

        editPrice(item, element) {
            let modal = $("#edit-price-modal")
            if (modal) {
                modal.modal()
                this.editItem = item
            }
        },
        addSkuToCart: _.debounce(function () {
            if (this.sku.length > 0)
                this.addSku(this.sku)
        }, 1000),
        addSku(sku) {

            this.$refs.sku_input.disabled = true
            services.cartService.addToCartBySku({
                user_token: this.userId,
                sku: sku,

            })
                .then((res) => {
                    this.$toast.success(this.$t("main.add_success"));
                    toast.success()
                    this.$emit("update", res.data.data)
                    this.$refs.sku_input.disabled = false
                }
                )
                .catch(err => {
                    this.handleErrorInAjex(err)
                    this.$refs.sku_input.disabled = false

                })
        },
    },
    computed: {
        couponCondtion() {
            let coupon = this.carts.conditions.find((condtion) => condtion.type == "coupon_discount")
            return coupon
        },

    },
    data() {
        return {
            have_discount: false,
            coupon: "",
            countries: [],
            cities: [],
            states: [],
            loading: false,
            cityLoading: false,
            stateLoading: false,
            user: null,
            laodingActionsOrder: {},
            clientId: null,
            editItem: null,
            sku: "",
            addressLoading: false,
            addresses: [],
            addressId: null,
            customerModel: {
                customerId: null,
                title: null,
                name: null,
                mobile: null,
                email: null,
                loading: false,
            },
            addressModel: {
                addressId: null,
                title: null,
                loading: false,
                street: null,
                block: null,
                building: null,
                address: null,
                user_id: null,
                state: null,
                add: true,
            },
            errors: {
                name: null,
                mobile: null,
                email: null,
                state: null, 
                block: null,
                building: null,
                address: null,
                street: null,
            },


        }
    }
}
</script>
