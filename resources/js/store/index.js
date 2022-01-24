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
    SET_NEXT_MSGID,
    INC_NEXT_MSGID,
    INIT_MESSAGE,
    ADD_MESSAGE,
    UPDATE_MESSAGE,
    EDIT_MESSAGE,
    DELETE_MESSAGE,
    ATTACH_FILE,
    UPDATE_ATTACHMENT,
    LEAVE_REACTION,
    REMOVE_REACTION,
} from './mutation-types'
import api from '../api'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        currentUserId: null,
        rooms: [],
        roomsLoaded: false,
        roomId: null,
        nextMessageId: 1,
        messages: [],
    },

    getters: {
        getMessage: state => id => {
            const messageIndex = state.messages.findIndex(message => message._id === id)
            return state.messages[messageIndex]
        },
        joinedRooms: state => {
            return state.rooms.filter(room => room.users.some(user => user._id === state.currentUserId))
        },
    },

    mutations: {
        [SET_USER_ID](state, userId) {
            state.currentUserId = +userId
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

        [SET_NEXT_MSGID](state, nextMessageId = null) {
            state.nextMessageId = nextMessageId ?? Math.max.apply(Math, state.messages.map(message => message._id)) + 1
        },

        [INC_NEXT_MSGID](state) {
            state.nextMessageId++
        },

        [INIT_MESSAGE](state, { _id, content, replyMessage, files }) {
            let message = {
                _id,
                content,
                senderId: state.currentUserId,
                username: '',
                system: false,
                saved: false,
                distributed: false,
                seen: false,
                deleted: false,
                failure: false,
                disableActions: false,
                disableReactions: false,
                files: [],
                reactions: {},
                replyMessage,
            }
            for (const file of files || []) {
                message.files.push({
                    name: file.name,
                    size: file.size,
                    type: file.extension,
                    audio: false,
                    duration: 0,
                    url: file.localURL,
                    preview: null,
                    progress: 0,
                })
            }
            state.messages = [...state.messages, message]
        },

        [ADD_MESSAGE](state, message) {
            state.messages = [...state.messages, message]
        },

        [UPDATE_MESSAGE](state, message) {
            const messageIndex = state.messages.findIndex(currentMessage => currentMessage._id === message._id)
            state.messages[messageIndex] = message
            state.messages = [...state.messages]
        },

        [EDIT_MESSAGE](state, message) {
            const messageIndex = state.messages.findIndex(currentMessage => currentMessage._id === message._id)
            state.messages[messageIndex] = message
            state.messages = [...state.messages]
        },

        [DELETE_MESSAGE](state, messageId) {
            //state.messages = state.messages.filter(message => message._id !== messageId)
            const messageIndex = state.messages.findIndex(message => message._id === messageId)
            state.messages[messageIndex] = { ...state.messages[messageIndex], deleted: true }
            state.messages = [...state.messages]
        },

        [UPDATE_ATTACHMENT](state, messageId) {

        },

        [LEAVE_REACTION](state, { userId, messageId, reaction }) {
            const messageIndex = state.messages.findIndex(currentMessage => currentMessage._id === messageId)
            let reactionUsers = state.messages[messageIndex].reactions[reaction] ?? []
            reactionUsers.push(userId)
            Vue.set(state.messages[messageIndex].reactions, reaction, [...new Set(reactionUsers)])
        },

        [REMOVE_REACTION](state, { userId, messageId, reaction }) {
            const messageIndex = state.messages.findIndex(currentMessage => currentMessage._id === messageId)
            let reactionUsers = state.messages[messageIndex].reactions[reaction] ?? []
            const userIndex = reactionUsers.indexOf(userId)
            if (userIndex > -1) {
                reactionUsers.splice(userIndex, 1)
            }
            Vue.set(state.messages[messageIndex].reactions, reaction, [...new Set(reactionUsers)])
        },
    },

    actions: {
        async fetchRooms({ commit, state, getters }) {
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
                    .listen('.message-reaction.left', reaction => {
                        commit(LEAVE_REACTION, {
                            userId: reaction.user_id,
                            messageId: reaction.message_id,
                            reaction: reaction.emoji
                        })
                    })
                    .listen('.message-reaction.removed', reaction => {
                        commit(REMOVE_REACTION, {
                            userId: reaction.user_id,
                            messageId: reaction.message_id,
                            reaction: reaction.emoji
                        })
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

        async fetchMessages({ commit, state }, { room, options }) {
            commit(SET_ROOM_ID, room.roomId)
            const response = await api.messages.index(room.roomId)
            commit(SET_MESSAGES, response.data.data)
            commit(SET_NEXT_MSGID)
        },

        async sendMessage({ commit, state }, { roomId, content, files, replyMessage, usersTag }) {
            const _id = state.nextMessageId
            commit(INC_NEXT_MSGID)
            commit(INIT_MESSAGE, { _id, content, replyMessage, files })
            const response = await api.messages.store(roomId, {
                content,
                parent_id: replyMessage?._id,
                attachments: files?.map(file => ({
                    name: file.name + '.' + file.extension,
                    type: file.type,
                    size: file.size,
                })) ?? [],
            })
            const message = response.data.data
            commit(UPDATE_MESSAGE, { ...message, _id })

            for (let file of files || []) {
                //await this.uploadAttachment({ commit }, { messageId: message._id, file })
            }
        },

        async syncAttachments({ commit, state }, { messageId, files }) {

        },

        async uploadAttachment({ commit }, { messageId, file }) {
            let formData = new FormData
            formData.append('attachment', file.blob, file.name + '.' + file.extension)
            const response = await api.attachments.store(messageId, formData, {
                onUploadProgress: e => {
                    const progress = Math.round((e.loaded * 100) / e.total)
                    //commit(UPDATE_ATTACHMENT, { _id:  })
                },
            })
        },

        async editMessage({ commit, state, getters }, { roomId, messageId, newContent, files, replyMessage, usersTag }) {
            const response = await api.messages.update(messageId, {
                content: newContent,
                replyMessage: replyMessage ?? getters.getMessage(messageId).replyMessage,
            })
            commit(EDIT_MESSAGE, response.data.data)
        },

        async deleteMessage({ commit }, { roomId, message }) {
            const response = await api.messages.destroy(message._id)
            commit(DELETE_MESSAGE, message._id)
        },

        async sendMessageReaction({ commit, state }, { roomId, messageId, reaction, remove }) {
            const response = await api.messageReactions[remove ? 'destroy' : 'store'](messageId, reaction.unicode)
            commit(remove ? REMOVE_REACTION : LEAVE_REACTION, { userId: state.currentUserId, messageId, reaction: reaction.unicode })
        },
    },
})
