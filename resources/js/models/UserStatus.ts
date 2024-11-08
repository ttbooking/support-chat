import { Model } from "pinia-orm";
import { Attr, Str } from "pinia-orm/decorators";
import type { UserStatus as BaseUserStatus } from "vue-advanced-chat";

export default class UserStatus extends Model implements BaseUserStatus {
    static entity = "userStatus";

    static primaryKey = ["roomId", "userId"];

    @Attr() declare roomId: string;
    @Attr() declare userId: string;
    @Str("offline") declare state: "online" | "offline";
    @Attr() declare lastChanged: string;
}
