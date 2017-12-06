<template>
    <table class="ui sortable celled striped table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Product</th>
                <th class="one wide center aligned">Version</th>
                <th class="two wide center aligned">Last Modified</th>
                <th class="one wide"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in data">
                <td>
                    <i class="icon" :class="getIconClass(item.type)"></i>
                    <a :href="`/${item.type}/${item.id}`" target="_BLANK">{{ item.title }}</a>
                </td>
                <td>
                    <a :href="`/product/${item.product_id}`" target="_BLANK">{{ item.product_name }}</a>
                </td>
                <td class="center aligned">{{ item.version }}</td>
                <td class="center aligned">
                    <timeago :since="item.updated_at" :max-time="86400 * 7" :format="formatDate" :auto-update="30"></timeago>
                </td>
                <td>
                    <div class="ui tiny basic icon buttons">
                        <router-link :to="`/dashboard/message/${item.feedback_id}`" class="ui button" data-tooltip="Feedback" data-position="top center" data-inverted v-if="item.feedback_id">
                            <i class="comments outline icon"></i>
                        </router-link>
                        <router-link :to="`/dashboard/message/${item.negotiations_id}`" class="ui button" data-tooltip="Negotiations" data-position="top center" data-inverted v-if="item.negotiations_id">
                            <i class="file text outline icon"></i>
                        </router-link>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import moment from 'moment';

export default {
    props: ['columns', 'data'],
    mounted() {
        $('table.sortable').tablesort();
    },
    methods: {
        getIconClass(type) {
            if (type === 'design') return 'pencil';
            if (type === 'prototype') return 'cube';

            return '';
        },
        formatDate(time) {
            return moment(new Date(time)).format('DD.MM.YYYY');
        },
    },
};
</script>