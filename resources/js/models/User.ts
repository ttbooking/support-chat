import { Model } from "pinia-orm";
import { Attr, Str, BelongsToMany, HasMany } from "pinia-orm/decorators";
import { UserStatus, RoomUser as BaseUser } from "vue-advanced-chat";
import Room from "./Room";
import RoomUser from "./RoomUser";
import Message from "./Message";

export default class User extends Model implements BaseUser {
    static entity = "users";

    static primaryKey = "_id";

    @Attr() declare _id: string;
    @Str("") declare username: string;
    @Str("") declare email: string;
    @Str("") declare avatar: string;
    @Attr() declare status: UserStatus;

    @BelongsToMany(() => Room, () => RoomUser, "userId", "roomId") declare rooms: Room[];
    @HasMany(() => Message, "senderId") declare messages: Message[];
}
