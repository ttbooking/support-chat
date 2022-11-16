import { StringNumber } from "vue-advanced-chat";

const baseUrl = window.SupportChat.path + "/api/v1";

export default {
    store(messageId: StringNumber, attachment: any, config: any) {
        return window.axios.post(`${baseUrl}/messages/${messageId}/attachments`, attachment, config);
    },

    show(messageId: StringNumber, filename: string) {
        return window.axios.get(`${baseUrl}/messages/${messageId}/attachments/${filename}`);
    },

    destroy(messageId: StringNumber, filename: string) {
        return window.axios.delete(`${baseUrl}/messages/${messageId}/attachments/${filename}`);
    },
};
