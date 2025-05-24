const resource = 'pos/coupons'
export default {


  applyCoupon(data){
    return sessionAxios.post(`${resource}/check_coupon`, data)
  }
  
}
