const baseUrl = window.SupportChat.path + '/api/v1'

export default {
    index() {
        return axios.get(baseUrl + '/rooms')
    },

    store(room) {
        return axios.post(baseUrl + '/rooms', room)
    },

    show(roomId) {
        return axios.get(baseUrl + '/rooms/' + roomId)
    },

    update(roomId, room) {
        return axios.put(baseUrl + '/rooms/' + roomId, room)
    },

    destroy(roomId) {
        return axios.delete(baseUrl + '/rooms/' + roomId)
    },
}
