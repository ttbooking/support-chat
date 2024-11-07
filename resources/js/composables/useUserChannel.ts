import { onBeforeMount, onBeforeUnmount } from "vue";
import { PusherPrivateChannel, PusherPresenceChannel } from "laravel-echo/dist/channel";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";
import type { Room as BaseRoom, TypingMessageArgs } from "@/types";
import type { Message, RoomUser } from "vue-advanced-chat";

export function useUserChannel(userId: string) {
    const roomRepo = useRepo(RoomRepository);
    const messageRepo = useRepo(MessageRepository);

    const roomChannelMap: Map<string, PusherPresenceChannel> = new Map();

    const typing = ({ roomId }: TypingMessageArgs) => {
        roomChannelMap.get(roomId)?.whisper("typing", userId);
    };

    const userInvited = (room: BaseRoom, save = true) => {
        if (save) roomRepo.save(room);

        const channel = (window.Echo.join(`support-chat.room.${room.roomId}`) as PusherPresenceChannel)
            .here((users: RoomUser[]) => roomRepo.setUsers(room.roomId, users))
            .joining((user: RoomUser) => roomRepo.joinUser(room.roomId, user))
            .leaving((user: RoomUser) => roomRepo.leaveUser(room.roomId, user))
            .listenToAll((event: string, data: unknown) => console.log(event, data))
            .error((error: unknown) => console.error(error))
            //.listen(".room.added", roomRepo.added)
            .listen(".room.updated", roomRepo.updated)
            //.listen(".room.deleted", roomRepo.deleted)
            .listenForWhisper("typing", (userId: string) => roomRepo.userTyping({ userId, roomId: room.roomId }))
            .listen(".message.posted", (message: Message) => messageRepo.posted(room.roomId, message))
            .listen(".message.edited", messageRepo.edited)
            .listen(".message.deleted", messageRepo.deleted)
            .listen(".reaction.left", messageRepo.reactionLeft)
            .listen(".reaction.removed", messageRepo.reactionRemoved);

        roomChannelMap.set(room.roomId, channel as PusherPresenceChannel);
    };

    const userKicked = (room: BaseRoom, save = true) => {
        window.Echo.leaveChannel(`support-chat.room.${room.roomId}`);

        if (save) roomRepo.destroy(room.roomId);
    };

    onBeforeMount(() => {
        for (const room of roomRepo.all()) userInvited(room, false);

        (window.Echo.private(`support-chat.user.${userId}`) as PusherPrivateChannel)
            .listenToAll((event: string, data: unknown) => console.log(`user.${userId}`, event, data))
            .error((error: unknown) => console.error(error))
            .listen(".user.invited", userInvited)
            .listen(".user.kicked", userKicked);
    });

    onBeforeUnmount(() => {
        window.Echo.leaveChannel(`support-chat.user.${userId}`);

        for (const room of roomRepo.all()) userKicked(room, false);
    });

    return { typing };
}
