import { defineStore, acceptHMRUpdate } from 'pinia'
import { ref, computed } from 'vue'
import { throttle } from 'lodash'
import api from "@/api";

export const useSupportChatStore = defineStore('support-chat', () => {

    /** State **/

    const currentUserId = ref(null)

    const rooms = ref([])

    const roomsLoaded = ref(false)

    const roomId = ref(null)

    const nextMessageId = ref(1)

    const messages = ref([])

    /** Getters **/

    const findMessageIndexById = computed(() => _id => messages.value.findIndex(message => message._id === _id))

    const findMessageIndexByIndexId = computed(() => indexId => messages.value.findIndex(message => message.indexId === indexId))

    const findMessageIndex = computed(() => message => {
        return message.indexId !== null
            ? findMessageIndexByIndexId.value(message.indexId)
            : findMessageIndexById.value(message._id)
    })

    const getMessage = computed(() => id => {
        const messageIndex = messages.value.findIndex(message => message._id === id)
        return messages.value[messageIndex]
    })

    const joinedRooms = computed(() => {
        return rooms.value.filter(room => room.users.some(user => user._id === currentUserId.value))
    })

    /** Mutations **/

    function _setUserId(_userId) {
        currentUserId.value = _userId
    }

    function _setRoomsLoadedState(_roomsLoaded) {
        roomsLoaded.value = _roomsLoaded
    }

    function _setRooms(_rooms) {
        rooms.value = _rooms
    }

    function _addRoom(_room) {
        rooms.value = [...rooms.value, _room]
    }

    function _deleteRoom(_roomId) {
        rooms.value = rooms.value.filter(room => room.roomId !== _roomId)
    }

    function _setRoomId(_roomId) {
        roomId.value = _roomId
    }

    function _roomSetUsers(_roomId, _users) {
        const roomIndex = rooms.value.findIndex(currentRoom => currentRoom.roomId === _roomId)
        rooms.value[roomIndex].users = _users
        rooms.value = [...rooms.value]
    }

    function _roomJoinUser(_roomId, _user) {
        const roomIndex = rooms.value.findIndex(currentRoom => currentRoom.roomId === _roomId)
        rooms.value[roomIndex].users = [...rooms.value[roomIndex].users, _user]
        rooms.value = [...rooms.value]
    }

    function _roomLeaveUser(_roomId, _user) {
        const roomIndex = rooms.value.findIndex(currentRoom => currentRoom.roomId === _roomId)
        const users = rooms.value[roomIndex].users.filter(currentUser => currentUser._id !== _user._id)
        rooms.value[roomIndex].users = [...users]
        rooms.value = [...rooms.value]
    }

    function _setMessages(_messages) {
        messages.value = _messages
    }

    function _setNextMsgId(_nextMessageId = null) {
        const lastMessageId = Math.max.apply(Math, [0, ...messages.value.map(message => +message._id)])
        nextMessageId.value = _nextMessageId ?? lastMessageId + 1
    }

    function _incrementNextMsgId() {
        nextMessageId.value++
    }

    function _initMessage({ _id, content, replyMessage, files }) {
        let message = {
            _id,
            content,
            senderId: currentUserId.value,
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
        messages.value = [...messages.value, message]
    }

    function _addMessage(_message) {
        messages.value = [...messages.value, _message]
    }

    function _updateMessage(_messageIndex, _message) {
        messages.value[_messageIndex] = _message
        messages.value = [...messages.value]
    }

    function _editMessage(_messageIndex, _message) {
        messages.value[_messageIndex] = _message
        messages.value = [...messages.value]
    }

    function _failMessage(_messageIndex) {
        messages.value[_messageIndex] = { ...messages.value[_messageIndex], failure: true }
        messages.value = [...messages.value]
    }

    function _deleteMessage(_messageIndex) {
        messages.value[_messageIndex] = { ...messages.value[_messageIndex], deleted: true }
        messages.value = [...messages.value]
    }

    function _uploadProgress(_messageIndex, _filename, _progress) {
        const fileIndex = messages.value[_messageIndex].files.findIndex(file => {
            return file.name + '.' + file.type === _filename
        })
        messages.value[_messageIndex].files[fileIndex].progress = _progress
    }

    function _leaveReaction(_messageIndex, _userId, _emoji) {
        let reactionUsers = messages.value[_messageIndex].reactions[_emoji] ?? []
        reactionUsers.push(_userId)
        messages.value[_messageIndex].reactions[_emoji] = [...new Set(reactionUsers)]
    }

    function _removeReaction(_messageIndex, _userId, _emoji) {
        let reactionUsers = messages.value[_messageIndex].reactions[_emoji] ?? []
        const userIndex = reactionUsers.indexOf(_userId)
        if (userIndex > -1) {
            reactionUsers.splice(userIndex, 1)
        }
        messages.value[_messageIndex].reactions[_emoji] = [...new Set(reactionUsers)]
    }

    /** Actions **/

    async function fetchRooms() {
        _setRoomsLoadedState(false)
        const response = await api.rooms.index()
        _setRooms(response.data.data)
        _setRoomsLoadedState(true)

        for (const room of joinedRooms.value) {
            window.roomChannel = window.Echo.join(`support-chat.room.${room.roomId}`)
                .here(users => {
                    _roomSetUsers(room.roomId, users)
                })
                .joining(user => {
                    _roomJoinUser(room.roomId, user)
                })
                .leaving(user => {
                    _roomLeaveUser(room.roomId, user)
                })
                .error(error => {
                    console.error(error)
                })
                .listen('.message.posted', message => {
                    room.roomId === roomId.value && _addMessage(message)
                    _setNextMsgId()
                })
                .listen('.message.edited', message => {
                    const messageIndex = findMessageIndex.value(message)
                    room.roomId === roomId.value && _editMessage(messageIndex, message)
                })
                .listen('.message.deleted', message => {
                    const messageIndex = findMessageIndex.value(message)
                    room.roomId === roomId.value && _deleteMessage(messageIndex)
                })
                .listen('.reaction.left', ({ messageIndexId, userId, emoji }) => {
                    const messageIndex = findMessageIndexByIndexId.value(messageIndexId)
                    _leaveReaction(messageIndex, userId, emoji)
                })
                .listen('.reaction.removed', ({ messageIndexId, userId, emoji }) => {
                    const messageIndex = findMessageIndexByIndexId.value(messageIndexId)
                    _removeReaction(messageIndex, userId, emoji)
                })
                .listenForWhisper('attachment.uploading', ({ messageIndexId, filename, progress }) => {
                    const messageIndex = findMessageIndexByIndexId.value(messageIndexId)
                    if (messageIndex > -1) {
                        _uploadProgress(messageIndex, filename, progress)
                    }
                })
                .listen('.attachment.uploaded', ({ messageIndexId, filename }) => {
                    const messageIndex = findMessageIndexByIndexId.value(messageIndexId)
                    _uploadProgress(messageIndex, filename, -1)
                })
        }
    }

    async function addRoom(room = {}) {
        const response = await api.rooms.store(room)
        _addRoom(response.data.data)
    }

    async function deleteRoom(roomId) {
        await api.rooms.destroy(roomId)
        _deleteRoom(roomId)
    }

    async function fetchMessages({ room, options }) {
        _setRoomId(room.roomId)
        const response = await api.messages.index(room.roomId)
        _setMessages(response.data.data)
        _setNextMsgId()
    }

    async function sendMessage({ roomId, content, files, replyMessage, usersTag }) {
        const _id = nextMessageId.value.toString()
        _incrementNextMsgId()

        const message = { _id, content, replyMessage, files }
        _initMessage(message)

        await trySendMessage({ roomId, message })
    }

    async function trySendMessage({ roomId, message }) {
        const messageIndex = findMessageIndex.value(message)
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
            _updateMessage(messageIndex, { ...savedMessage, _id: message._id })

            for (let file of message.files || []) {
                await uploadAttachment({ message: savedMessage, file })
                _uploadProgress(messageIndex, file.name + '.' + file.extension, -1)
            }
        } catch (error) {
            _failMessage(messageIndex)
        }
    }

    async function syncAttachments({ messageId, files }) {
        //
    }

    async function uploadAttachment({ message, file }) {
        let formData = new FormData
        formData.append('attachment', file.blob, file.name + '.' + file.extension)
        const messageIndex = findMessageIndex.value(message)
        await api.attachments.store(message.indexId, formData, {
            onUploadProgress: throttle(e => {
                const upload = {
                    messageIndexId: message.indexId,
                    filename: file.name + '.' + file.extension,
                    progress: Math.round((e.loaded * 100) / e.total),
                }
                window.roomChannel.whisper('attachment.uploading', upload)
                _uploadProgress(messageIndex, upload.filename, upload.progress)
            }, 100),
        })
    }

    async function editMessage({ roomId, messageId, newContent, files, replyMessage, usersTag }) {
        const response = await api.messages.update(messageId, {
            content: newContent,
            replyMessage: replyMessage ?? getMessage.value(messageId).replyMessage,
        })
        const savedMessage = response.data.data
        const messageIndex = findMessageIndex.value(savedMessage)
        _editMessage(messageIndex, savedMessage)
    }

    async function deleteMessage({ roomId, message }) {
        await api.messages.destroy(message.indexId)
        const messageIndex = findMessageIndex.value(message)
        _deleteMessage(messageIndex)
    }

    async function sendMessageReaction({ roomId, messageId, reaction, remove }) {
        const messageIndex = findMessageIndexById.value(messageId)
        if (remove) {
            await api.messageReactions.destroy(messageId, reaction.unicode)
            _removeReaction(messageIndex, currentUserId.value, reaction.unicode)
        } else {
            await api.messageReactions.store(messageId, reaction.unicode)
            _leaveReaction(messageIndex, currentUserId.value, reaction.unicode)
        }
    }

    return {
        currentUserId, rooms, roomsLoaded, roomId, nextMessageId, messages,

        findMessageIndexById, findMessageIndexByIndexId, findMessageIndex, getMessage, joinedRooms,

        _setUserId, _setRoomsLoadedState, _setRooms, _addRoom, _deleteRoom, _setRoomId, _roomSetUsers, _roomJoinUser,
        _roomLeaveUser, _setMessages, _setNextMsgId, _incrementNextMsgId, _initMessage, _addMessage, _updateMessage,
        _editMessage, _failMessage, _deleteMessage, _uploadProgress, _leaveReaction, _removeReaction,

        fetchRooms, addRoom, deleteRoom, fetchMessages, sendMessage, trySendMessage, syncAttachments, uploadAttachment,
        editMessage, deleteMessage, sendMessageReaction,
    }
})

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useSupportChatStore, import.meta.hot))
}
