import moment from 'moment'

export default {
    computed: {
        SupportChat() {
            return SupportChat
        },

        $echo() {
            return Echo.channel('support-chat')
        },
    },

    methods: {
        formatTime(timestamp, format = 'DD.MM.YYYY HH:mm:ss') {
            return moment(timestamp).format(format)
        },
    },
}
