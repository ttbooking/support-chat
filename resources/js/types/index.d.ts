import { Message, MessageFile, Room, StringNumber } from "vue-advanced-chat";

export interface File {
    blob: Blob;
    localURL: string;
    name: string;
    size: number;
    type: string;
    extension: string;
}

export interface InitMessageArgs {
    _id?: string;
    content: string;
    replyMessage?: Message;
    files: File[];
}

export interface SendMessageArgs {
    roomId: string;
    content: string;
    files: File[];
    replyMessage?: Message;
    usersTag: string;
}

export interface EditMessageArgs {
    roomId: string;
    messageId: StringNumber;
    newContent: string;
    files: File[];
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
    message: InitMessageArgs;
}

export interface SendMessageReactionArgs {
    roomId: string;
    messageId: StringNumber;
    reaction: { unicode: string };
    remove: boolean;
}

export interface UploadAttachmentArgs {
    message: Message;
    file: File;
}

export interface ReactionCallbackArgs {
    messageIndexId: StringNumber;
    userId: string;
    emoji: string;
}

export interface AttachmentCallbackArgs {
    messageIndexId: StringNumber;
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
    name?: string;
}

export interface MessageStoreRequest {
    content: string;
    parent_id?: StringNumber;
    attachments?: Array<{
        name: string;
        type?: string;
        size: number;
    }>;
}
