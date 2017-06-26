<template>
    <div class="item">
        <div class="right floated content">
            <a class="ui" @click.prevent="markAsRead()"><i class="close grey icon"></i></a>
        </div>
        <img class="ui avatar image" :src="notification.data.sender.avatar" />
        <div class="content">
            {{ notification.data.message }}
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
    methods: {
        markAsRead() {
            this.$emit('read');
        }
    }
}
</script>

<style>
.ui.list>.item>.image+.content {
    padding: 0.5em;
}
</style>