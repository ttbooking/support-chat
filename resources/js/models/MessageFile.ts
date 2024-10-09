import { Model } from "pinia-orm";
import { Attr, Str, Num, Bool, BelongsTo } from "pinia-orm/decorators";
import Message from "./Message";
import type { MessageFile as BaseMessageFile } from "vue-advanced-chat";

export default class MessageFile extends Model implements BaseMessageFile {
    static entity = "messageFiles";

    static primaryKey = ["messageId", "name"];

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
