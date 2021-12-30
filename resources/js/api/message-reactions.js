const baseUrl = window.SupportChat.path + '/api/v1'

export default {
    store(messageId, emoji) {
        return axios.post(`${baseUrl}/messages/${messageId}/reactions`, emoji, {
            headers: { 'Content-Type': 'text/plain' }
        })
    },

    destroy(messageId, emoji) {
        return axios.delete(`${baseUrl}/messages/${messageId}/reactions/${emoji}`)
    },
}
