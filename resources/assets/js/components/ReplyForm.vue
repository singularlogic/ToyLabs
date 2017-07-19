<template>
    <form class="ui reply form">
        <div class="field">
            <textarea v-model="comment.body"></textarea>
        </div>
        <div class="ui primary submit labeled right floated icon button" @click="sendReply">
            <i class="icon edit"></i> Add Reply
        </div>
        <div class="ui right floated button" @click="cancel">Cancel</div>
    </form>
</template>

<script>
export default {
    props: ['comment'],
    data() {
        return {};
    },
    methods: {
        cancel() {
            this.$emit('cancel');
        },
        sendReply() {
            this.$emit('update:comment', this.comment); // Sync newComment with parent
            this.$emit('reply');                        // Notify parent to process reply
        }
    }
}
</script>