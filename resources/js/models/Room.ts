import { Model } from "pinia-orm";
import { Uid, Attr, Num, Str, BelongsToMany, HasMany, HasManyBy } from "pinia-orm/decorators";
import User from "./User";
import RoomUser from "./RoomUser";
import Tag from "./Tag";
import RoomTag from "./RoomTag";
import Message from "./Message";
import type { StringNumber } from "vue-advanced-chat";
import type { Room as BaseRoom } from "@/types";

export default class Room extends Model implements BaseRoom {
    static entity = "rooms";

    static primaryKey = "roomId";

    @Uid(7) declare roomId: string;
    @Str("") declare roomName: string;
    @Str("") declare avatar: string;
    @Num(null) declare unreadCount?: number;
    @Attr(null) declare index?: StringNumber | Date;
    @Attr(null) declare typingUsers?: string[];

    @BelongsToMany(() => User, () => RoomUser, "roomId", "userId") declare users: User[];
    @BelongsToMany(() => Tag, () => RoomTag, "roomId", "tagName") declare tags: Tag[];
    @HasManyBy(() => User, "typingUsers") declare usersTyping: User[];
    @HasMany(() => Message, "roomId") declare messages: Message[];
}
