const resource = 'catalog';
export default {

  getMianCategory () {
    return axios.get(`${resource}/pos-service/all-categories?with_sub_categories=yes`)
  },
  
}
