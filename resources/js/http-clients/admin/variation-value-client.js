const BASE_URL = `variation-values`;
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
    toggleStatus(id) {
        return axios.get(`${BASE_URL}/toggle-status/${id}`);
    },
    getItems(pageNo, pageSize, variationId, text, status) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&variation_id=${variationId}&text=${text}&status=${status !== null && status !== -1 ? status : ''}`);
    },
    getItem(id) {
        return axios.get(`${BASE_URL}/${id}`);
    },

    getAllItems() {
        return axios.get(`${BASE_URL}`);
    }
}