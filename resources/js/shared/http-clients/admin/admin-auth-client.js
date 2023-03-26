const BASE_URL = `admin-auth`;
export default {
    login(formValue) {
        return axios.post(`${BASE_URL}/login`, formValue);
    },
    logout() {
        return axios.get(`${BASE_URL}/logout`);
    },
    verifyToken() {
        return axios.get(`${BASE_URL}/verify-token`);
    },
}