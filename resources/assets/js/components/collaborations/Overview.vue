<template>
    <table class="ui sortable celled striped table">
        <thead>
            <tr>
                <th class="ten wide">Organization</th>
                <th class="three wide">Status</th>
                <th class="three wide center aligned">Last Message</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="thread of threads" :class="getClass(thread.status)">
                <td>
                    <router-link :to="{ name: 'discussionview', params: { id, type, org_id: thread.org_id } }">{{ thread.organization }}</router-link>
                </td>
                <td>{{ thread.status }}</td>
                <td class="center aligned">
                    <timeago :since="thread.updated_at" :max-time="86400 * 7" :format="formatDate" :auto-update="30"></timeago>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import moment from 'moment';

export default {
    props: ['type', 'id'],
    data() {
        return {
            threads: [],
        }
    },
    created() {
        axios.get(`/${this.type}/${this.id}/negotiations`).then((res) => {
            if (res.status === 200) {
                this.threads = res.data;
            }
        });
    },
    methods: {
        getClass(status) {
            switch (status) {
            case 'Accepted':
                return 'positive';
            case 'Rejected':
                return 'negative';
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