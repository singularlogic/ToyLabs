<template>
    <div class="right menu">
        <a class="ui item" href="/feed/messages" style="padding-right: 12px;" ref="messages">
            <i class="large mail outline icon" style="margin-right: 0.1em;"></i>
            <div class="floating circular ui red label" style="position: relative; left: 10%;" v-if="unreadMessages > 0">{{ unreadMessages }}</div>
        </a>
        <a class="ui item" style="padding-right: 12px;" ref="notifications" id="notificationsIcon">
            <i class="large bell outline icon" style="margin-right: 0.1em;"></i>
            <div class="floating circular ui red label" style="position: relative; left: 10%;" v-if="totalNotifications > 0">{{ totalNotifications }}</div>
        </a>
        <div class="ui custom popup top center transition hidden" id="notificationsPopup">
            <div class="ui middle aligned divided list">
                <div class="item" v-if="totalNotifications == 0">
                    <div class="content">You don't have any unread notifications.</div>
                </div>
                <notification v-for="n of popupNotifications"
                    :key="n.id"
                    :notification="n"
                    v-on:read="markAsRead(n)"
                    v-on:accept="acceptRequest(n)"
                    v-on:decline="declineRequest(n)"
                ></notification>
                <div class="item">
                    <div class="content" style="text-align: center;">
                        <a href="/feed/notifications">View all notifications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Notification from './Notification.vue';
import { NotificationsMixin } from '../mixins';
import { mapGetters } from 'vuex';

export default {
    mixins: [NotificationsMixin],
    components: { Notification },
    mounted() {
        this.fetch();

        if (window.Echo) {
            this.listen();
        }
    },
    computed: {
        ...mapGetters([
            'totalNotifications',
            'unreadMessages',
        ]),
        ...mapGetters({
            notifications: 'notificationsArray',
            popupNotifications: 'latestNotifications',
        }),
    },
}
</script>

<style>
.item .content {
    padding: 8px 0;
}
</style>