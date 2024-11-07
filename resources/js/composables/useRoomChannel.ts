import { onBeforeMount, onBeforeUnmount } from "vue";
import { PusherPresenceChannel } from "laravel-echo/dist/channel";
import type { Message, RoomUser } from "vue-advanced-chat";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";
import type { TypingMessageArgs } from "@/types";

export function useRoomChannel(roomId: string) {
    const channel = <PusherPresenceChannel>window.Echo.join(`support-chat.room.${roomId}`);

    const typing = ({ roomId }: TypingMessageArgs) => {
        channel.whisper("typing", {
            userId: window.SupportChat.userId,
            roomId,
        });
    };

    const roomRepo = useRepo(RoomRepository);
    const messageRepo = useRepo(MessageRepository);

    onBeforeMount(() => {
        channel
            .here((users: RoomUser[]) => roomRepo.setUsers(roomId, users))
            .joining((user: RoomUser) => roomRepo.joinUser(roomId, user))
            .leaving((user: RoomUser) => roomRepo.leaveUser(roomId, user))
            .listenToAll((event: string, data: unknown) => console.log(`room.${roomId}`, event, data))
            .error((error: unknown) => console.error(error))
            //.listen(".room.added", roomRepo.added)
            .listen(".room.updated", roomRepo.updated)
            //.listen(".room.deleted", roomRepo.deleted)
            .listenForWhisper("typing", roomRepo.userTyping)
            .listen(".message.posted", (message: Message) => messageRepo.posted(roomId, message))
            .listen(".message.edited", messageRepo.edited)
            .listen(".message.deleted", messageRepo.deleted)
            .listen(".reaction.left", messageRepo.reactionLeft)
            .listen(".reaction.removed", messageRepo.reactionRemoved);
    });

    onBeforeUnmount(() => {
        window.Echo.leaveChannel(`support-chat.room.${roomId}`);
    });

    return channel;
}
