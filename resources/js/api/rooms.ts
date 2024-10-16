import type { Room } from "vue-advanced-chat";
import type { RoomStoreRequest } from "@/types";

const baseUrl = window.SupportChat.path + "/api";

export default {
    index() {
        return window.axios.get<{ data: Room[] }>(baseUrl + "/rooms");
    },

    store(room: RoomStoreRequest) {
        return window.axios.post<{ data: Room }>(baseUrl + "/rooms", room);
    },

    show(roomId: string) {
        return window.axios.get<{ data: Room }>(baseUrl + "/rooms/" + roomId);
    },

    update(roomId: string, room: RoomStoreRequest) {
        return window.axios.put<{ data: Room }>(baseUrl + "/rooms/" + roomId, room);
    },

    destroy(roomId: string) {
        return window.axios.delete(baseUrl + "/rooms/" + roomId);
    },
};
