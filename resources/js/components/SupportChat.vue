<template>
    <vue-advanced-chat
        v-if="$env.userId"
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
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from "vue";
import { register, CustomAction, VueAdvancedChat } from "vue-advanced-chat";
import type { OpenFileArgs } from "@/types";

import { useRepo } from "pinia-orm";
import RoomRepository from "@/repositories/RoomRepository";
import MessageRepository from "@/repositories/MessageRepository";

register();

const roomRepo = computed(() => useRepo(RoomRepository));
const messageRepo = computed(() => useRepo(MessageRepository));

const rooms = computed(() => roomRepo.value.all());
const roomMessages = computed(() => messageRepo.value.all());

const menuActions = ref([
    {
        name: "deleteRoom",
        title: "Delete Room",
    },
]);

onMounted(roomRepo.value.fetch);

function openFile(args: OpenFileArgs) {
    window.location.assign(args.file.file.url);
}

function menuActionHandler({ roomId, action }: { roomId: string; action: CustomAction }) {
    switch (action.name) {
        case "deleteRoom":
            roomRepo.value.delete(roomId);
    }
}
</script>
