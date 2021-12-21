<template>
    <chat-window :current-user-id="currentUserId" :rooms="rooms" :messages="messages" />
</template>

<script>
import ChatWindow from 'vue-advanced-chat'
import 'vue-advanced-chat/dist/vue-advanced-chat.css'

export default {
    components: {
        ChatWindow
    },

    data() {
        return {
            rooms: [],
            messages: [],
            currentUserId: 1234
        }
    }
}
</script>
