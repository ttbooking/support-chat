import { Repository } from "pinia-orm";
import Room from "@/models/Room";
import api from "@/api";
import type { Room as BaseRoom, RoomUser, Message } from "vue-advanced-chat";

export default class RoomRepository extends Repository<Room> {
    use = Room;

    loaded = false;

    fetch = async () => {
        this.loaded = false;
        const response = await api.rooms.index();
        const rooms = this.save(response.data.data);
        this.loaded = true;
        return rooms;
    };

    add = async () => {
        const { roomId } = this.new()!;
        const response = await api.rooms.store({ id: roomId });
        return this.save(response.data.data);
    };

    delete = async (roomId: string) => {
        await api.rooms.destroy(roomId);
        this.destroy(roomId);
    };

    setUsers = (roomId: string, users: RoomUser[]) => {
        this.query()
            .whereId(roomId)
            .save({ users } as Partial<BaseRoom>);
    };

    joinUser = (roomId: string, user: RoomUser) => {
        const existingUsers = this.find(roomId)?.users ?? [];
        this.query()
            .whereId(roomId)
            .save({ users: [...existingUsers, user] } as Partial<BaseRoom>);
    };

    leaveUser = (roomId: string, user: RoomUser) => {
        const existingUsers = this.find(roomId)?.users ?? [];
        const users = existingUsers.filter((current) => current._id !== user._id);
        this.query()
            .whereId(roomId)
            .save({ users } as Partial<BaseRoom>);
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
