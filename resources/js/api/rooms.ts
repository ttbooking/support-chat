import { RoomStoreRequest } from "@/types";

const baseUrl = window.SupportChat.path + "/api/v1";

export default {
    index() {
        return window.axios.get(baseUrl + "/rooms");
    },

    store(room: RoomStoreRequest) {
        return window.axios.post(baseUrl + "/rooms", room);
    },

    show(roomId: string) {
        return window.axios.get(baseUrl + "/rooms/" + roomId);
    },

    update(roomId: string, room: RoomStoreRequest) {
        return window.axios.put(baseUrl + "/rooms/" + roomId, room);
    },

    destroy(roomId: string) {
        return window.axios.delete(baseUrl + "/rooms/" + roomId);
    },
};
