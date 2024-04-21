const BASE_URL = `chat`;
export default {
    newMessage(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    getRooms() {
        return axios.get(`${BASE_URL}/rooms`);
    },
    getMessages(roomId) {
        return axios.get(`${BASE_URL}/messages/${roomId}`);
    },
}