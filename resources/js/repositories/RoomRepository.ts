import { ref } from "vue";
import { AxiosRepository } from "@pinia-orm/axios";
import Room from "@/models/Room";
import { useDebounceFn } from "@vueuse/core";
import type { Room as BaseRoom, RoomUser } from "vue-advanced-chat";
import type { UserTypingArgs } from "@/types";

export default class RoomRepository extends AxiosRepository<Room> {
    use = Room;

    loaded = ref(false);

    fetch = async (roomId?: string) => {
        this.loaded.value = false;
        const url = window.SupportChat.path + "/api/rooms";
        const response = await this.api().get(roomId ? `${url}/${roomId}` : url, { dataKey: "data" });
        this.loaded.value = true;
        return response;
    };

    add = async () => {
        const { roomId } = this.new()!;
        return await this.api().post(window.SupportChat.path + "/api/rooms", { id: roomId }, { dataKey: "data" });
    };

    update = async (room: BaseRoom) => {
        return await this.api().put(window.SupportChat.path + `/api/rooms/${room.roomId}`, room, { dataKey: "data" });
    };

    delete = async (roomId: string) => {
        return await this.api().delete(window.SupportChat.path + `/api/rooms/${roomId}`, {
            delete: 1,
            dataKey: "data",
        });
    };

    setUsers = (roomId: string, users: RoomUser[]) => {
        console.log("here", roomId, users);
        this.query()
            .whereId(roomId)
            .update({ users } as Partial<BaseRoom>);
    };

    joinUser = (roomId: string, user: RoomUser) => {
        console.log("joining", roomId, user);
        const existingUsers = this.find(roomId)?.users ?? [];
        this.query()
            .whereId(roomId)
            .update({ users: [...existingUsers, user] } as Partial<BaseRoom>);
    };

    leaveUser = (roomId: string, user: RoomUser) => {
        console.log("leaving", roomId, user);
        const existingUsers = this.find(roomId)?.users ?? [];
        const users = existingUsers.filter((current) => current._id !== user._id);
        this.query()
            .whereId(roomId)
            .update({ users } as Partial<BaseRoom>);
    };

    added = (room: BaseRoom) => {
        return this.save(room);
    };

    updated = (room: BaseRoom) => {
        return this.save(room);
    };

    deleted = (room: BaseRoom) => {
        this.destroy(room.roomId);
    };

    userTyping = async ({ userId, roomId }: UserTypingArgs) => {
        const typingUsers = Array.from(new Set(this.find(roomId)?.typingUsers ?? []).add(userId));
        this.query().whereId(roomId).update({ typingUsers });
        await useDebounceFn(this.userNoMoreTyping, 5000)({ userId, roomId });
    };

    userNoMoreTyping = ({ userId, roomId }: UserTypingArgs) => {
        const typingUserSet = new Set(this.find(roomId)?.typingUsers ?? []);
        typingUserSet.delete(userId);
        const typingUsers = Array.from(typingUserSet);
        this.query().whereId(roomId).update({ typingUsers });
    };
}
