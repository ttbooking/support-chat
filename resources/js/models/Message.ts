import { Model } from "pinia-orm";
import { Uid, Attr, Str, Bool, BelongsTo, HasMany } from "pinia-orm/decorators";
import { StringNumber, Message as BaseMessage, MessageReactions } from "vue-advanced-chat";
import Room from "./Room";
import User from "./User";
import MessageFile from "./MessageFile";

export default class Message extends Model implements BaseMessage {
    static entity = "messages";

    static primaryKey = "_id";

    @Uid() declare _id: string;
    @Attr() declare roomId: string;
    @Str(window.SupportChat.userId) declare senderId: string;
    @Str(null) declare replyMessageId?: string;
    @Attr(null) declare indexId?: StringNumber;
    @Str(null) declare content?: string;
    @Str(null) declare username?: string;
    @Str(null) declare avatar?: string;
    @Str(null) declare date?: string;
    @Str(null) declare timestamp?: string;
    @Bool(null) declare system?: boolean;
    @Bool(null) declare saved?: boolean;
    @Bool(null) declare distributed?: boolean;
    @Bool(null) declare seen?: boolean;
    @Bool(null) declare deleted?: boolean;
    @Bool(null) declare failure?: boolean;
    @Bool(null) declare disableActions?: boolean;
    @Bool(null) declare disableReactions?: boolean;
    @Attr({}) declare reactions: MessageReactions;

    @BelongsTo(() => Room, "roomId") declare room: Room;
    @BelongsTo(() => User, "senderId") declare sender: User;
    @HasMany(() => MessageFile, "messageId") declare files?: MessageFile[];
    @BelongsTo(() => Message, "replyMessageId") declare replyMessage?: Message;
    @HasMany(() => Message, "replyMessageId") declare replies: Message[];

    get id() {
        return this.indexId ?? this._id;
    }
}
