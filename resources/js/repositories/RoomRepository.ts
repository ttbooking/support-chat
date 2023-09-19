import { AxiosRepository } from "@pinia-orm/axios";
import Room from "@/models/Room";
import type { Room as BaseRoom, RoomUser, Message } from "vue-advanced-chat";

export default class RoomRepository extends AxiosRepository<Room> {
    use = Room;

    loaded = false;

    fetch = async () => {
        this.loaded = false;
        const response = await this.api().get("/rooms");
        this.loaded = true;
        return response;
    };

    add = async () => {
        const { roomId } = this.new()!;
        return await this.api().post("/rooms", { id: roomId });
    };

    delete = async (roomId: string) => {
        return await this.api().delete(`/rooms/${roomId}`, { delete: 1 });
    };

    setUsers = (roomId: string, users: RoomUser[]) => {
        this.query()
            .whereId(roomId)
            .update({ users } as Partial<BaseRoom>);
    };

    joinUser = (roomId: string, user: RoomUser) => {
        const existingUsers = this.find(roomId)?.users ?? [];
        this.query()
            .whereId(roomId)
            .update({ users: [...existingUsers, user] } as Partial<BaseRoom>);
    };

    leaveUser = (roomId: string, user: RoomUser) => {
        const existingUsers = this.find(roomId)?.users ?? [];
        const users = existingUsers.filter((current) => current._id !== user._id);
        this.query()
            .whereId(roomId)
            .update({ users } as Partial<BaseRoom>);
    };

    messagePosted = (roomId: string, message: Message) => {
        //
    };

    joined() {
        return this.with("users", (query) => {
            query.whereId(window.SupportChat.userId);
        });
    }
}
