const BASE_URL = `media-manager`;
export default {
    store(formValue, config) {
        return axios.post(`${BASE_URL}`, formValue, config);
    },
    getMedia(prevLimit, recentLimit, text) {
        return axios.get(`${BASE_URL}?prev_limit=${prevLimit}&recent_limit=${recentLimit}&text=${text ? text : ''}`);
    },
    deleteMedia(id) {
        return axios.delete(`${BASE_URL}/${id}`);
    },
}