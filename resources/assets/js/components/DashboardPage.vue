<template>
    <div class="ui main container" id="dashboard">
        <div class="ui orange pointing secondary menu">
            <router-link to="/dashboard" class="item" exact>My Products</router-link>
            <router-link to="/dashboard/collaborations" class="item" v-if="activeCollaborations.length > 0">Collaborations</router-link>
            <router-link to="/dashboard/archive" class="item" v-if="archivedCollaborations.length > 0">Archive</router-link>
            <router-link :to="{ name: 'threadview', params: { id: activeThread.id } }" class="item" v-if="activeThread">
                <i class="icon" :class="getIconClass(activeThread.type)"></i> {{ activeThread.target.title }}
            </router-link>
            <div class="right menu">
                <div class="item" style="padding-right: 6px;" v-if="isProfileComplete">
                    <a class="ui labeled orange mini icon button" href="/product/create" id="newButton">
                        <i class="icon plus"></i>
                        New Product
                    </a>
                </div>
                <div class="ui category search item">
                    <div class="ui icon input">
                        <input type="text" placeholder="Search..." />
                        <i class="search link icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
            </div>
        </div>

        <router-view></router-view>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ProductList from './ProductList.vue';
import ThreadView from './ThreadView.vue';
import TableView from './collaborations/TableView.vue';

export default {
    props: ['isProfileComplete', 'products', 'activeCollaborations', 'archivedCollaborations'],
    created() {
        const baseUrl = `/${this.type}/${this.id}/collaborate`;
        const routes = [
            { name: 'dashboard', path: '/dashboard', component: ProductList, props: { products: this.products } },
            { name: 'collaborations', path: '/dashboard/collaborations', component: TableView, props: { data: this.activeCollaborations, emptyMessage: 'No active collaborations found!' } },
            { name: 'archive', path: '/dashboard/archive', component: TableView, props: { data: this.archivedCollaborations, emptyMessage: 'No archived collaborations found!' } },
            { name: 'threadview', path: `/dashboard/message/:id`, component:  ThreadView },
        ];

        this.$router.addRoutes(routes);
    },
    methods: {
        getIconClass(type) {
            if (type === 'feedback') return 'comments outline';
            if (type === 'negotiation') return 'file text outline';

            return '';
        }
    },
    computed: {
        ...mapGetters([
            'activeThread',
        ]),
    },
};
</script>