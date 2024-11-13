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
import { ref, computed, watchEffect, onMounted, onBeforeUnmount } from "vue";
import { useI18n } from "vue-i18n";
import { register, CustomAction, VueAdvancedChat } from "vue-advanced-chat";
import type { Room as BaseRoom, OpenFileArgs } from "@/types";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";
import Room from "@/models/Room";

import RoomOptionsDialog from "@/components/RoomOptionsDialog.vue";

register();

const model = defineModel<string>();

const props = defineProps<{ roomId?: string; height: number }>();

const computedHeight = computed(() => props.height - 35 + "px");

const { t, tm, rt } = useI18n();

const textMessages = computed(() =>
    Object.fromEntries(Object.entries(tm("advanced_chat")).map(([key, msg]) => [key.toUpperCase(), rt(msg)])),
);

const roomRepo = useRepo(RoomRepository);
const messageRepo = useRepo(MessageRepository);

const rooms = computed<Room[]>(() =>
    props.roomId ? roomRepo.whereId(props.roomId).with("users").get() : roomRepo.with("users").get(),
);
const roomMessages = computed(() => messageRepo.currentRoom().get());

watchEffect(() => {
    model.value = messageRepo.room.value?.roomName;
});

const roomOptionsDialogOpened = ref<boolean>(false);

const currentRoomId = ref<string | null>(null);
const room = computed<BaseRoom>({
    get(oldRoom) {
        if (currentRoomId.value === oldRoom?.roomId) return oldRoom;
        return roomRepo
            .with("creator")
            .with("users")
            .with("tags")
            .find(currentRoomId.value!)
            ?.$refresh()
            .$toJson() as BaseRoom;
    },
    set(room) {
        roomRepo.update(room);
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
    await roomRepo.fetch(props.roomId);

    window.Echo.private(`support-chat.user.${window.SupportChat.userId}`)
        .listenToAll((event: string, data: unknown) => console.log(`user.${window.SupportChat.userId}`, event, data))
        .error((error: unknown) => console.error(error))
        .listen(".user.invited", (room: BaseRoom) => roomRepo.save(room))
        .listen(".user.kicked", (room: BaseRoom) => roomRepo.destroy(room.roomId));
});

onBeforeUnmount(() => {
    window.Echo.leaveChannel(`support-chat.user.${window.SupportChat.userId}`);

    roomRepo.destroy(rooms.value.map((room) => room.roomId));
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
            roomRepo.delete(roomId);
    }
}
</script>
