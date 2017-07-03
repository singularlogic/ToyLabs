<template>
    <div class="ui comment segment" :class="{ secondary: isMine }">
        <div class="avatar">
            <img :src="message.user.image" />
        </div>
        <div class="content">
            <span class="author">{{ message.user.name }}</span>
            <div class="metadata">
                <span class="date"><timeago :since="message.created_at" :auto-update="30"></timeago></span>
            </div>
            <div class="text" v-html="formatMessage(message.body)"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message'],
    computed: {
        isMine() {
            return this.message.user.id === window.Laravel.user.id;
        }
    },
    methods: {
        formatMessage: (value) => {
            const breakTag = '<br />';
            return (value + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }
    }
}
</script>

<style>
.comment.segment {
    padding: 8px!important;
    border: 1px solid #F3F4F5!important;
}

.comment.segment.secondary {
    border: 1px solid #dcddde!important;
    background-color: #F3F4F5!important;
}
</style>