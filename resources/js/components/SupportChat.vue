<template>
    <chat-window
        :current-user-id="userId"
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
import { mapMutations, mapActions, mapState } from 'vuex'
import { SET_USER_ID } from '../store/mutation-types'
import ChatWindow from 'vue-advanced-chat'
import 'vue-advanced-chat/dist/vue-advanced-chat.css'

export default {
    components: {
        ChatWindow,
    },

    props: ['userId'],

    data() {
        return {
            menuActions: [{
                name: 'deleteRoom',
                title: 'Delete Room',
            }],
        }
    },

    mounted() {
        this.setUser(this.userId)
        this.fetchRooms()
    },

    methods: {
        menuActionHandler({ roomId, action }) {
            switch (action.name) {
                case 'deleteRoom':
                    this.deleteRoom(roomId)
            }
        },

        ...mapMutations({
            setUser: SET_USER_ID,
        }),

        ...mapActions([
            'fetchRooms', 'addRoom', 'deleteRoom',
            'fetchMessages', 'sendMessage', 'editMessage', 'deleteMessage',
        ]),
    },

    computed: mapState(['currentUserId', 'rooms', 'roomsLoaded', 'messages']),
}
</script>
