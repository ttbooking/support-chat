import moment from 'moment'

export default {
    computed: {
        SupportChat() {
            return SupportChat
        },
    },

    methods: {
        formatTime(timestamp, format = 'DD.MM.YYYY HH:mm:ss') {
            return moment(timestamp).format(format)
        },
    },
}
