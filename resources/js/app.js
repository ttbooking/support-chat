import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Base from './base'
import Echo from 'laravel-echo'
import Routes from './routes'
import VueRouter from 'vue-router'
import 'bootstrap'
import store from './store'

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

Vue.use(BootstrapVue)
Vue.use(VueRouter)

window.SupportChat.basePath = '/' + window.SupportChat.path

let routerBasePath = window.SupportChat.basePath + '/'

if (window.SupportChat.path === '' || window.SupportChat.path === '/') {
    routerBasePath = '/'
    window.SupportChat.basePath = ''
}

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: routerBasePath,
})

Vue.mixin(Base)

new Vue({
    el: '#support-chat',
    store,
    router,
})
