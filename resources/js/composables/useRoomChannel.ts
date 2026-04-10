import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";
import type { Message, RoomUser } from "vue-advanced-chat";
import type { Room as BaseRoom } from "@/types";

export function useRoomChannel() {
    const roomRepo = useRepo(RoomRepository);
    const messageRepo = useRepo(MessageRepository);

    const join = (room: BaseRoom) =>
        window.Echo.join(`advanced-chat.room.${room.roomId}`)
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

    const leave = (room: BaseRoom) => window.Echo.leaveChannel(`advanced-chat.room.${room.roomId}`);

    return { join, leave };
}
