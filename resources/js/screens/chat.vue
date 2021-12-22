<template>
    <chat-window
        :current-user-id="currentUserId"
        :rooms="rooms"
        :rooms-loaded="roomsLoaded"
        :messages="messages"
        :messages-loaded="true"
        :room-actions="menuActions"
        :menu-actions="menuActions"
        @fetch-messages="fetchMessages"
        @fetch-more-rooms="fetchRooms"
        @send-message="sendMessage"
        @edit-message="editMessage"
        @delete-message="deleteMessage"
        @add-room="addRoom"
        @room-action-handler="menuActionHandler"
        @menu-action-handler="menuActionHandler"
    />
</template>

<script>
import { mapActions, mapState } from 'vuex'
import ChatWindow from 'vue-advanced-chat'
import 'vue-advanced-chat/dist/vue-advanced-chat.css'

export default {
    components: {
        ChatWindow,
    },

    data() {
        return {
            //rooms: [],
            //messages: [],
            //currentUserId: 1234,
            menuActions: [{
                name: 'deleteRoom',
                title: 'Delete Room',
            }],
        }
    },

    mounted() {
        this.fetchRooms()
    },

    methods: {
        menuActionHandler({ roomId, action }) {
            switch (action.name) {
                case 'deleteRoom':
                    this.deleteRoom(roomId)
            }
        },

        ...mapActions([
            'fetchRooms', 'addRoom', 'deleteRoom',
            'fetchMessages', 'sendMessage', 'editMessage', 'deleteMessage',
        ]),
    },

    computed: mapState(['currentUserId', 'rooms', 'roomsLoaded', 'messages']),
}
</script>
