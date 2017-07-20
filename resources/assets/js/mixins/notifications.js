export default {
    methods: {
        fetch() {
            if (this.$store.state.notifications.length === 0) {
                this.$store.dispatch('fetchNotifications');
            }

            if (this.$store.state.messages.length === 0) {
                this.$store.dispatch('fetchMessages');
            }
        },
        listen() {
            window.Echo.private(`App.User.${window.Laravel.user.id}`)
                .notification((notification) => {
                    this.$store.commit('addNotification', { notification });
                })
                .listen('NotificationRead', ({ notificationId }) => {
                    this.$store.commit('removeNotification', { notificationId });
                })
                .listen('NotificationReadAll', () => {
                    this.$store.commit('clearNotifications');
                })
                .listen('NewMessage', () => {
                    this.$store.commit('messageArrived');
                });
        },
        markAsRead(notification) {
            this.$store.dispatch('markNotificationAsRead', notification);
        },
        acceptRequest(notification) {
            this.$store.dispatch('acceptRequest', notification);
        },
        declineRequest(notification) {
            this.$store.dispatch('declineRequest', notification);
        }
    },
}