import Vue from 'vue'
import Vuex from 'vuex'
import {
    SET_ROOMS_LOADED_STATE,
    SET_ROOMS,
    ADD_ROOM,
    DELETE_ROOM,
    SET_MESSAGES,
    ADD_MESSAGE,
    EDIT_MESSAGE,
    DELETE_MESSAGE,
} from './mutation-types'
import api from '../api'

Vuex.Store.prototype.$api = api
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        currentUserId: 5088,
        rooms: [],
        roomsLoaded: false,
        messages: [],
    },

    getters: {
        joinedRooms: state => {
            return state.rooms.filter(room => room.users.some(user => user._id === state.currentUserId))
        },
    },

    mutations: {
        [SET_ROOMS_LOADED_STATE](state, roomsLoaded) {
            state.roomsLoaded = roomsLoaded
        },

        [SET_ROOMS](state, rooms) {
            state.rooms = rooms
        },

        [ADD_ROOM](state, room) {
            state.rooms = [...state.rooms, room]
        },

        [DELETE_ROOM](state, roomId) {
            state.rooms = state.rooms.filter(room => room.roomId !== roomId)
        },

        [SET_MESSAGES](state, messages) {
            state.messages = messages
        },

        [ADD_MESSAGE](state, message) {
            state.messages = [...state.messages, message]
        },

        [EDIT_MESSAGE](state, message) {
            const messageIndex = state.messages.findIndex(currentMessage => currentMessage._id === message._id)
            state.messages[messageIndex] = message
            state.messages = [...state.messages]
        },

        [DELETE_MESSAGE](state, messageId) {
            state.messages = state.messages.filter(message => message._id !== messageId)
        },
    },

    actions: {
        async fetchRooms({ commit, getters }) {
            commit(SET_ROOMS_LOADED_STATE, false)
            const response = await this.$api.rooms.index()
            commit(SET_ROOMS, response.data.data)
            commit(SET_ROOMS_LOADED_STATE, true)

            for (const room of getters.joinedRooms) {
                window.Echo.join(`support-chat.room.${room.roomId}`)
                    .here(users => {
                        console.log(users)
                    })
                    .joining(user => {
                        console.log(user.username)
                    })
                    .leaving(user => {
                        console.log(user.username)
                    })
                    .error(error => {
                        console.error(error)
                    })
            }
        },

        async addRoom({ commit }, room = {}) {
            const response = await this.$api.rooms.store(room)
            commit(ADD_ROOM, response.data.data)
        },

        async deleteRoom({ commit }, roomId) {
            const response = await this.$api.rooms.destroy(roomId)
            commit(DELETE_ROOM, roomId)
        },

        async fetchMessages({ commit }, { room, options }) {
            const response = await this.$api.messages.index(room.roomId)
            commit(SET_MESSAGES, response.data.data)
        },

        async sendMessage({ commit, state }, { roomId, content, files, replyMessage, usersTag }) {
            const response = await this.$api.messages.store(roomId, {
                content,
                senderId: state.currentUserId,
                replyMessage,
            })
            commit(ADD_MESSAGE, response.data.data)
        },

        async editMessage({ commit, state }, { roomId, messageId, newContent, files, replyMessage, usersTag }) {
            const response = await this.$api.messages.update(messageId, {
                content: newContent,
                senderId: state.currentUserId,
            })
            commit(EDIT_MESSAGE, response.data.data)
        },

        async deleteMessage({ commit }, { roomId, message }) {
            const response = await this.$api.messages.destroy(message._id)
            commit(DELETE_MESSAGE, message._id)
        },
    },
})
