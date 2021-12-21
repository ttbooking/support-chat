export default {
    index() {
        return this.$http.get('/rooms')
    },

    store(room) {
        return this.$http.post('/rooms', room)
    },

    show(roomId) {
        return this.$http.get('/rooms/' + roomId)
    },

    update(roomId, room) {
        return this.$http.put('/rooms/' + roomId, room)
    },

    destroy(roomId) {
        return this.$http.delete('/rooms/' + roomId)
    },
}
