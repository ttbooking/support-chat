const baseUrl = window.SupportChat.path + '/api/v1'

export default {
    index(roomId) {
        return axios.get(`${baseUrl}/rooms/${roomId}/messages`)
    },

    store(roomId, message) {
        return axios.post(`${baseUrl}/rooms/${roomId}/messages`, message)
    },

    show(messageId) {
        return axios.get(`${baseUrl}/messages/${messageId}`)
    },

    update(messageId, message) {
        return axios.put(`${baseUrl}/messages/${messageId}`, message)
    },

    destroy(messageId) {
        return axios.delete(`${baseUrl}/messages/${messageId}`)
    },
}
