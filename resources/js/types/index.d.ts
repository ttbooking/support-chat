import type { Message, MessageFile, Room as BaseRoom, RoomUser } from "vue-advanced-chat";

export interface RoomTag {
    name: string;
    type: string;
    link: string;
}

export interface Room extends BaseRoom {
    creator: RoomUser;
    tags: string[];
}

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

export interface Reaction {
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

export interface TypingMessageArgs {
    roomId: string;
    message: string;
}

export interface UserTypingArgs {
    userId: string;
    roomId: string;
}

export interface RoomStoreRequest {
    id: string;
    name?: string;
    users?: RoomUser[];
    tags?: string[];
}

export interface MessageStoreRequest {
    id: string;
    reply_to?: string;
    content: string;
    attachments?: Array<{
        name: string;
        type?: string;
        size: number;
    }>;
}
