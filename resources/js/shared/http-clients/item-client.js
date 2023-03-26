const BASE_URL = `items`;
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
    getItems(pageNo,pageSize,text) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    getAllItems(){
        return axios.get(`${BASE_URL}`);
    }
}