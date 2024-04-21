const BASE_URL = `orders`;
export default {
    store(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.post(`${BASE_URL}/update`, formValue);
    },
    delete(id) {
        return axios.delete(`${BASE_URL}/${id}`);
    },
    getItems(pageNo, pageSize, code, payment_status, status, location_id) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}
        &code=${code ?? ''}
        &payment_status=${payment_status ?? ''}
        &status=${status ?? ''}
        &location_id=${location_id ?? ''}`);
    },
    getItem(id) {
        return axios.get(`${BASE_URL}/${id}`);
    },

    getAllItems() {
        return axios.get(`${BASE_URL}`);
    },
    togglePublished(id) {
        return axios.get(`${BASE_URL}/toggle-publish/${id}`);
    },
    getBrands() {
        return axios.get(`${BASE_URL}/brands`);
    },
    getLocations() {
        return axios.get(`${BASE_URL}/locations`);
    },
    getVariationValues(variation_id) {
        return axios.get(`${BASE_URL}/variation-values/${variation_id}`);
    },
    getVariations() {
        return axios.get(`${BASE_URL}/variations`);
    },

    getUnits() {
        return axios.get(`${BASE_URL}/units`);
    },

    getCategories() {
        return axios.get(`${BASE_URL}/categories`);
    },
    getCustomers() {
        return axios.get(`${BASE_URL}/customers`);
    },
    getTags() {
        return axios.get(`${BASE_URL}/tags`);
    },
    getTaxes() {
        return axios.get(`${BASE_URL}/taxes`);
    },
    createCustomer(form) {
        return axios.post(`${BASE_URL}/create-customer`, form);
    },
    completeOrder(id) {
        return axios.get(`${BASE_URL}/complete-order/${id}`);
    },
    getProducts(pageNo, pageSize, brand, category, location, text) {
        return axios.get(`${BASE_URL}/products?page=${pageNo}&page_size=${pageSize}&text=${text}
        &brand_id=${brand ?? ''}
        &category_id=${category ?? ''}
        &location_id=${location ?? ''}`);
    },

}