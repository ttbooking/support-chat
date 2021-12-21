import Vue from 'vue'
import Vuex from 'vuex'
import BootstrapVue from 'bootstrap-vue'
import Base from './base'
import Echo from 'laravel-echo'
import axios from 'axios'
import api from './api/index'
import Routes from './routes'
import VueRouter from 'vue-router'
import 'bootstrap'
import store from './store/index'

let token = document.head.querySelector('meta[name="csrf-token"]')

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

Vue.use(Vuex)
Vue.use(BootstrapVue)
Vue.use(VueRouter)

Vue.prototype.$http = axios.create()
Vue.prototype.$api = api

window.Pusher = require('pusher-js')
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.SupportChat.pusher.key,
    cluster: window.SupportChat.pusher.cluster ?? 'eu',
    forceTLS: window.SupportChat.pusher.useTLS ?? true,
})

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
