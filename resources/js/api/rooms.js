const baseUrl = window.SupportChat.path + '/api/v1'

export default $http => ({
    index() {
        return $http.get(baseUrl + '/rooms')
    },

    store(room) {
        return $http.post(baseUrl + '/rooms', room)
    },

    show(roomId) {
        return $http.get(baseUrl + '/rooms/' + roomId)
    },

    update(roomId, room) {
        return $http.put(baseUrl + '/rooms/' + roomId, room)
    },

    destroy(roomId) {
        return $http.delete(baseUrl + '/rooms/' + roomId)
    },
})
