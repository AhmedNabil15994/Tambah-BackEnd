const resource = 'pos/areas'
export default {

  listCountries () {
    return axios.get(`${resource}/countries`);
  },

  listCities (countryId) {
    return axios.get(`${resource}/cities/${countryId}`);
  },

  listState (stateId) {
    return axios.get(`${resource}/states/${stateId}`,{
      params:{
        flag:"city"
      }
    });
  },
  
}