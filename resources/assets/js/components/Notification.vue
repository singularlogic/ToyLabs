<template>
    <div class="item">
        <div class="right floated content">
            <button class="ui small basic green icon button" @click.prevent="accept()" v-if="needsResponse"><i class="checkmark icon"></i></button>
            <button class="ui small basic red icon button" @click.prevent="decline()" v-if="needsResponse"><i class="remove icon"></i></button>
            <a class="ui" @click.prevent="markAsRead()" v-if="!needsResponse"><i class="close grey icon"></i></a>
        </div>
        <img class="ui avatar image" :src="notification.sender.avatar" />
        <div class="content">
            {{ notification.message }}
            <div class="description">
                <small class="timestamp">
                    <timeago :since="notification.created" :auto-update="30"></timeago>
                </small>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['notification'],
    computed: {
        needsResponse() {
            return true;
        }
    },
    methods: {
        markAsRead() {
            this.$emit('read');
        },
        accept() {
            this.$emit('accept');
        },
        decline() {
            this.$emit('decline');
        }
    }
}
</script>

<style>
.ui.list>.item>.image+.content {
    padding: 0.5em;
}
</style>