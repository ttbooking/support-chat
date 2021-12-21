export default {
    index(roomId) {
        return this.$http.get(`/rooms/${roomId}/messages`)
    },

    store(roomId, message) {
        return this.$http.post(`/rooms/${roomId}/messages`, message)
    },

    show(messageId) {
        return this.$http.get(`/messages/${messageId}`)
    },

    update(messageId, message) {
        return this.$http.put(`/messages/${messageId}`, message)
    },

    destroy(messageId) {
        return this.$http.delete(`/messages/${messageId}`)
    },
}
