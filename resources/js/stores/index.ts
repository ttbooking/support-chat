import { defineStore, acceptHMRUpdate } from "pinia";
import { ref, reactive, computed } from "vue";
import { throttle } from "lodash";
import api from "@/api";

import { PusherPresenceChannel } from "laravel-echo/dist/channel";
import {
    Message,
    Messages,
    Room,
    Rooms,
    RoomUser,
    RoomUsers,
    StringNumber,
} from "vue-advanced-chat";
import {
    AttachmentCallbackArgs,
    DeleteMessageArgs,
    EditMessageArgs,
    FetchMessagesArgs,
    InitMessageArgs,
    ReactionCallbackArgs,
    SendMessageArgs,
    SendMessageReactionArgs,
    TrySendMessageArgs,
    UploadAttachmentArgs,
} from "@/types";

export const useSupportChatStore = defineStore("support-chat", () => {
    /** State **/

    const currentUserId = ref<string>("0");

    const rooms = ref<Rooms>([]);

    const roomsLoaded = ref(false);

    const roomId = ref<string>("0");

    const nextMessageId = ref(1);

    const messages = reactive<Map<StringNumber, Message>>(new Map());

    /** Getters **/

    const allMessages = computed(() => Array.from(messages.values()));

    const getMessage = computed(() => (id: StringNumber) => {
        return messages.get(id);
    });

    const joinedRooms = computed(() => {
        return rooms.value.filter((room) =>
            room.users.some((user) => user._id === currentUserId.value)
        );
    });

    /** Mutations **/

    function _setUserId(_userId: StringNumber) {
        currentUserId.value = _userId.toString();
    }

    function _setRoomsLoadedState(_roomsLoaded: boolean) {
        roomsLoaded.value = _roomsLoaded;
    }

    function _setRooms(_rooms: Rooms) {
        rooms.value = _rooms;
    }

    function _addRoom(_room: Room) {
        rooms.value = [...rooms.value, _room];
    }

    function _deleteRoom(_roomId: string) {
        rooms.value = rooms.value.filter((room) => room.roomId !== _roomId);
    }

    function _setRoomId(_roomId: string) {
        roomId.value = _roomId;
    }

    function _roomSetUsers(_roomId: string, _users: RoomUsers) {
        const roomIndex = rooms.value.findIndex(
            (currentRoom) => currentRoom.roomId === _roomId
        );
        rooms.value[roomIndex].users = _users;
        rooms.value = [...rooms.value];
    }

    function _roomJoinUser(_roomId: string, _user: RoomUser) {
        const roomIndex = rooms.value.findIndex(
            (currentRoom) => currentRoom.roomId === _roomId
        );
        rooms.value[roomIndex].users = [...rooms.value[roomIndex].users, _user];
        rooms.value = [...rooms.value];
    }

    function _roomLeaveUser(_roomId: string, _user: RoomUser) {
        const roomIndex = rooms.value.findIndex(
            (currentRoom) => currentRoom.roomId === _roomId
        );
        const users = rooms.value[roomIndex].users.filter(
            (currentUser) => currentUser._id !== _user._id
        );
        rooms.value[roomIndex].users = [...users];
        rooms.value = [...rooms.value];
    }

    function _setMessages(_messages: Messages) {
        Object.assign(
            messages,
            new Map(
                _messages.map((message) => [
                    message.indexId ?? message._id,
                    message,
                ])
            )
        );
    }

    function _setNextMsgId(_nextMessageId = null) {
        const lastMessageId = Math.max(
            0,
            ...Array.from(messages.keys(), (k) => +k)
        );
        nextMessageId.value = _nextMessageId ?? lastMessageId + 1;
    }

    function _incrementNextMsgId() {
        nextMessageId.value++;
    }

    function _initMessage({
        _id,
        content,
        replyMessage,
        files,
    }: InitMessageArgs) {
        const message: Message = {
            _id,
            content,
            senderId: currentUserId.value,
            username: "",
            date: "",
            timestamp: "",
            system: false,
            saved: false,
            distributed: false,
            seen: false,
            deleted: false,
            failure: false,
            disableActions: false,
            disableReactions: false,
            files: [],
            reactions: {},
            replyMessage,
        };
        for (const file of files || []) {
            (message.files ??= []).push({
                name: file.name,
                size: file.size,
                type: file.extension,
                audio: false,
                duration: 0,
                url: file.localURL,
                preview: "",
                progress: 0,
            });
        }
        messages.set(message.indexId ?? message._id, message);
    }

    function _uploadProgress(
        _messageIndexId: StringNumber,
        _filename: string,
        _progress?: number
    ) {
        const fileIndex = messages
            .get(_messageIndexId)
            ?.files?.findIndex((file) => {
                return file.name + "." + file.type === _filename;
            });

        if (fileIndex !== undefined) {
            messages.get(_messageIndexId)!.files![fileIndex].progress =
                _progress ?? -1;
        }
    }

    function _leaveReaction(
        _messageId: StringNumber,
        _userId: StringNumber,
        _emoji: string
    ) {
        messages.get(_messageId)?.reactions![_emoji].push(_userId);
    }

    function _removeReaction(
        _messageId: StringNumber,
        _userId: StringNumber,
        _emoji: string
    ) {
        const reactionUsers =
            messages.get(_messageId)?.reactions![_emoji] ?? [];
        const userIndex = reactionUsers.indexOf(_userId);
        if (userIndex > -1) {
            reactionUsers.splice(userIndex, 1);
        }
        messages.get(_messageId)!.reactions![_emoji] = [
            ...new Set(reactionUsers),
        ];
    }

    /** Actions **/

    async function fetchRooms() {
        _setRoomsLoadedState(false);
        const response = await api.rooms.index();
        _setRooms(response.data.data);
        _setRoomsLoadedState(true);

        for (const room of joinedRooms.value) {
            window.roomChannel = <PusherPresenceChannel>window.Echo.join(
                `support-chat.room.${room.roomId}`
            )
                .here((users: RoomUsers) => {
                    _roomSetUsers(room.roomId.toString(), users);
                })
                .joining((user: RoomUser) => {
                    _roomJoinUser(room.roomId.toString(), user);
                })
                .leaving((user: RoomUser) => {
                    _roomLeaveUser(room.roomId.toString(), user);
                })
                .error((error: any) => {
                    console.error(error);
                })
                .listen(".message.posted", (message: Message) => {
                    room.roomId === roomId.value &&
                        messages.set(message.indexId ?? message._id, message);
                    _setNextMsgId();
                })
                .listen(".message.edited", (message: Message) => {
                    room.roomId === roomId.value &&
                        messages.set(message.indexId ?? message._id, message);
                })
                .listen(".message.deleted", (message: Message) => {
                    room.roomId === roomId.value &&
                        (messages.get(message.indexId ?? message._id)!.deleted =
                            true);
                })
                .listen(
                    ".reaction.left",
                    ({
                        messageIndexId,
                        userId,
                        emoji,
                    }: ReactionCallbackArgs) => {
                        _leaveReaction(messageIndexId, userId, emoji);
                    }
                )
                .listen(
                    ".reaction.removed",
                    ({
                        messageIndexId,
                        userId,
                        emoji,
                    }: ReactionCallbackArgs) => {
                        _removeReaction(messageIndexId, userId, emoji);
                    }
                )
                .listenForWhisper(
                    "attachment.uploading",
                    ({
                        messageIndexId,
                        filename,
                        progress,
                    }: AttachmentCallbackArgs) => {
                        if (messageIndexId > -1) {
                            _uploadProgress(messageIndexId, filename, progress);
                        }
                    }
                )
                .listen(
                    ".attachment.uploaded",
                    ({
                        messageIndexId,
                        filename,
                        progress,
                    }: AttachmentCallbackArgs) => {
                        if (messageIndexId > -1) {
                            _uploadProgress(messageIndexId, filename, progress);
                        }
                    }
                );
        }
    }

    async function addRoom(room = {}) {
        const response = await api.rooms.store(room);
        _addRoom(response.data.data);
    }

    async function deleteRoom(roomId: string) {
        await api.rooms.destroy(roomId);
        _deleteRoom(roomId);
    }

    async function fetchMessages({ room }: FetchMessagesArgs) {
        _setRoomId(room.roomId.toString());
        const response = await api.messages.index(room.roomId.toString());
        _setMessages(response.data.data);
        _setNextMsgId();
    }

    async function sendMessage({
        roomId,
        content,
        files,
        replyMessage,
    }: SendMessageArgs) {
        const _id = nextMessageId.value.toString();
        _incrementNextMsgId();

        const message: InitMessageArgs = { _id, content, replyMessage, files };
        _initMessage(message);

        await trySendMessage({ roomId, message });
    }

    async function trySendMessage({ roomId, message }: TrySendMessageArgs) {
        try {
            const response = await api.messages.store(roomId, {
                content: message.content,
                parent_id:
                    message.replyMessage?.indexId ?? message.replyMessage?._id,
                attachments:
                    message.files?.map((file) => ({
                        name: file.name + "." + file.extension,
                        type: file.type,
                        size: file.size,
                    })) ?? [],
            });
            const savedMessage = response.data.data;
            messages.set(message._id, { ...savedMessage, _id: message._id });

            for (const file of message.files || []) {
                await uploadAttachment({ message: savedMessage, file });
                _uploadProgress(message._id, file.name + "." + file.extension);
            }
        } catch (error) {
            messages.get(message._id)!.failure = true;
        }
    }

    async function uploadAttachment({ message, file }: UploadAttachmentArgs) {
        const formData = new FormData();
        formData.append(
            "attachment",
            file.blob,
            file.name + "." + file.extension
        );
        await api.attachments.store(message.indexId ?? message._id, formData, {
            onUploadProgress: throttle((e) => {
                const upload = {
                    messageIndexId: message.indexId ?? message._id,
                    filename: file.name + "." + file.extension,
                    progress: Math.round((e.loaded * 100) / e.total),
                };
                window.roomChannel.whisper("attachment.uploading", upload);
                _uploadProgress(
                    message.indexId ?? message._id,
                    upload.filename,
                    upload.progress
                );
            }, 100),
        });
    }

    async function editMessage({
        messageId,
        newContent,
        replyMessage,
    }: EditMessageArgs) {
        replyMessage ??= getMessage.value(messageId)?.replyMessage;
        const response = await api.messages.update(messageId, {
            content: newContent,
            parent_id: replyMessage?.indexId ?? replyMessage?._id,
        });
        const savedMessage: Message = response.data.data;
        messages.set(savedMessage.indexId ?? savedMessage._id, savedMessage);
    }

    async function deleteMessage({ message }: DeleteMessageArgs) {
        await api.messages.destroy(message.indexId ?? message._id);
        messages.delete(message.indexId ?? message._id);
    }

    async function sendMessageReaction({
        messageId,
        reaction,
        remove,
    }: SendMessageReactionArgs) {
        if (remove) {
            await api.messageReactions.destroy(messageId, reaction.unicode);
            _removeReaction(messageId, currentUserId.value, reaction.unicode);
        } else {
            await api.messageReactions.store(messageId, reaction.unicode);
            _leaveReaction(messageId, currentUserId.value, reaction.unicode);
        }
    }

    return {
        currentUserId,
        rooms,
        roomsLoaded,
        roomId,
        nextMessageId,
        messages,

        allMessages,
        getMessage,
        joinedRooms,

        _setUserId,
        _setRoomsLoadedState,
        _setRooms,
        _addRoom,
        _deleteRoom,
        _setRoomId,
        _roomSetUsers,
        _roomJoinUser,
        _roomLeaveUser,
        _setMessages,
        _setNextMsgId,
        _incrementNextMsgId,
        _initMessage,
        _uploadProgress,
        _leaveReaction,
        _removeReaction,

        fetchRooms,
        addRoom,
        deleteRoom,
        fetchMessages,
        sendMessage,
        trySendMessage,
        uploadAttachment,
        editMessage,
        deleteMessage,
        sendMessageReaction,
    };
});

if (import.meta.hot) {
    import.meta.hot.accept(
        acceptHMRUpdate(useSupportChatStore, import.meta.hot)
    );
}
