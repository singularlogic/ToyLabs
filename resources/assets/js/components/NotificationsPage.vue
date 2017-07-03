<template>
    <div id="notifications">
        <div class="ui pointing secondary orange menu">
            <router-link to="/feed/notifications" class="item">
                <i class="icon bell"></i> Notifications
                <div class="ui orange label" v-if="totalNotifications > 0">{{ totalNotifications }}</div>
            </router-link>
            <router-link to="/feed/messages" class="item">
                <i class="icon mail"></i> Messages
                <div class="ui orange label" v-if="unreadMessages > 0">{{ unreadMessages }}</div>
            </router-link>
            <router-link :to="{ name: 'messageview', params: { id: activeThread.id } }" class="item" v-if="activeThread">
                <i class="icon mail outline"></i> {{ activeThread.subject }}
            </router-link>
        </div>

        <router-view keep-alive></router-view>
    </div>
</template>

<script>
import NotificationsTab from './NotificationsTab.vue';
import MessagesTab from './MessagesTab.vue';
import RequestsTab from './RequestsTab.vue';
import ThreadView from './ThreadView.vue';
import { mapGetters } from 'vuex';

export default {
    created() {
        // Initialize router
        const routes = [
            { name: 'feed', path: '/feed', beforeEnter: (to, from, next) => next('/feed/notifications') },
            { name: 'notifications', path: '/feed/notifications', component: NotificationsTab },
            { name: 'messages', path: '/feed/messages', component: MessagesTab },
            { name: 'messageview', path: '/feed/message/:id', component: ThreadView },
        ];
        this.$router.addRoutes(routes);
    },
    computed: {
        ...mapGetters([
            'totalNotifications',
            'unreadMessages',
            'activeThread',
        ]),
        ...mapGetters({
            notifications: 'notificationsArray',
            popupNotifications: 'latestNotifications',
        }),
    },
}
</script>
