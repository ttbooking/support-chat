import type { Message } from "vue-advanced-chat";
import type { MessageStoreRequest } from "@/types";

const baseUrl = window.chat.path + "/api";

export default {
    index(roomId: string) {
        return window.axios.get<{ data: Message[] }>(`${baseUrl}/rooms/${roomId}/messages`);
    },

    store(roomId: string, message: MessageStoreRequest) {
        return window.axios.post<{ data: Message }>(`${baseUrl}/rooms/${roomId}/messages`, message);
    },

    show(messageId: string) {
        return window.axios.get<{ data: Message }>(`${baseUrl}/messages/${messageId}`);
    },

    update(messageId: string, message: MessageStoreRequest) {
        return window.axios.put<{ data: Message }>(`${baseUrl}/messages/${messageId}`, message);
    },

    destroy(messageId: string) {
        return window.axios.delete(`${baseUrl}/messages/${messageId}`);
    },
};
