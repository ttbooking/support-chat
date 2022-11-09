<template>
    <vue-advanced-chat v-if="store.currentUserId"
        :current-user-id="store.currentUserId"
        :rooms="store.rooms"
        :rooms-loaded="store.roomsLoaded"
        :messages="store.messages"
        :messages-loaded="true"
        :room-actions="menuActions"
        :menu-actions="menuActions"
        @fetch-messages="store.fetchMessages"
        @fetch-more-rooms="store.fetchRooms"
        @send-message="store.sendMessage"
        @edit-message="store.editMessage"
        @delete-message="store.deleteMessage"
        @open-file="openFile"
        @open-failed-message="store.trySendMessage"
        @add-room="store.addRoom"
        @room-action-handler="menuActionHandler"
        @menu-action-handler="menuActionHandler"
        @send-message-reaction="store.sendMessageReaction"
    />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { register } from 'vue-advanced-chat'
import { useSupportChatStore } from '@/stores'

register()

defineProps(['userId'])

const store = useSupportChatStore()

const menuActions = ref([{
    name: 'deleteRoom',
    title: 'Delete Room',
}])

onMounted(() => {
    this.store._setUserId(this.userId)
    this.store.fetchRooms()
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
