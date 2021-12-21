import Vue from 'vue'
import Vuex from 'vuex'
import { SET_ROOMS_LOADED_STATE, SET_ROOMS, ADD_ROOM, DELETE_ROOM } from './mutation-types'
import api from '../api'

Vuex.Store.prototype.$api = api
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        rooms: [],
        roomsLoaded: false,
    },

    getters: {
        /*rooms: state => {
            return state.rooms
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
    },

    actions: {
        async fetchRooms({ commit }) {
            commit(SET_ROOMS_LOADED_STATE, false)
            const response = await this.$api.rooms.index()
            commit(SET_ROOMS, response.data.data)
            commit(SET_ROOMS_LOADED_STATE, true)
        },

        async createRoom({ commit }, room) {
            const response = await this.$api.rooms.store(room)
            commit(ADD_ROOM, response.data.data)
        },

        async deleteRoom({ commit }, roomId) {
            const response = await this.$api.rooms.destroy(roomId)
            commit(DELETE_ROOM, roomId)
        },
    },
})
