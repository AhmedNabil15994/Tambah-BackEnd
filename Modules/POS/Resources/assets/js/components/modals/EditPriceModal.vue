<template>
    <div class="modal fade" id="edit-price-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%!important">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="ti-pencil"></i> {{$t("main.edit_product_price")}} <span v-if="item">#{{item.title}}</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body"  v-if="item">
                <form class="dashboard-form" 
                        method="post" action="#" autocomplete="off"
                          @submit.prevent=""  
                         >
                    <error-laravel  v-if="errors" :errors="errors"/>    

                    <div class="item-addon d-flex align-items-center">
                            <div class="p-img">
                                <img class="img-fluid" :src="item.image" alt="">
                            </div>
                            <div>
                                 <h3>{{ item.title }}</h3>
                                 <div class="text-secondary">{{ item.sku }} - 
                                     <template v-if="item.price != price">
                                          <del>{{item.price}}</del> <span>{{price}}</span>
                                     </template>
                                     <template v-else>
                                          <span> {{item.price}}</span>
                                     </template>
                                  </div>
                                
                            </div>

                          
                           
                        
                    </div>

                      <hr />

                            <div class="row">
                                <div class="col-md-4">
                                    <label>{{$t("main.price")}}</label>
                                    <input  type="number" class="form-control"  name="price" v-model.number="price">
                                </div>
                                 <div class="col-md-4">
                                    <label>{{$t("main.subtotal")}}</label>
                                    <template v-if="item.price != price">
                                        <input disabled class="form-control" :value="item.qty * ( isNaN(price) ? 0 : price )" >
                                        <del>

                                            {{item.qty * item.price}}
                                        </del>
                                    </template>
                                    <template v-else>
                                         <input disabled class="form-control" :value=" item.qty * item.price" >
                                    </template>
                                   
                                </div>
                                <div class="col-md-4">
                                    <label>{{$t("main.qty")}}</label>
                                    <input disabled class="form-control" :value="item.qty">
                                </div>
                            </div>

                            <div v-if="validatedPrice.length > 0">
                                <div class="alert alert-danger">
                                    {{validatedPrice}}
                                </div>
                            </div>
                            



                            <template v-if="loadingSave == false">
                                <button :disabled="validatedPrice.length > 0 || item.price == price "
                                     @click.prevent="submit"
                                     class="btn btn-success btn-block mt-2">{{$t("main.edit")}}</button>

           
                            </template>

                             <loading v-else />



                   
        
                   
                </form> 
            </div>

        </div>
    </div>
</div>
</template>

<script>
  
    import errorLaravel from '../error/errorLaravel.vue';
    import Cartservice from "../../services/cart.service"
import services from '../../services';
    export default {
        name: 'edit-price-modal',
        components: { errorLaravel },
        props:["item","userId"],
        mounted(){
             $("#edit-price-modal").on("hidden.bs.modal", ()=>{
        
                   //    this.$emit("update:order", null)
            }); 
            
        },
        data(){
            return {
               loadingSave:false,
               errors:null ,
               price:0
            }
        },
        watch:{
           item(newValue, old){
             
              if(newValue){
                    this.price = newValue.price
              }else{
                   this.price = 0
              }
           }
        },
        computed:{
            // new_price:{
            //     get(){
            //         return this.item ? this.item.price : 0
            //     },
            //     set(new_value){
                  
            //     }
            // }

            validatedPrice(){
                if(isNaN(this.price)){
                    return this.$t("main.priceValidation.not_number")
                }
                if(this.price <= 0){
                     return this.$t("main.priceValidation.more_than", {num:0})
                }
                return ""
            }
        },
        methods:{
            submit() {
                if (this.validatedPrice.length == 0 && this.item.price != this.price) {
                    this.loadingSave = true;
                    this.item.old_price = this.item.price;
                    this.item.price = this.price;
                    this.item.edit_price_flag = true;

                    $("#edit-price-modal").modal('hide');
                    this.$emit("updateItem", this.item)
                    this.$toast.success("success");
                    this.loadingSave = false; 
                }else{
                    this.$toast.error("something error");
                }
            },
          
            handleErrorInAjex(error){
                this.loadingSave = false;   
                let res = error.response

                if(res){
                    if("data" in res && res.status  == 422) this.errors = res.data.errors
                    else this.errors = null
                    if( "data" in res )this.$toast.error(res.data.message);
                }
                this.errors = null
            
                
            },
             
        },
    }
</script>
