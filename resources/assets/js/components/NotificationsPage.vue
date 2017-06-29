<template>
    <div id="notifications">
        <div class="ui pointing secondary orange menu">
            <router-link to="/feed/notifications" class="item">
                <i class="icon bell"></i> Notifications
                <div class="ui orange label" v-if="totalNotifications > 0">{{ totalNotifications }}</div>
            </router-link>
            <router-link to="/feed/messages" class="item">
                <i class="icon mail"></i> Messages
                <div class="ui orange label" v-if="totalMessages > 0">{{ totalMessages }}</div>
            </router-link>
        </div>

        <router-view keep-alive></router-view>
    </div>
</template>

<script>
import NotificationsTab from './NotificationsTab.vue';
import MessagesTab from './MessagesTab.vue';
import RequestsTab from './RequestsTab.vue';
import { mapGetters } from 'vuex';

export default {
    created() {
        // Initialize router
        const routes = [
            { path: '/feed', beforeEnter: (to, from, next) => next('/feed/notifications') },
            { path: '/feed/notifications', component: NotificationsTab },
            { path: '/feed/messages', component: MessagesTab },
        ];
        this.$router.addRoutes(routes);
    },
    computed: {
        ...mapGetters([
            'totalNotifications',
            'totalMessages',
        ]),
        ...mapGetters({
            notifications: 'notificationsArray',
            popupNotifications: 'latestNotifications',
        }),
    },
}
</script>
