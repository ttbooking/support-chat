<template>
    <vue-win-box ref="wbRef" :options="options" @resize="resizeHandler">
        <vue-advanced-chat
            v-if="$env.userId"
            :height="height"
            :current-user-id="$env.userId"
            :rooms.prop="rooms"
            :rooms-loaded="roomRepo.loaded"
            :room-info-enabled="false"
            :messages.prop="roomMessages"
            :messages-loaded="messageRepo.loaded"
            :room-actions.prop="menuActions"
            :menu-actions.prop="menuActions"
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
    </vue-win-box>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from "vue";
import { PusherPresenceChannel } from "laravel-echo/dist/channel";
import { VueWinBox } from "vue-winbox";
import { register, CustomAction, VueAdvancedChat } from "vue-advanced-chat";
import type { RoomUser, Message } from "vue-advanced-chat";
import type { Reaction, OpenFileArgs } from "@/types";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";
import WinBox from "winbox";

import icon from "../../images/favicon.svg";

register();

const wbRef = ref<InstanceType<typeof VueWinBox>>();
const options: WinBox.Params & { icon?: string } = {
    x: "center",
    y: "center",
    minwidth: 250,
    minheight: 400,
    icon,
    title: "Support Chat",
    class: "modern",
};
const height = ref<string>("600px");

const roomRepo = computed(() => useRepo(RoomRepository));
const messageRepo = computed(() => useRepo(MessageRepository));

const rooms = computed(() => roomRepo.value.all());
const joinedRooms = computed(() => roomRepo.value.joined().get());
const roomMessages = computed(() => messageRepo.value.all());

const menuActions = ref([
    {
        name: "deleteRoom",
        title: "Delete Room",
    },
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
        case "deleteRoom":
            roomRepo.value.delete(roomId);
    }
}

function resizeHandler(size: { width: number; height: number }) {
    height.value = size.height - 35 + "px";
}
</script>
