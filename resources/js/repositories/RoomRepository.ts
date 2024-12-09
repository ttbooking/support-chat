import { ref } from "vue";
import { AxiosRepository } from "@pinia-orm/axios";
import Room from "@/models/Room";
//import { useDebounceFn } from "@vueuse/core";
import type { Room as BaseRoom, RoomUser } from "vue-advanced-chat";
//import type { UserTypingArgs } from "@/types";

export default class RoomRepository extends AxiosRepository<Room> {
    use = Room;

    loaded = ref(false);

    fetch = async (roomId?: string) => {
        this.loaded.value = false;
        const response = await this.api().get(roomId ? `/rooms/${roomId}` : "/rooms");
        this.loaded.value = true;
        return response;
    };

    add = async () => {
        const { roomId } = this.new()!;
        return await this.api().post("/rooms", { id: roomId });
    };

    update = async (room: BaseRoom) => {
        return await this.api().put(`/rooms/${room.roomId}`, room);
    };

    delete = async (roomId: string) => {
        return await this.api().delete(`/rooms/${roomId}`, { delete: 1 });
    };

    setUsers = (roomId: string, users: RoomUser[]) => {
        console.log("here", roomId, users);
        const room = this.find(roomId)!;
        room.users = users.map((user) => ({
            ...user,
            status: {
                state: "online",
                lastChanged: new Date().toLocaleString(),
            },
        }));
        this.save(room);
    };

    joinUser = (roomId: string, user: RoomUser) => {
        console.log("joining", roomId, user);
        const room = this.find(roomId)!;
        for (const i in room.users) {
            if (room.users[i]._id === user._id) {
                room.users[i] = {
                    ...room.users[i],
                    status: {
                        state: "online",
                        lastChanged: new Date().toLocaleString(),
                    },
                };
            }
        }
        this.save(room);
    };

    leaveUser = (roomId: string, user: RoomUser) => {
        console.log("leaving", roomId, user);
        const room = this.find(roomId)!;
        for (const i in room.users) {
            if (room.users[i]._id === user._id) {
                room.users[i] = {
                    ...room.users[i],
                    status: {
                        state: "offline",
                        lastChanged: new Date().toLocaleString(),
                    },
                };
            }
        }
        this.save(room);
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

    /*userTyping = async ({ userId, roomId }: UserTypingArgs) => {
        const typingUsers = Array.from(new Set(this.find(roomId)?.typingUsers ?? []).add(userId));
        this.query().whereId(roomId).update({ typingUsers });
        await useDebounceFn(this.userNoMoreTyping, 5000)({ userId, roomId });
    };

    userNoMoreTyping = ({ userId, roomId }: UserTypingArgs) => {
        const typingUserSet = new Set(this.find(roomId)?.typingUsers ?? []);
        typingUserSet.delete(userId);
        const typingUsers = Array.from(typingUserSet);
        this.query().whereId(roomId).update({ typingUsers });
    };*/
}
