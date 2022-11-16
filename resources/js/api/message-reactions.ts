import { StringNumber } from "vue-advanced-chat";

const baseUrl = window.SupportChat.path + "/api/v1";

export default {
    store(messageId: StringNumber, emoji: string) {
        return window.axios.post(`${baseUrl}/messages/${messageId}/reactions`, emoji, {
            headers: { "Content-Type": "text/plain" },
        });
    },

    destroy(messageId: StringNumber, emoji: string) {
        return window.axios.delete(`${baseUrl}/messages/${messageId}/reactions/${emoji}`);
    },
};
