const resource = 'en/pos/users'
export default {
 
  store (data) {
    return sessionAxios.post(`${resource}`,data);
  },

  update (data, customerId) {
    return sessionAxios.put(`${resource}/${customerId}`,data);
  },
}
