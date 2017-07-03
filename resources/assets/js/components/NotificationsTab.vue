<template>
    <div>
        <div class="ui middle aligned divided list">
            <notification v-for="n of notifications"
                :key="n.id"
                :notification="n"
                v-on:read="markAsRead(n)"
                v-on:accept="acceptRequest(n)"
                v-on:decline="declineRequest(n)"
            ></notification>
        </div>
    </div>
</template>

<script>
import { NotificationsMixin } from '../mixins';
import Notification from './Notification.vue';
import { mapGetters } from 'vuex';

export default {
    mixins: [NotificationsMixin],
    components: {
        Notification
    },
    computed: {
        ...mapGetters({
            notifications: 'notificationsArray',
        }),
    },
}
</script>