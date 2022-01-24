const baseUrl = window.SupportChat.path + '/api/v1'

export default {
    store(messageId, attachment, config) {
        return axios.post(`${baseUrl}/messages/${messageId}/attachments`, attachment, config)
    },

    show(attachmentId) {
        return axios.get(`${baseUrl}/attachments/${attachmentId}`)
    },

    destroy(attachmentId) {
        return axios.delete(`${baseUrl}/attachments/${attachmentId}`)
    },
}
