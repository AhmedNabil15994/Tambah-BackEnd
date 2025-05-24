const resource = 'pos/catalog/products';
export default {

  list (params) {
    return sessionAxios.get(`${resource}`, {
      params
    })
  },
  
}
