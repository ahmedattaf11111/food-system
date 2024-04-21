const BASE_URL = `stocks`;
export default {
    store(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    getLocations() {
        return axios.get(`${BASE_URL}/locations`);
    },
    getProducts() {
        return axios.get(`${BASE_URL}/products`);
    },
    getStocks(location_id,product_id) {
        return axios.get(`${BASE_URL}?location_id=${location_id??''}&product_id=${product_id??''}`);
    },
}