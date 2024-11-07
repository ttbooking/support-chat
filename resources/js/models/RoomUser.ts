import { Model } from "pinia-orm";
import { Attr } from "pinia-orm/decorators";
import type { UserStatus } from "vue-advanced-chat";

export default class RoomUser extends Model implements UserStatus {
    static entity = "roomUser";

    static primaryKey = ["roomId", "userId"];

    @Attr() declare roomId: string;
    @Attr() declare userId: string;
    @Attr() declare state: "online" | "offline";
    @Attr() declare lastChanged: string;
}
