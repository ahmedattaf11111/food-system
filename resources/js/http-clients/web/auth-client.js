const BASE_URL = `auth`;
export default {
    register(formValue) {
        return axios.post(`${BASE_URL}/register`, formValue);
    },
    login(formValue) {
        return axios.post(`${BASE_URL}/login`, formValue);
    },
    verifyToken() {
        return axios.get(`${BASE_URL}/verify-token`);
    },
    forgetPassword(email) {
        return axios.get(`${BASE_URL}/forget-password/${email}`);
    },
    resetPassword(formValue) {
        return axios.post(`${BASE_URL}/reset-password`, formValue);
    },
    verifyEmail(formValue) {
        return axios.post(`${BASE_URL}/verify-email`, formValue);
    },
    resendToken() {
        return axios.get(`${BASE_URL}/resend-verification-code`);
    },
    logout() {
        return axios.get(`${BASE_URL}/logout`);
    },
    updateProfile(formValue) {
        return axios.post(`${BASE_URL}/update-profile`, formValue);
    },
    getCurrentUser() {
        return axios.get(`${BASE_URL}/current-user`);
    }
    
}