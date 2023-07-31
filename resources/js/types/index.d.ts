import { Message, MessageFile, Room } from "vue-advanced-chat";

export interface File {
    blob: Blob;
    localURL: string;
    name: string;
    size: number;
    type: string;
    extension: string;
}

export interface InitMessageArgs {
    content: string;
    replyMessage?: Message;
    files?: File[];
}

export interface SendMessageArgs {
    roomId: string;
    content: string;
    files?: File[];
    replyMessage?: Message;
    usersTag: string;
}

export interface EditMessageArgs {
    roomId: string;
    messageId: string;
    newContent: string;
    files?: File[];
    replyMessage?: Message;
    usersTag: string;
}

export interface FetchMessagesArgs {
    room: Room;
    options?: { reset: boolean };
}

export interface DeleteMessageArgs {
    roomId: string;
    message: Message;
}

export interface TrySendMessageArgs {
    roomId: string;
    message: { _id: string } & InitMessageArgs;
}

export interface SendMessageReactionArgs {
    roomId: string;
    messageId: string;
    reaction: { unicode: string };
    remove: boolean;
}

export interface UploadAttachmentArgs {
    message: Message;
    file: File;
}

export interface ReactionCallbackArgs {
    messageId: string;
    userId: string;
    emoji: string;
}

export interface AttachmentCallbackArgs {
    messageId: string;
    filename: string;
    progress?: number;
}

export interface OpenFileArgs {
    message: Message;
    file: {
        action: "preview" | "download";
        file: MessageFile;
    };
}

export interface RoomStoreRequest {
    id: string;
    name?: string;
}

export interface MessageStoreRequest {
    id: string;
    parent_id?: string;
    content: string;
    attachments?: Array<{
        name: string;
        type?: string;
        size: number;
    }>;
}
