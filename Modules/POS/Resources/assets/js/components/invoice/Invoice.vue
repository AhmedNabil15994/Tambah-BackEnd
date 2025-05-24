<template>
    <div id="invoice">
        <div class="ticket">

            <template v-if="order">

                <!-- <img src="/poss/images/logo2.png" width="50" height="50" alt=""> -->
                <div id="header" style="border-bottom: 1px solid #cbcbcb;padding:0px 15px 0px 15px">
                    <h3>{{ settings && settings.hasOwnProperty('title') ? settings.title : '' }}</h3>
                    <p>
                        Invoice #: {{ order.id }}
                    </p>

                </div>
                <div id="contact-us" style="padding:0px 15px 0px 15px;">
                    <h3 style="margin-bottom:10px">Contact Info</h3>
                    <p>
                        Customer : {{ order.user ? order.user.name : "unknow" }}</br>

                        <template v-if="order.hasOwnProperty('address')">
                            Address : {{ order.address.state }} ,
                            {{ order.address.street }} ,
                            {{ order.address.block }} ,
                            {{ order.address.building }},
                            {{ order.address.address }}</br>

                        </template>
                        Email : {{ order.user ? order.user.email : "unknow" }}</br>
                        Phone : {{ order.user ? order.user.mobile : "unknow" }}</br>
                    </p>
                </div>

                <table class="table-border" style="margin-top:8px;">
                    <thead>
                        <tr>
                            <th class="description"
                                style="text-align:left;width: 110px;border-bottom: 1px solid #cbcbcb;">Item</th>
                            <th class="sub-total"
                                style="text-align:right;width: 110px;border-bottom: 1px solid #cbcbcb;">Price</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="product in order.products" :key="'order_print_' + product.id">
                            <td class="description" style="text-align:left">
                                <span style="font-weight:700">{{ product.qty }}</span> X {{ product.hasOwnProperty('title') ? product.title :'' }}
                                </br>{{ product.sku }}
                            </td>
                            <td class="price" style="text-align:right">
                                <template v-if="product.selling_price == product.origin_price">
                                    {{ product.selling_price }}
                                </template>
                                <template v-else>
                                    <del>{{ product.origin_price }}</del> <br /> <span>{{ product.selling_price
                                    }}</span>
                                </template>
                                {{ currency }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="border-bottom: 1px solid #cbcbcb;padding:0px 15px 0px 15px">
                    <table class="table-border" style="margin-top:8px">
                        <thead>
                            <tr>
                                <th class="description" style="text-align:left;width: 110px"></th>
                                <th class="sub-total" style="text-align:right;width: 110px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" style="text-align:right"><span>
                                        SubTotal</span> {{ order.subtotal }} {{ currency }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:right"><span>
                                       Delivery Price</span> {{ order.shipping }} {{ currency }}</td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align:right"><span>Total</span> {{
                                        order.total
                                }} {{ currency }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p style="text-align: center;">
                    Saved By {{ order.cashier.name }}
                    </br>
                    {{ order.created_at }}
                </p>

                <p v-html="this.settings && this.settings.hasOwnProperty('invoice_description') ? this.settings.invoice_description[$i18n.locale] : ''"></p>

            </template>
            <p style="text-align: center;">
            Thank You for Choosing Us.
                <br />{{ settings && settings.hasOwnProperty('mobile') ? settings.mobile : "" }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['order', "user", "settings"],
    computed: {
        getUrl() {
            return $origin
        }
    },

}
</script>

<style scoped>
</style>

