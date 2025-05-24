const resource = 'catalog'
export default {

  list () {
    return axios.get(`${resource}/pos-service/all-brands`)
  },
  
}
