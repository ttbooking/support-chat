import { Model } from "pinia-orm";
import { Attr } from "pinia-orm/decorators";

export default class RoomUser extends Model {
    static entity = "roomUser";

    static primaryKey = ["roomId", "userId"];

    @Attr() declare roomId: string;
    @Attr() declare userId: string;
}
