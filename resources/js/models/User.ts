import { Model } from "pinia-orm";
import { Attr, Str, BelongsToMany, HasMany } from "pinia-orm/decorators";
import Room from "./Room";
import UserStatus from "./UserStatus";
import Message from "./Message";
import type { RoomUser as BaseUser } from "vue-advanced-chat";

export default class User extends Model implements BaseUser {
    static entity = "users";

    static primaryKey = "_id";

    @Attr() declare _id: string;
    @Str("") declare username: string;
    @Str("") declare credential: string;
    @Str("") declare avatar: string;
    declare status: UserStatus;

    @BelongsToMany(() => Room, { as: "status", model: () => UserStatus }, "userId", "roomId") declare rooms: Room[];
    @HasMany(() => Message, "senderId") declare messages: Message[];
}
