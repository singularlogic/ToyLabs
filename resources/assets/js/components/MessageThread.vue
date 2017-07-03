<template>
    <div class="ui segment">
        <router-link class="comment" :to="{ name: 'messageview', params: { id: thread.id } }">
            <span class="avatar">
                <img :src="thread.creator.image" />
            </span>
            <div class="content">
                <span class="author">{{ thread.creator.name }}</span>
                <div class="metadata">
                    <div class="date"><timeago :since="thread.created_at" :auto-update="30"></timeago></div>
                </div>
                <div class="text">{{ thread.subject }}</div>
            </div>
        </router-link>
    </div>
</template>

<script>
export default {
    props: ['thread'],
    data() {
        return {
        };
    },
    computed: {
        hasReplies() {
            return this.thread.replies > 0;
        },
        hasManyReplies() {
            return this.thread.replies > 4;
        }
    },
    filters: {
        formatReplies: (value) => {
            if (value == 1) {
                return `${value} Message`;
            } else {
                return `${value} Messages`;
            }
        }
    }
}
</script>