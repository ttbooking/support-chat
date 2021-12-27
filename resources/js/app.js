import Vue from 'vue'
import Base from './base'
import Echo from 'laravel-echo'
import store from './store'
import SupportChat from './components/SupportChat'

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

window.Pusher = require('pusher-js')
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.SupportChat.pusher.key,
    cluster: window.SupportChat.pusher.cluster ?? 'eu',
    forceTLS: window.SupportChat.pusher.useTLS ?? true,
})

Vue.mixin(Base)

new Vue({
    el: '#support-chat',
    components: { SupportChat },
    store,
})
