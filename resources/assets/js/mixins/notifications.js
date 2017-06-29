export default {
    methods: {
        fetch() {
            if (this.$store.state.notifications.length === 0) {
                this.$store.dispatch('fetchNotifications');
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
                });
        },
        markAsRead(notification) {
            this.$store.dispatch('markNotificationAsRead', notification);
        },
        acceptRequest(notification) {
            console.log('NYI: acceptRequest');
            // TODO: Accept the request
        },
        declineRequest(notification) {
            console.log('NYI: declineRequest');
            this.markAsRead(notification);
            // TODO: Decline the request
        }
    },
}