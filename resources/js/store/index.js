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
    FAIL_MESSAGE,
    DELETE_MESSAGE,
    UPLOAD_PROGRESS,
    LEAVE_REACTION,
    REMOVE_REACTION,
} from './mutation-types'
import api from '../api'

Vue.use(Vuex)

const throttle = require('lodash.throttle')

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
        findMessageIndexById: state => _id => state.messages.findIndex(message => message._id === _id),
        findMessageIndexByIndexId: state => indexId => state.messages.findIndex(message => message.indexId === indexId),
        findMessageIndex: (state, getters) => message => {
            return message.indexId !== null
                ? getters.findMessageIndexByIndexId(message.indexId)
                : getters.findMessageIndexById(message._id)
        },
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
            const lastMessageId = Math.max.apply(Math, [0, ...state.messages.map(message => message._id)])
            state.nextMessageId = nextMessageId ?? lastMessageId + 1
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

        [UPDATE_MESSAGE](state, { messageIndex, message }) {
            state.messages[messageIndex] = message
            state.messages = [...state.messages]
        },

        [EDIT_MESSAGE](state, { messageIndex, message }) {
            state.messages[messageIndex] = message
            state.messages = [...state.messages]
        },

        [FAIL_MESSAGE](state, messageIndex) {
            state.messages[messageIndex] = { ...state.messages[messageIndex], failure: true }
            state.messages = [...state.messages]
        },

        [DELETE_MESSAGE](state, messageIndex) {
            state.messages[messageIndex] = { ...state.messages[messageIndex], deleted: true }
            state.messages = [...state.messages]
        },

        [UPLOAD_PROGRESS](state, { messageIndex, filename, progress }) {
            const fileIndex = state.messages[messageIndex].files.findIndex(file => {
                return file.name + '.' + file.type === filename
            })
            state.messages[messageIndex].files[fileIndex].progress = progress
        },

        [LEAVE_REACTION](state, { messageIndex, userId, emoji }) {
            let reactionUsers = state.messages[messageIndex].reactions[emoji] ?? []
            reactionUsers.push(userId)
            Vue.set(state.messages[messageIndex].reactions, emoji, [...new Set(reactionUsers)])
        },

        [REMOVE_REACTION](state, { messageIndex, userId, emoji }) {
            let reactionUsers = state.messages[messageIndex].reactions[emoji] ?? []
            const userIndex = reactionUsers.indexOf(userId)
            if (userIndex > -1) {
                reactionUsers.splice(userIndex, 1)
            }
            Vue.set(state.messages[messageIndex].reactions, emoji, [...new Set(reactionUsers)])
        },
    },

    actions: {
        async fetchRooms({ commit, state, getters }) {
            commit(SET_ROOMS_LOADED_STATE, false)
            const response = await api.rooms.index()
            commit(SET_ROOMS, response.data.data)
            commit(SET_ROOMS_LOADED_STATE, true)

            for (const room of getters.joinedRooms) {
                window.roomChannel = window.Echo.join(`support-chat.room.${room.roomId}`)
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
                        commit(SET_NEXT_MSGID)
                    })
                    .listen('.message.edited', message => {
                        const messageIndex = getters.findMessageIndex(message)
                        room.roomId === state.roomId && commit(EDIT_MESSAGE, { messageIndex, message })
                    })
                    .listen('.message.deleted', message => {
                        const messageIndex = getters.findMessageIndex(message)
                        room.roomId === state.roomId && commit(DELETE_MESSAGE, messageIndex)
                    })
                    .listen('.reaction.left', ({ messageIndexId, userId, emoji }) => {
                        const messageIndex = getters.findMessageIndexByIndexId(messageIndexId)
                        commit(LEAVE_REACTION, { messageIndex, userId, emoji })
                    })
                    .listen('.reaction.removed', ({ messageIndexId, userId, emoji }) => {
                        const messageIndex = getters.findMessageIndexByIndexId(messageIndexId)
                        commit(REMOVE_REACTION, { messageIndex, userId, emoji })
                    })
                    .listenForWhisper('attachment.uploading', ({ messageIndexId, filename, progress }) => {
                        const messageIndex = getters.findMessageIndexByIndexId(messageIndexId)
                        if (messageIndex > -1) {
                            commit(UPLOAD_PROGRESS, { messageIndex, filename, progress })
                        }
                    })
                    .listen('.attachment.uploaded', ({ messageIndexId, filename }) => {
                        const messageIndex = getters.findMessageIndexByIndexId(messageIndexId)
                        commit(UPLOAD_PROGRESS, { messageIndex, filename, progress: -1 })
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

        async sendMessage({ commit, state, dispatch }, { roomId, content, files, replyMessage, usersTag }) {
            const _id = state.nextMessageId
            commit(INC_NEXT_MSGID)

            const message = { _id, content, replyMessage, files }
            commit(INIT_MESSAGE, message)

            await dispatch('trySendMessage', { roomId, message })
        },

        async trySendMessage({ commit, dispatch, getters }, { roomId, message }) {
            const messageIndex = getters.findMessageIndex(message)
            try {
                const response = await api.messages.store(roomId, {
                    content: message.content,
                    parent_id: message.replyMessage?.indexId,
                    attachments: message.files?.map(file => ({
                        name: file.name + '.' + file.extension,
                        type: file.type,
                        size: file.size,
                    })) ?? [],
                })
                const savedMessage = response.data.data
                commit(UPDATE_MESSAGE, { messageIndex, message: { ...savedMessage, _id: message._id } })

                for (let file of message.files || []) {
                    await dispatch('uploadAttachment', { message: savedMessage, file })
                    commit(UPLOAD_PROGRESS, {
                        messageIndex,
                        filename: file.name + '.' + file.extension,
                        progress: -1,
                    })
                }
            } catch (error) {
                commit(FAIL_MESSAGE, messageIndex)
            }
        },

        async syncAttachments({ commit, state }, { messageId, files }) {

        },

        async uploadAttachment({ commit, getters }, { message, file }) {
            let formData = new FormData
            formData.append('attachment', file.blob, file.name + '.' + file.extension)
            const messageIndex = getters.findMessageIndex(message)
            const response = await api.attachments.store(message.indexId, formData, {
                onUploadProgress: throttle(e => {
                    const upload = {
                        messageIndexId: message.indexId,
                        filename: file.name + '.' + file.extension,
                        progress: Math.round((e.loaded * 100) / e.total),
                    }
                    window.roomChannel.whisper('attachment.uploading', upload)
                    commit(UPLOAD_PROGRESS, { messageIndex, filename: upload.filename, progress: upload.progress })
                }, 100),
            })
        },

        async editMessage({ commit, state, getters }, { roomId, messageId, newContent, files, replyMessage, usersTag }) {
            const response = await api.messages.update(messageId, {
                content: newContent,
                replyMessage: replyMessage ?? getters.getMessage(messageId).replyMessage,
            })
            const savedMessage = response.data.data
            const messageIndex = getters.findMessageIndex(savedMessage)
            commit(EDIT_MESSAGE, { messageIndex, savedMessage })
        },

        async deleteMessage({ commit, getters }, { roomId, message }) {
            const response = await api.messages.destroy(message.indexId)
            const messageIndex = getters.findMessageIndex(message)
            commit(DELETE_MESSAGE, messageIndex)
        },

        async sendMessageReaction({ commit, state }, { roomId, messageId, reaction, remove }) {
            const response = await api.messageReactions[remove ? 'destroy' : 'store'](messageId, reaction.unicode)
            commit(remove ? REMOVE_REACTION : LEAVE_REACTION, {
                messageIndexId: messageId,
                userId: state.currentUserId,
                emoji: reaction.unicode
            })
        },
    },
})
