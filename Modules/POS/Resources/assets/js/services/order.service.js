const resource = 'pos/orders'
export default {
  create(data){
    return sessionAxios.post(`${resource}`, data)
  }
  
}
