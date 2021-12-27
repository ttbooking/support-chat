import Vue from 'vue'
import Vuex from 'vuex'
import {
    SET_USER_ID,
    SET_ROOMS_LOADED_STATE,
    SET_ROOMS,
    ADD_ROOM,
    DELETE_ROOM,
    SET_ROOM_ID,
    ROOM_SET_USERS,
    ROOM_JOIN_USER,
    ROOM_LEAVE_USER,
    SET_MESSAGES,
    ADD_MESSAGE,
    EDIT_MESSAGE,
    DELETE_MESSAGE,
} from './mutation-types'
import api from '../api'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        currentUserId: null,
        rooms: [],
        roomsLoaded: false,
        roomId: null,
        messages: [],
    },

    getters: {
        joinedRooms: state => {
            return state.rooms.filter(room => room.users.some(user => user._id === state.currentUserId))
        },
    },

    mutations: {
        [SET_USER_ID](state, userId) {
            state.currentUserId = userId
        },

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

        [SET_ROOM_ID](state, roomId) {
            state.roomId = roomId
        },

        [ROOM_SET_USERS](state, { roomId, users }) {
            const roomIndex = state.rooms.findIndex(currentRoom => currentRoom.roomId === roomId)
            state.rooms[roomIndex].users = users
            state.rooms = [...state.rooms]
        },

        [ROOM_JOIN_USER](state, { roomId, user }) {
            const roomIndex = state.rooms.findIndex(currentRoom => currentRoom.roomId === roomId)
            state.rooms[roomIndex].users = [...state.rooms[roomIndex].users, user]
            state.rooms = [...state.rooms]
        },

        [ROOM_LEAVE_USER](state, { roomId, user }) {
            const roomIndex = state.rooms.findIndex(currentRoom => currentRoom.roomId === roomId)
            const users = state.rooms[roomIndex].users.filter(currentUser => currentUser._id !== user._id)
            state.rooms[roomIndex].users = [...users]
            state.rooms = [...state.rooms]
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
        async fetchRooms({ commit, getters, state }) {
            commit(SET_ROOMS_LOADED_STATE, false)
            const response = await api.rooms.index()
            commit(SET_ROOMS, response.data.data)
            commit(SET_ROOMS_LOADED_STATE, true)

            for (const room of getters.joinedRooms) {
                window.Echo.join(`support-chat.room.${room.roomId}`)
                    .here(users => {
                        commit(ROOM_SET_USERS, { roomId: room.roomId, users })
                    })
                    .joining(user => {
                        commit(ROOM_JOIN_USER, { roomId: room.roomId, user })
                    })
                    .leaving(user => {
                        commit(ROOM_LEAVE_USER, { roomId: room.roomId, user })
                    })
                    .error(error => {
                        console.error(error)
                    })
                    .listen('.message.posted', message => {
                        room.roomId === state.roomId && commit(ADD_MESSAGE, message)
                    })
                    .listen('.message.edited', message => {
                        room.roomId === state.roomId && commit(EDIT_MESSAGE, message)
                    })
                    .listen('.message.deleted', message => {
                        room.roomId === state.roomId && commit(DELETE_MESSAGE, message)
                    })
            }
        },

        async addRoom({ commit }, room = {}) {
            const response = await api.rooms.store(room)
            commit(ADD_ROOM, response.data.data)
        },

        async deleteRoom({ commit }, roomId) {
            const response = await api.rooms.destroy(roomId)
            commit(DELETE_ROOM, roomId)
        },

        async fetchMessages({ commit }, { room, options }) {
            commit(SET_ROOM_ID, room.roomId)
            const response = await api.messages.index(room.roomId)
            commit(SET_MESSAGES, response.data.data)
        },

        async sendMessage({ commit, state }, { roomId, content, files, replyMessage, usersTag }) {
            const response = await api.messages.store(roomId, {
                content,
                senderId: state.currentUserId,
                replyMessage,
            })
            commit(ADD_MESSAGE, response.data.data)
        },

        async editMessage({ commit, state }, { roomId, messageId, newContent, files, replyMessage, usersTag }) {
            const response = await api.messages.update(messageId, {
                content: newContent,
                senderId: state.currentUserId,
            })
            commit(EDIT_MESSAGE, response.data.data)
        },

        async deleteMessage({ commit }, { roomId, message }) {
            const response = await api.messages.destroy(message._id)
            commit(DELETE_MESSAGE, message._id)
        },
    },
})
