import Vue from 'vue'
import Vuex from 'vuex'
import {
    SET_ROOMS_LOADED_STATE,
    SET_ROOMS,
    ADD_ROOM,
    DELETE_ROOM,
    SET_MESSAGES,
} from './mutation-types'
import api from '../api'

Vuex.Store.prototype.$api = api
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        rooms: [],
        roomsLoaded: false,
        messages: [],
    },

    getters: {
        /*rooms: state => {
            return state.rooms
        },

        messages: state => {
            return state.messages
        },*/
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
    },

    actions: {
        async fetchRooms({ commit }) {
            commit(SET_ROOMS_LOADED_STATE, false)
            const response = await this.$api.rooms.index()
            commit(SET_ROOMS, response.data.data)
            commit(SET_ROOMS_LOADED_STATE, true)
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
    },
})
