import { Repository } from "pinia-orm";
import Room from "@/models/Room";
import api from "@/api";

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
}
