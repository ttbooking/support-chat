import { AxiosRepository } from "@pinia-orm/axios";
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
    Reaction,
} from "@/types";

export default class MessageRepository extends AxiosRepository<Message> {
    use = Message;

    loaded = false;

    fetch = async ({ room }: FetchMessagesArgs) => {
        this.loaded = false;
        const response = await this.api().get(window.SupportChat.path + `/rooms/${room.roomId}/messages`, {
            dataKey: "data",
        });
        this.loaded = true;
        return response;
    };

    send = async ({ roomId, content, files, replyMessage }: SendMessageArgs) => {
        const { _id } = this.init({ content, replyMessage, files });
        return await this.trySend({ roomId, message: { _id, content, replyMessage, files } });
    };

    trySend = async ({ roomId, message }: TrySendMessageArgs) => {
        try {
            return await this.api().post(
                window.SupportChat.path + `/rooms/${roomId}/messages`,
                {
                    id: message._id,
                    parent_id: message.replyMessage?._id,
                    content: message.content,
                    attachments:
                        message.files?.map((file) => ({
                            name: file.name + "." + file.extension,
                            type: file.type,
                            size: file.size,
                        })) ?? [],
                },
                { dataKey: "data" },
            );
        } catch (error) {
            this.where("_id", message._id).update({ failure: true });
            throw error;
        }
    };

    edit = async ({ messageId, newContent, replyMessage }: EditMessageArgs) => {
        //
    };

    delete = async ({ message }: DeleteMessageArgs) => {
        return await this.api().delete(window.SupportChat.path + `/messages/${message._id}`, {
            delete: 1,
            dataKey: "data",
        });
    };

    sendReaction = async ({ messageId, reaction, remove }: SendMessageReactionArgs) => {
        if (remove) {
            this.reactionRemoved({ messageId, userId: window.SupportChat.userId, emoji: reaction.unicode });
            await api.messageReactions.destroy(messageId, reaction.unicode);
        } else {
            this.reactionLeft({ messageId, userId: window.SupportChat.userId, emoji: reaction.unicode });
            await api.messageReactions.store(messageId, reaction.unicode);
        }
    };

    posted = (roomId: string, message: BaseMessage) => {
        return this.save({ ...message, roomId });
    };

    edited = (message: BaseMessage) => {
        return this.save(message);
    };

    deleted = (message: BaseMessage) => {
        //this.destroy(message._id);
        //this.query().whereId(message._id).update({ deleted: true });
        return this.save({ ...message, deleted: true });
    };

    reactionLeft = ({ messageId, userId, emoji }: Reaction) => {
        const reactions = this.find(messageId)!.reactions;
        reactions[emoji] = [...new Set(reactions[emoji]).add(userId)];
        return this.query().whereId(messageId).update({ reactions });
    };

    reactionRemoved = ({ messageId, userId, emoji }: Reaction) => {
        const reactions = this.find(messageId)!.reactions;
        const reactionUsers = new Set(reactions[emoji]);
        reactionUsers.delete(userId);
        reactions[emoji] = [...reactionUsers];
        return this.query().whereId(messageId).update({ reactions });
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
