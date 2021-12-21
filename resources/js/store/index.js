import { SET_ROOMS, ADD_ROOM, DELETE_ROOM } from './mutation-types'

export default new Vuex.Store({
    state: {
        rooms: [],
    },

    getters: {
        rooms: state => {
            return state.rooms
        },
    },

    mutations: {
        [SET_ROOMS](state, rooms) {
            state.rooms = rooms
        },

        [ADD_ROOM](state, room) {
            state.rooms = [...state.rooms, room]
        },
    },

    actions: {
        async fetchRooms({ commit }) {
            const response = await this.$api.rooms.index()
            commit(SET_ROOMS, response.data)
        },

        async createRoom({ commit }, room) {
            const response = await this.$api.rooms.store(room)
            commit(ADD_ROOM, response.data)
        },

        async deleteRoom({ commit }, roomId) {
            const response = await this.$api.rooms.destroy(roomId)
            commit(DELETE_ROOM, roomId)
        },
    },
})
