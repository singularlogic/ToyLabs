<template>
    <div class="right menu">
        <a class="ui item" style="padding-right: 12px;" ref="messages">
            <i class="large mail outline icon" style="margin-right: 0.1em;"></i>
            <div class="floating circular ui red label" style="position: relative; left: 10%;" v-if="hasUnreadMessages">{{ totalMessages }}</div>
        </a>
        <a class="ui item" style="padding-right: 12px;" ref="notifications" id="notificationsIcon">
            <i class="large alarm outline icon" style="margin-right: 0.1em;"></i>
            <div class="floating circular ui red label" style="position: relative; left: 10%;" v-if="hasUnreadNotifications">{{ totalNotifications }}</div>
        </a>
        <div class="ui custom popup top center transition hidden" id="notificationsPopup">
            <div class="ui middle aligned divided list">
                <div class="item" v-if="!hasUnreadNotifications">
                    <div class="content">You don't have any unread notifications.</div>
                </div>
                <notification v-for="n of notifications"
                    :key="n.id"
                    :notification="n"
                    v-on:read="markAsRead(n)"
                ></notification>
                <div class="item">
                    <div class="content" style="text-align: center;">
                        <a href="">View all notifications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Notification from './Notification.vue';

export default {
    components: { Notification },
    data: () => ({
        totalNotifications: 0,
        totalMessages: 0,
        notifications: [],
    }),
    mounted() {
        this.fetch();

        if (window.Echo) {
            this.listen();
        }
    },
    computed: {
        hasUnreadNotifications() {
            return this.totalNotifications > 0;
        },
        hasUnreadMessages() {
            return this.totalMessages > 0;
        }
    },
    methods: {
        listen() {
            window.Echo.private(`App.User.${window.Laravel.user.id}`)
                .notification((notification) => {
                    this.totalNotifications++;
                    this.notifications.push(notification);
                })
                .listen('NotificationRead', ({ notificationId }) => {
                    this.totalNotifications--;

                    const index = this.notifications.findIndex(n => n.id === notificationId);
                    if (index > -1) {
                        this.notifications.splice(index, 1);
                    }
                })
                .listen('NotificationReadAll', () => {
                    this.totalNotifications = 0;
                    this.notifications = [];
                });
        },
        fetch() {
            axios.get('/notifications').then(response => {
                this.totalNotifications = response.data.total;
                this.notifications = response.data.notifications;
            });
        },
        markAsRead(notification) {
            const index = this.notifications.findIndex(n => n.id === notification.id);
            if (index > -1) {
                this.totalNotifications--;
                this.notifications.splice(index, 1);
                axios.patch(`/notifications/${notification.id}/read`)
            }
        }
    },
}
</script>

<style>
.item .content {
    padding: 8px 0;
}
</style>