const resource = 'en/pos/addresses'
export default {

  citiesWithStates () {
    return sessionAxios.get(`${resource}/area/cities`)
  },
  
  list (userId) {
    return sessionAxios.get(`${resource}/list/${userId}`)
  },
 
  store (data) {
    return sessionAxios.post(`${resource}`,data);
  },
 
  update (data, addressId) {
    return sessionAxios.put(`${resource}/${addressId}`,data);
  },
}
