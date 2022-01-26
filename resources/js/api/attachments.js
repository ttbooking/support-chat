const baseUrl = window.SupportChat.path + '/api/v1'

export default {
    store(messageId, attachment, config) {
        return axios.post(`${baseUrl}/messages/${messageId}/attachments`, attachment, config)
    },

    show(messageId, filename) {
        return axios.get(`${baseUrl}/messages/${messageId}/attachments/${filename}`)
    },

    destroy(messageId, filename) {
        return axios.delete(`${baseUrl}/messages/${messageId}/attachments/${filename}`)
    },
}
