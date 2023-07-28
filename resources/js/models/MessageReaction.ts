import { Model } from "pinia-orm";
import { Uid, Attr, Str, BelongsTo } from "pinia-orm/decorators";
import Message from "./Message";
import User from "./User";

export default class MessageReaction extends Model {
    static entity = "messageReactions";

    static primaryKey = "_id";

    @Uid() declare _id: string;
    @Attr() declare messageId: string;
    @Attr() declare userId: string;
    @Str("") declare emoji: string;

    @BelongsTo(() => Message, "messageId") declare message: Message;
    @BelongsTo(() => User, "userId") declare user: User;
}
