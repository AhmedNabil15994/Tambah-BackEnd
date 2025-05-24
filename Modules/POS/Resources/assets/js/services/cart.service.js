const resource = "cart/pos-service";
export default {
  addToCart(data) {
    return axios.post(`${resource}/add-or-update`, data);
  },

  addToCartBySku(data) {
    return axios.post(`${resource}/add-or-update-sku`, data);
  },

  current(user_token) {
    return axios.get(`${resource}`, {params: {
        user_token
      }});
  },

  addCompanyDelivery(data) {
    return axios.post(`${resource}/add-company-delivery-fees-condition`, data);
  },

  handleDraft(user_token) {
    return axios.get(`${resource}/handle-draft`, {params: {
        user_token
      }});
  },

  replaceCart(data) {
    return axios.post(`${resource}/repalce-cart`, data);
  },

  removeItem(id, data) {
    // console.log(data)
    return axios.post(`${resource}/remove/${id}`, data);
  },
  deleteCondtion(name, data) {
    return axios.post(`${resource}/remove-condition/${name}`, data);
  },

  deleteCarts(data) {
    return axios.post(`${resource}/clear`, data);
  },

  handleUpdatePrice(data) {
    return axios.post(`${resource}/update-item-price`, data);
  }
};
