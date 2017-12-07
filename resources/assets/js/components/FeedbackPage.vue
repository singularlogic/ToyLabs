<template>
    <div id="collaborations">
        <div class="ui pointing secondary orange menu">
            <router-link :to="`/${type}/${id}/feedback`" class="item" exact>Overview</router-link>
            <router-link :to="{ name: 'feedbackview', params: { id, type, org_id: organization.id, thread_type: 'feedback' } }" v-if="organization" class="item">{{ organization.name }}</router-link>
        </div>

        <router-view></router-view>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Overview from './collaborations/Overview.vue';
import Contact from './collaborations/Contact.vue';

export default {
    props: ['type', 'id'],
    created() {
        const baseUrl = `/${this.type}/${this.id}/feedback`;
        const routes = [
            { name: 'feedback', path: `${baseUrl}/`, component: Overview, props: { type: this.type, id: this.id, thread_type: 'feedback' } },
            { name: 'feedbackview', path: `/:type/:id/:thread_type/:org_id`, component:  Contact },
        ];

        this.$router.addRoutes(routes);
    },
    computed: {
        ...mapGetters({
            organization: 'activePartner',
        }),
    },
};
</script>