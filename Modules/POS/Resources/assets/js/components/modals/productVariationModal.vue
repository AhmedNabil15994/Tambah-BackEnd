<template>
   <div class="modal fade" id="product-addon" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                    <template v-if="product">

                        <div class="item-addon d-flex align-items-center">
                            <div class="p-img">
                                <img class="img-fluid" :src="product.image" alt="">
                            </div>
                            <div>
                                 <h3>{{ product.title }}</h3>
                                 <div class="text-secondary">{{ product.sku }}  <template v-if="product.price > 0"> -  <product-price :product="product"  /> </template></div>
                                
                            </div>
                           
                        
                        </div>

                         <div class="">
                             <div class="row">
                                    <div
                                         class="col-md-6 col-6"
                                          v-for="(variation, index) in product.variations_values"
                                         :key="`${index}_products_variation`"
                                         
                                    >
                                         <template v-if="!loadinginsAdd.hasOwnProperty(`variation_${variation.id}`)">
                                          <product-variation  :variation="variation" @click.native="()=>addVariation(variation)" />      
                                         </template>     
                                          <template  v-else>
                                            <div class="product-blk d-flex justify-content-center  align-items-center" data-toggle="tooltip" data-placement="bottom" title="Product Code" 
                                            style="min-height:165px" >
                                                    <loading />
                                            </div>
                                        </template>   
                                    </div>
                             </div>
                         </div>

                        
                        
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ProductVariationComponent from '../products/ProductVariationComponent'
    import ProductPrice from '../products/ProductPriceComponent'
    import service from "../../services"
    export default {
      components:{
           productVariation:ProductVariationComponent,
           ProductPrice
          
        },
         
    props: ["product", "userId", "carts"],
    created() {

    },
    methods: {

        alertMsg(msg, type = "error") {

            this.$toast.open({
                message: msg,
                type: type,
                // all of other options may go here
            });

        },

        addVariation(variation) {
            this.$set(this.loadinginsAdd, `variation_${variation.id}`, variation.id);

            //check if product exists in cart
            var itemInCart = this.carts.items.find(item => item.id == 'var-' + variation.id),
                askedQty = itemInCart ? itemInCart.qty + 1 : 1;
            if (!variation.qty || variation.qty >= askedQty) {

                //create or update item in cart
                this.$emit("add", {variation:variation,qty:askedQty,itemInCart:itemInCart,productTitle:this.product.title});

                this.$toast.success(this.$t("main.add_success"));
                toast.success();
            }else{
                this.alertMsg(this.$t("main.qty_max_alert"))
            }

            this.$delete(this.loadinginsAdd, `variation_${variation.id}`);
        },

        },
        computed:{
            
        },
        data(){
            return {
                loadinginsAdd : {},
            }
        }
    }
</script>
