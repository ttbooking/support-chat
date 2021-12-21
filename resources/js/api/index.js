import axios from 'axios'
import rooms from './rooms'
import messages from './messages'

const $http = axios.create()

export default {
    rooms: rooms($http),
    messages: messages($http),
}
