import { onBeforeMount, onBeforeUnmount } from "vue";
import { PusherPrivateChannel } from "laravel-echo/dist/channel";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";

export function useUserChannel(userId: string) {
    const channel = <PusherPrivateChannel>window.Echo.private(`support-chat.user.${userId}`);

    const roomRepo = useRepo(RoomRepository);

    onBeforeMount(() => {
        channel
            .listenToAll((event: string, data: unknown) => console.log(`user.${userId}`, event, data))
            .error((error: unknown) => console.error(error))
            .listen(".room.added", roomRepo.added)
            .listen(".room.updated", roomRepo.updated)
            .listen(".room.deleted", roomRepo.deleted);
    });

    onBeforeUnmount(() => {
        window.Echo.leaveChannel(`support-chat.user.${userId}`);
    });

    return channel;
}
