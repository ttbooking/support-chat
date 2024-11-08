import { PusherPresenceChannel } from "laravel-echo/dist/channel";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";
import Room from "@/models/Room";
import type { Message, RoomUser } from "vue-advanced-chat";

export function useRoomChannel() {
    const roomRepo = useRepo(RoomRepository);
    const messageRepo = useRepo(MessageRepository);

    const join = (room: Room) =>
        (window.Echo.join(`support-chat.room.${room.roomId}`) as PusherPresenceChannel)
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
            .listen(".reaction.removed", messageRepo.reactionRemoved) as PusherPresenceChannel;

    const leave = (room: Room) => window.Echo.leaveChannel(`support-chat.room.${room.roomId}`);

    return { join, leave };
}
