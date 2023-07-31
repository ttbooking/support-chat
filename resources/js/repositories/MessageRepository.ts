import { Repository } from "pinia-orm";
import Message from "@/models/Message";
import api from "@/api";
import type { Message as BaseMessage, MessageFile } from "vue-advanced-chat";
import type {
    FetchMessagesArgs,
    SendMessageArgs,
    TrySendMessageArgs,
    EditMessageArgs,
    DeleteMessageArgs,
    SendMessageReactionArgs,
    InitMessageArgs,
} from "@/types";

export default class MessageRepository extends Repository<Message> {
    use = Message;

    loaded = false;

    fetch = async ({ room }: FetchMessagesArgs) => {
        this.loaded = false;
        const response = await api.messages.index(room.roomId);
        const messages = this.save(response.data.data);
        this.loaded = true;
        return messages;
    };

    send = async ({ roomId, content, files, replyMessage }: SendMessageArgs) => {
        const { _id } = this.init({ content, replyMessage, files });
        return await this.trySend({ roomId, message: { _id, content, replyMessage, files } });
    };

    trySend = async ({ roomId, message }: TrySendMessageArgs) => {
        try {
            const response = await api.messages.store(roomId, {
                id: message._id,
                parent_id: message.replyMessage?._id,
                content: message.content,
                attachments:
                    message.files?.map((file) => ({
                        name: file.name + "." + file.extension,
                        type: file.type,
                        size: file.size,
                    })) ?? [],
            });
            return this.save(response.data.data);
        } catch (error) {
            this.where("_id", message._id).update({ failure: true });
            throw error;
        }
    };

    edit = async ({ messageId, newContent, replyMessage }: EditMessageArgs) => {
        //
    };

    delete = async ({ message }: DeleteMessageArgs) => {
        await api.messages.destroy(message._id);
        this.destroy(message._id);
    };

    sendReaction = async ({ messageId, reaction, remove }: SendMessageReactionArgs) => {
        if (remove) {
            await api.messageReactions.destroy(messageId, reaction.unicode);
            // TODO
        } else {
            await api.messageReactions.store(messageId, reaction.unicode);
            // TODO
        }
    };

    private init({ content, replyMessage, files }: InitMessageArgs) {
        return this.save({
            content,
            files: (files || []).map<MessageFile>((file) => ({
                name: file.name,
                type: file.type,
                extension: file.extension,
                url: file.localURL,
                localUrl: file.localURL,
                preview: "",
                size: file.size,
                audio: false,
                duration: 0,
                progress: 0,
                blob: file.blob,
            })),
            replyMessage,
        });
    }
}
