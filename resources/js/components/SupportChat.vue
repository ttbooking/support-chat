<template>
    <vue-advanced-chat
        v-if="store.currentUserId"
        :current-user-id="store.currentUserId"
        :rooms.prop="store.rooms"
        :rooms-loaded="store.roomsLoaded"
        :room-info-enabled="false"
        :messages.prop="store.allMessages"
        :messages-loaded="true"
        :room-actions.prop="menuActions"
        :menu-actions.prop="menuActions"
        @fetch-messages="store.fetchMessages($event.detail[0])"
        @fetch-more-rooms="store.fetchRooms"
        @send-message="store.sendMessage($event.detail[0])"
        @edit-message="store.editMessage($event.detail[0])"
        @delete-message="store.deleteMessage($event.detail[0])"
        @open-file="openFile($event.detail[0])"
        @open-failed-message="store.trySendMessage($event.detail[0])"
        @add-room="store.addRoom"
        @room-action-handler="menuActionHandler($event.detail[0])"
        @menu-action-handler="menuActionHandler($event.detail[0])"
        @send-message-reaction="store.sendMessageReaction($event.detail[0])"
    />
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import {
    register,
    CustomAction,
    Message,
    StringNumber,
    VueAdvancedChat,
} from "vue-advanced-chat";
import { useSupportChatStore } from "@/stores";

register();

const props = defineProps<{ userId: StringNumber }>();

const store = useSupportChatStore();

const menuActions = ref([
    {
        name: "deleteRoom",
        title: "Delete Room",
    },
]);

onMounted(() => {
    store._setUserId(props.userId);
    store.fetchRooms();
});

function openFile({ file }: { message: Message; file: any }) {
    window.location = file.file.url;
}

function menuActionHandler({
    roomId,
    action,
}: {
    roomId: string;
    action: CustomAction;
}) {
    switch (action.name) {
        case "deleteRoom":
            store.deleteRoom(roomId);
    }
}
</script>
