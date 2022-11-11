import { StringNumber } from "vue-advanced-chat";
import { MessageStoreRequest } from "@/types";

const baseUrl = window.SupportChat.path + "/api/v1";

export default {
    index(roomId: string) {
        return window.axios.get(`${baseUrl}/rooms/${roomId}/messages`);
    },

    store(roomId: string, message: MessageStoreRequest) {
        return window.axios.post(
            `${baseUrl}/rooms/${roomId}/messages`,
            message
        );
    },

    show(messageId: StringNumber) {
        return window.axios.get(`${baseUrl}/messages/${messageId}`);
    },

    update(messageId: StringNumber, message: MessageStoreRequest) {
        return window.axios.put(`${baseUrl}/messages/${messageId}`, message);
    },

    destroy(messageId: StringNumber) {
        return window.axios.delete(`${baseUrl}/messages/${messageId}`);
    },
};
