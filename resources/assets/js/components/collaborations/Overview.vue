<template>
    <table class="ui sortable celled striped table">
        <thead>
            <tr>
                <th>Organization</th>
                <th class="four wide" v-if="thread_type === 'negotiation'">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="thread of threads" :class="getClass(thread.status)">
                <td>
                    <router-link :to="{ name: view, params: { id, type, org_id: thread.org_id, thread_type } }">
                        {{ thread.organization }}
                    </router-link>
                </td>
                <td v-if="thread_type === 'negotiation'" :class="{ disabled: thread.status === 'Archived' }">
                    <i class="icon" :class="getIcon(thread.status)"></i>
                    {{ thread.status }}
                </td>
            </tr>
            <tr v-if="threads.length === 0">
                <td colspan="3">No discussions found</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import moment from 'moment';

export default {
    props: ['type', 'id', 'thread_type'],
    data() {
        return {
            threads: [],
        }
    },
    created() {
        const url = `/${this.type}/${this.id}/${this.thread_type === 'negotiation' ? 'negotiations' : 'discussions' }`;
        axios.get(url).then((res) => {
            if (res.status === 200) {
                this.threads = res.data;
            }
        });
    },
    computed: {
        view() {
            return this.thread_type === 'feedback' ? 'feedbackview' : 'discussionview';
        },
    },
    methods: {
        getClass(status) {
            switch (status) {
            case 'Accepted':
                return 'positive';
            case 'Rejected':
                return 'negative';
            case 'Archived':
                return 'grey';
            default:
                return '';
            }
        },
        getIcon(status) {
            switch (status) {
            case 'Accepted':
                return 'check circle';
            case 'Rejected':
                return 'remove circle';
            case 'Archived':
                return 'archive';
            case 'Negotiating':
                return 'comments'
            default:
                return '';
            }
        },
        formatDate(time) {
            return moment(new Date(time)).format('DD.MM.YYYY');
        },
    },
};
</script>