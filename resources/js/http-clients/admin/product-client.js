const BASE_URL = `products`;
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
    getItems(pageNo, pageSize, brand, published, text) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}
        &brand_id=${brand !== null && brand !== -1 ? brand : ''}
        &published=${published !== null && published !== -1 ? published : ''}`);
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
    getTags() {
        return axios.get(`${BASE_URL}/tags`);
    },
    getTaxes() {
        return axios.get(`${BASE_URL}/taxes`);
    },
}