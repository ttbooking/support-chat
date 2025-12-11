const baseUrl = window.chat.path + "/api";

export default {
    store(messageId: string, attachment: any, config: any) {
        return window.axios.post(`${baseUrl}/messages/${messageId}/attachments`, attachment, config);
    },

    show(messageId: string, filename: string) {
        return window.axios.get(`${baseUrl}/messages/${messageId}/attachments/${filename}`);
    },

    destroy(messageId: string, filename: string) {
        return window.axios.delete(`${baseUrl}/messages/${messageId}/attachments/${filename}`);
    },
};
