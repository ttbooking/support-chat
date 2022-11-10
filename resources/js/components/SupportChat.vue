<template>
    <vue-advanced-chat v-if="store.currentUserId"
        :current-user-id="store.currentUserId"
        :rooms.prop="store.rooms"
        :rooms-loaded="store.roomsLoaded"
        :messages.prop="store.messages"
        :messages-loaded="true"
        :room-actions.prop="menuActions"
        :menu-actions.prop="menuActions"
        @fetch-messages="store.fetchMessages($event.detail[0])"
        @fetch-more-rooms="store.fetchRooms($event.detail[0])"
        @send-message="store.sendMessage($event.detail[0])"
        @edit-message="store.editMessage($event.detail[0])"
        @delete-message="store.deleteMessage($event.detail[0])"
        @open-file="openFile($event.detail[0])"
        @open-failed-message="store.trySendMessage($event.detail[0])"
        @add-room="store.addRoom($event.detail[0])"
        @room-action-handler="menuActionHandler($event.detail[0])"
        @menu-action-handler="menuActionHandler($event.detail[0])"
        @send-message-reaction="store.sendMessageReaction($event.detail[0])"
    />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { register } from 'vue-advanced-chat'
import { useSupportChatStore } from '@/stores'

register()

const props = defineProps(['userId'])

const store = useSupportChatStore()

const menuActions = ref([{
    name: 'deleteRoom',
    title: 'Delete Room',
}])

onMounted(() => {
    store._setUserId(props.userId)
    store.fetchRooms()
})

function openFile({ message, file }) {
    window.location = file.file.url
}

function menuActionHandler({ roomId, action }) {
    switch (action.name) {
        case 'deleteRoom':
            this.store.deleteRoom(roomId)
    }
}
</script>
