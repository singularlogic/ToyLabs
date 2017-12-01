<template>
    <div id="collaborations">
        <div class="ui pointing secondary orange menu">
            <router-link :to="`/${type}/${id}/collaborate`" class="item" exact>Overview</router-link>
            <router-link :to="`/${type}/${id}/collaborate/search`" class="item">Search</router-link>
            <router-link :to="{ name: 'discussionview', params: { id, type, org_id: organization.id, thread_type: 'negotiation' } }" v-if="organization" class="item">{{ organization.name }}</router-link>
        </div>

        <keep-alive include="search">
            <router-view></router-view>
        </keep-alive>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Search from '../Search.vue';
import Overview from './Overview.vue';
import Contact from './Contact.vue';

export default {
    props: ['roles', 'competencies', 'paymentTypes', 'back', 'type', 'id'],
    created() {
        const baseUrl = `/${this.type}/${this.id}/collaborate`;
        const routes = [
            { name: 'overview', path: `${baseUrl}/`, component: Overview, props: { type: this.type, id: this.id, thread_type: 'negotiation' } },
            { name: 'search', path: `${baseUrl}/search`, component: Search,
                props: {
                    roles: this.roles,
                    competencies: this.competencies,
                    paymentTypes: this.paymentTypes,
                    back: this.back,
                    type: this.type,
                    id: this.id
                }
            },
            { name: 'discussionview', path: `/:type/:id/collaborate/contact/:org_id`, component:  Contact },
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