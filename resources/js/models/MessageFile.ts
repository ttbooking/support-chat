import { Model } from "pinia-orm";
import { Uid, Attr, Str, Num, Bool, BelongsTo } from "pinia-orm/decorators";
import { MessageFile as BaseMessageFile } from "vue-advanced-chat";
import Message from "./Message";

export default class MessageFile extends Model implements BaseMessageFile {
    static entity = "messageFiles";

    static primaryKey = "_id";

    @Uid() declare _id: string;
    @Str("") declare messageId: string;
    @Str("") declare name: string;
    @Str("") declare type: string;
    @Str("") declare extension: string;
    @Str("") declare url: string;
    @Str(null) declare localUrl?: string;
    @Str(null) declare preview?: string;
    @Num(null) declare size?: number;
    @Bool(null) declare audio?: boolean;
    @Num(null) declare duration?: number;
    @Num(null) declare progress?: number;
    @Attr() declare blob?: Blob;

    @BelongsTo(() => Message, "messageId") declare message: Message;
}
