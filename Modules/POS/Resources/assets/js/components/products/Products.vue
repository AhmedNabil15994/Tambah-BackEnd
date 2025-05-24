<template>
  <div class="inner-page">
    <div class="cust-pad">
      <div class="row">

        <!-- left for orders -->
        <div class="col-md-6">
          <orders :carts.sync="carts" :user-id="auth.id" :users="users" :client="client_id" :address="address_id"
            :delivery_price="delivery_price" v-on:remove="setCart" v-on:update="setCart"
            @update:clientValue="(value) => client_id = value"
            @update:deliveryPriceValue="(value) => delivery_price = value"
            @update:addressValue="(value) => address_id = value" />

        </div>

        <!--  tab for rigth for priducts -->
        <div class="col-md-6">
          <!-- start products -->
          <div class="card bg-white list-products">
            <!-- filter -->

            <div class="card-header search-products d-flex align-items-center" id="headingOne">
              <div class="head-item">
                <div class="row">
                  <div class="col-md-9">
                    <div class="search-barcode">
                      <img src="/poss/images/barcode.jpg" />
                      <input class="form-control" type="text" v-model="search" @input="filterInput" ref="search_input"
                        @keypress.enter="fireProductHandlerFiler" :placeholder="$t('main.search_by')" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="price-block d-flex align-items-center">
                      <p style="margin-bottom:0px">{{$t("main.add_to_cart")}}</p>
                      <input type="checkbox" @change="handleAddToCart" class="form-control discount-inpt"
                        style="width: 19px;margin: 0px 19px;" />
                    </div>
                  </div>

                  <div class="col-md-12">
                    <select class="select form-control" v-model="category_id" name="category_id"
                      v-select2="category_id">
                      <option value="">{{ $t("main.all_catagroies") }}</option>
                      <template v-for="category in categories">

                        <optgroup v-if="category.sub_categories.length > 0" :label="category.title"
                          :key="'cate' + category.id">
                          <option v-for="subcategory in category.sub_categories" :value="subcategory.id"
                            :key="`sub_${category.id}_${subcategory.id}`">
                            {{ subcategory.title }}
                          </option>
                        </optgroup>
                        <option v-else :key="'cate' + category.id" :value="category.id">
                          {{ category.title }}
                        </option>
                      </template>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- end filter -->

            <!-- list products  -->
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
              <div class="card-body">
                <div class="products">

                  <div class="row">

                    <div class="col-md-2 col-4" v-for="(product, index) in products" :key="`${index}_products`">
                      <template v-if="!loadinginsAdd.hasOwnProperty(`product_${product.id}`)">
                        <product-component :product="product" @click.native="() => openModelProduct(index)" />
                      </template>
                      <template v-else>
                        <div class="product-blk d-flex justify-content-center  align-items-center" data-toggle="tooltip"
                          data-placement="bottom" title="Product Code" style="min-height:165px">
                          <loading />
                        </div>
                      </template>

                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <infinite-loading :identifier="productinfiniteId" @infinite="productsHandler">
                        <div slot="no-results">No More</div>
                      </infinite-loading>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <!-- end list product  -->
          </div>
          <!-- end products -->
          <!-- start transaction -->
          <transaction :routes="routes" v-on:refund="(refund) => $emit('total', refund * -1)" :user="auth"
            :settings="settings" />
          <!-- end transaction -->
        </div>

      </div>
    </div>

    <!-- variation Model -->
    <product-variation-modal v-on:add="addVariation" :carts="carts" :product="product" :user-id="auth.id" />
    <!-- Payment Model  -->

    <payment-modal :carts="carts" :user-id="auth.id" :auth="auth" :client-id="client_id" :address-id="address_id"
      :delivery_price="delivery_price" v-on:payment="paymentConfrim" />

  </div>
</template>

<script>
import ProductComponent from "./ProductComponent";
import ProductAddonModal from "../modals/productAddonModal";
import service from "../../services/index.js"
import productVariationModal from '../modals/productVariationModal.vue';
import services from '../../services/index.js';
import PaymentModal from '../modals/paymentModal.vue';
import Transaction from '../transaction/Transaction.vue';
export default {
  props: ["auth", "users", "defultClient", "totalDisplay", "routes","settings"],
  components: {
    ProductComponent,
    productctModal: ProductAddonModal,
    productVariationModal: productVariationModal,
    PaymentModal,
    Transaction
  },
  computed: {
    filterSearch() {
      return `${this.brand_id}&${this.category_id}`
    }
  },
  watch: {
    filterSearch: function () {
      this.fireProductHandlerFiler()
    }
  },
  methods: {

    alertMsg(msg, type = "error") {

      this.$toast.open({
        message: msg,
        type: type,
        // all of other options may go here
      });

    },

    handleCartStorage() {
        let carts = JSON.parse(localStorage.getItem("carts"))
        
        if(carts){
          this.carts = carts;
        }
    },

    handleAddToCart(event) {
      this.addToCart = this.addToCart ? false : true;
    },
    addTest() {
      this.categories.push({ ...this.categories[0] })
    },
    productsHandler($state,counter = 0) {
      service.productService.list({
        page: this.pageProducts,
        search: this.search,
        // branch_id:this.auth.branch_id ,
        category_id: this.category_id,
        with_categories_id: this.pageProducts == 1 ? 1 : 0,
        display_type: "pos"

      }).then(res => {
        let products = res.data.data.products;
        if (products.data.length > 0) this.products.push(...products.data);
        if (products.links.next) {
          this.pageProducts += 1;
          $state.loaded();
          counter++

          if (products.meta.last_page >= this.pageProducts && counter < 5) this.productsHandler($state,counter);

        } else {
          $state.complete();
        }

      })
    },
    filterInput: _.debounce(function () {
      if (this.addToCart) {
        if (this.search)
          this.addSku(this.search);
      } else {

        this.fireProductHandlerFiler()
      }
    }, 500),

    fireProductHandlerFiler() {
      this.pageProducts = 1;
      this.productinfiniteId += 1;
      this.products = []
    },
    addSku(sku) {

      this.$refs.search_input.disabled = true
      services.cartService.addToCartBySku({
        sku: sku,
      })
        .then((res) => {
          this.$toast.success(this.$t("main.add_success"));
          toast.success()
          var product = res.data.data;

          if (product.type == 'variant') {

            var itemInCart = this.carts.items.find(item => item.id == 'var-' + product.id),
              askedQty = itemInCart ? itemInCart.qty + 1 : 1;

            if (!product.qty || product.qty >= askedQty) {
              //create or update item in cart

              this.buildVariationCartItem(product, askedQty, product.product.title, itemInCart);

            } else {
              this.alertMsg(this.$t("main.qty_max_alert"))
            }
          } else {
            this.addProduct(product);
          }

          this.$refs.search_input.disabled = false;
          this.search = '';
          this.$refs.search_input.focus();
        }
        )
        .catch(err => {
          this.handleErrorInAjex(err)
          this.$refs.search_input.disabled = false
        })
    },

    openModelProduct(index) {

      this.product = this.products[index]
      if (productAddonModal && this.product.variations_values.length > 0) {
        productAddonModal.modal()

      }
      else {
        this.addProduct(this.product)
      }

    },

    addProduct(product) {

      //open loading in target product where adding to cart
      this.$set(this.loadinginsAdd, `product_${product.id}`, product.id)

      //check if product exists in cart
      var itemInCart = this.getItemFromCartListById(product.id),
        askedQty = itemInCart ? itemInCart.qty + 1 : 1;
        
      if (product.qty == null || product.qty >= askedQty) {

        //create or update item in cart
        this.buildCartItem(product, askedQty, itemInCart);

        this.$toast.success(this.$t("main.add_success"));
        toast.success();
      } else {

        this.alertMsg(this.$t("main.qty_max_alert"))
      }

      this.$delete(this.loadinginsAdd, `product_${product.id}`);
    },

    getProductFromListById(productId) {
      return this.products.find(product => product.id == productId);
    },

    getItemFromCartListById(productId) {
      return this.carts.items.find(item => item.id == productId);
    },

    buildCartItem(product, qty, itemInCart = null) {
      if (itemInCart) {

        itemInCart.qty = qty;
      } else {
        var cartItem = {
          id: null,
          image: null,
          max_qty: null,
          price: null,
          product_type: null,
          qty: null,
          sku: null,
          title: null,
        };

        for (var element in cartItem) {
          if (cartItem.hasOwnProperty(element)) {
            cartItem[element] = product[element];
          }
        }

        cartItem.max_qty = product.qty ?? null;
        cartItem.product_type = 'product';
        cartItem.qty = qty;

        this.carts.items.push(cartItem);
      }

      this.refreshCartData();
    },

    addVariation(data) {
      this.buildVariationCartItem(data.variation, data.qty, data.productTitle, data.itemInCart);
    },

    buildVariationCartItem(variation, qty, productTitle, itemInCart = null) {

      if (itemInCart) {

        itemInCart.qty = qty;

      } else {
        var cartVariation = {
          id: null,
          image: null,
          max_qty: null,
          price: null,
          product_type: null,
          qty: null,
          sku: null,
          title: null,
          variation_title: null,
          product_options: variation.variations,
        };

        for (var element in cartVariation) {
          if (cartVariation.hasOwnProperty(element)) {
            cartVariation[element] = variation[element];
          }
        }

        var title = '';
        for (var i = 0; i < variation.variations.length; i++) {

          title += (i > 0 ? ' , ' : '') + variation.variations[i].option_value;
        }

        cartVariation.max_qty = variation.qty ?? null;
        cartVariation.product_type = 'variation';
        cartVariation.qty = qty;
        cartVariation.id = 'var-' + variation.id;
        cartVariation.title = productTitle;
        cartVariation.variation_title = title;

        this.carts.items.push(cartVariation);
      }

      this.refreshCartData();
    },

    refreshCartData() {

      var total = 0;
      
      for (var i = 0; i < this.carts.items.length; i++) {

        total += parseInt(this.carts.items[i].qty) *
          parseFloat(this.carts.items[i].price);
      }

      var coupon = this.carts.conditions.find((condtion) => condtion.type == "coupon_discount");
      
      if(coupon){
        total -= coupon.value;
      }

      this.carts.subTotal = total;
      this.carts.total = total;
      this.carts.count = this.carts.items.length;
      localStorage.setItem('carts', JSON.stringify(this.carts))
    },

    successAdded(data) {

      this.$toast.success(this.$t("main.add_success"));
      toast.success()
      this.setCart(data.data.data)
    },
    setCart(data) {
      if(data){

        this.carts = data;
      }else{
        this.carts.items = [];
        this.carts.conditions = [];
      }
      this.refreshCartData();
    },
    handleErrorInAjex(error) {
      let res = error.response
      if (res) this.$toast.error(res.data.message);
    },

    paymentConfrim(data) {

      this.$toast.success(this.$t("main.order_created"));
      toast.success()
      this.carts.items = [];
      this.carts.conditions = [];
      this.refreshCartData();
      this.$emit("total", data.total)
      this.$root.$emit('order_created', data)

    }

  },
  created() {

    this.$root.$on('carts_update', (data) => {
      // alert("ho")
      this.setCart(data)
    })

    //set 
    this.client_id = this.defultClient;
    this.handleCartStorage();
    // set client id
    // if(this.users.length > 0) this.client_id = this.users[0].id
    //  get categories 
    service.categoryService.getMianCategory()
      .then(res => this.categories = res.data.data)
  },

  mounted() {
    // register event for model
    var _instance = this
    $("#product-addon").on("hidden.bs.modal", function () {
      // put your default event here
      _instance.product = null
    });


  },
  data() {
    return {
      categories: [],
      products: [],
      product: null,
      delivery_price: false,
      test: "",
      brand_id: "",
      category_id: "",
      addToCart: false,
      search: "",
      pageProducts: 1,
      productinfiniteId: new Date(),
      carts: {
        conditions: [],
        count: 0,
        items: [],
        subTotal: 0,
        total: 0,
      },
      client_id: null,
      address_id: null,
      orderLoading: false,
      loadingSaveOrder: false,
      loadinginsAdd: {},
      sku: ""
    };
  },
};
</script>
