<template>
    <chat-window
        :current-user-id="currentUserId"
        :rooms="rooms"
        :rooms-loaded="roomsLoaded"
        :messages="messages"
        @fetch-more-rooms="fetchRooms"
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
            messages: [],
            currentUserId: 1234,
        }
    },

    mounted() {
        this.fetchRooms()
    },

    methods: {
        ...mapActions(['fetchRooms']),
    },

    computed: mapState(['rooms', 'roomsLoaded']),
}
</script>
