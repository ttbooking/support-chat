<template>
    <vue-advanced-chat
        v-if="$env.userId"
        :height="computedHeight"
        :current-user-id="$env.userId"
        :rooms.prop="rooms"
        :rooms-loaded="roomRepo.loaded"
        :room-id="roomId"
        :single-room="!!roomId"
        :text-messages.prop="textMessages"
        :room-info-enabled="false"
        :messages.prop="roomMessages"
        :messages-loaded="messageRepo.loaded"
        :room-actions.prop="menuActions"
        :menu-actions.prop="menuActions"
        :message-actions.prop="messageActions"
        @fetch-messages="messageRepo.fetch($event.detail[0])"
        @fetch-more-rooms="roomRepo.fetch"
        @send-message="messageRepo.send($event.detail[0])"
        @edit-message="messageRepo.edit($event.detail[0])"
        @delete-message="messageRepo.delete($event.detail[0])"
        @open-file="openFile($event.detail[0])"
        @open-failed-message="messageRepo.trySend($event.detail[0])"
        @add-room="roomRepo.add"
        @room-action-handler="menuActionHandler($event.detail[0])"
        @menu-action-handler="menuActionHandler($event.detail[0])"
        @send-message-reaction="messageRepo.sendReaction($event.detail[0])"
    />
    <Teleport to="body">
        <RoomOptionsDialog
            v-if="room"
            v-show="roomOptionsDialogOpened"
            v-model="room"
            v-model:show="roomOptionsDialogOpened"
        />
    </Teleport>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { PusherPresenceChannel } from "laravel-echo/dist/channel";
import { register, CustomAction, VueAdvancedChat } from "vue-advanced-chat";
import type { RoomUser, Message } from "vue-advanced-chat";
import type { Room as BaseRoom, Reaction, OpenFileArgs } from "@/types";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";

import RoomOptionsDialog from "@/components/RoomOptionsDialog.vue";

register();

const props = defineProps<{ roomId?: string; height: number }>();

const computedHeight = computed(() => props.height - 35 + "px");

const { t, tm, rt } = useI18n();

const textMessages = computed(() =>
    Object.fromEntries(Object.entries(tm("advanced_chat")).map(([key, msg]) => [key.toUpperCase(), rt(msg)])),
);

const roomRepo = computed(() => useRepo(RoomRepository));
const messageRepo = computed(() => useRepo(MessageRepository));

const rooms = computed(() => roomRepo.value.with("users").get());
const joinedRooms = computed(() => roomRepo.value.joined().get());
const roomMessages = computed(() => messageRepo.value.currentRoom().get());

const roomOptionsDialogOpened = ref<boolean>(false);

const currentRoomId = ref<string | null>(null);
const room = computed<BaseRoom>({
    get(oldRoom) {
        if (currentRoomId.value === oldRoom?.roomId) return oldRoom;
        return roomRepo.value
            .with("creator")
            .with("users")
            .with("tags")
            .find(currentRoomId.value!)
            ?.$refresh()
            .$toJson() as BaseRoom;
    },
    set(room) {
        roomRepo.value.update(room);
    },
});

const menuActions = ref([
    { name: "configureRoom", title: t("configure_room") },
    { name: "deleteRoom", title: t("delete_room") },
]);

const messageActions = ref([
    { name: "replyMessage", title: t("reply_message") },
    { name: "editMessage", title: t("edit_message"), onlyMe: true },
    { name: "deleteMessage", title: t("delete_message"), onlyMe: true },
    { name: "selectMessages", title: t("select_messages") },
]);

onMounted(async () => {
    await roomRepo.value.fetch();

    for (const room of joinedRooms.value) {
        window.roomChannel = <PusherPresenceChannel>window.Echo.join(`support-chat.room.${room.roomId}`);
        window.roomChannel
            .here((users: RoomUser[]) => roomRepo.value.setUsers(room.roomId, users))
            .joining((user: RoomUser) => roomRepo.value.joinUser(room.roomId, user))
            .leaving((user: RoomUser) => roomRepo.value.leaveUser(room.roomId, user))
            .listenToAll((event: string, data: unknown) => console.log(event, data))
            .error((error: unknown) => console.error(error))
            .listen(".message.posted", (message: Message) => messageRepo.value.posted(room.roomId, message))
            .listen(".message.edited", (message: Message) => messageRepo.value.edited(message))
            .listen(".message.deleted", (message: Message) => messageRepo.value.deleted(message))
            .listen(".reaction.left", (reaction: Reaction) => messageRepo.value.reactionLeft(reaction))
            .listen(".reaction.removed", (reaction: Reaction) => messageRepo.value.reactionRemoved(reaction));
    }
});

function openFile(args: OpenFileArgs) {
    window.location.assign(args.file.file.url);
}

function menuActionHandler({ roomId, action }: { roomId: string; action: CustomAction }) {
    switch (action.name) {
        case "configureRoom":
            currentRoomId.value = roomId;
            roomOptionsDialogOpened.value = true;
            break;
        case "deleteRoom":
            roomRepo.value.delete(roomId);
    }
}
</script>
