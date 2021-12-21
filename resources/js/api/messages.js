const baseUrl = window.SupportChat.path + '/api/v1'

export default $http => ({
    index(roomId) {
        return $http.get(`${baseUrl}/rooms/${roomId}/messages`)
    },

    store(roomId, message) {
        return $http.post(`${baseUrl}/rooms/${roomId}/messages`, message)
    },

    show(messageId) {
        return $http.get(`${baseUrl}/messages/${messageId}`)
    },

    update(messageId, message) {
        return $http.put(`${baseUrl}/messages/${messageId}`, message)
    },

    destroy(messageId) {
        return $http.delete(`${baseUrl}/messages/${messageId}`)
    },
})
