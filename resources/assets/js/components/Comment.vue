<template>
    <div class="comment">
        <span class="avatar">
            <img :src="comment.creator.image" />
        </span>
        <div class="content">
            <a class="author">{{ comment.creator.name }}</a>
            <div class="metadata">
                <timeago class="date" :since="comment.created_at" :auto-update="30"></timeago>
            </div>
            <div class="text">{{ comment.body }}</div>
            <div class="actions" v-if="!readOnly">
                <a class="reply" @click="reply" v-if="isLogged">Reply</a>
                <a class="delete" @click="deleteComment(comment)" v-if="comment.creator_id === userID">Delete</a>
            </div>
            <reply-form
                :comment.sync="newComment"
                 v-if="showReply"
                 v-on:reply="addReply"
                 v-on:cancel="cancel"
            ></reply-form>
        </div>
        <div class="comments" v-if="children.length > 0">
            <comment v-for="c of children"
                :model="model"
                :key="c.id"
                :comments="comments"
                :comment="c"
                v-on:reply="sendReply"
                v-on:delete="deleteComment"
            ></comment>
        </div>
    </div>
</template>

<script>
import ReplyForm from './ReplyForm.vue';
import { mapGetters } from 'vuex';

export default {
    name: 'comment',
    components: { ReplyForm },
    props: ['comments', 'comment', 'model', 'readOnly'],
    data() {
        return {
            newComment: {
                title: '',
                body: '',
                parent_id: this.comment.id,
            },
            showReply: false,
        };
    },
    computed: {
        ...mapGetters(['isGuest', 'isLogged', 'userID']),
        children() {
            return this.comments.filter((c) => c.parent_id === this.comment.id);
        }
    },
    methods: {
        cancel() {
            this.showReply = false;
            this.newComment = {
                title: '',
                body: '',
                parent_id: this.comment.id,
            };
        },
        reply() {
            this.showReply = true;
        },
        deleteComment(comment) {
            this.$emit('delete', comment);
        },
        sendReply(comment) {
            this.$emit('reply', comment);
        },
        addReply() {
            this.sendReply(this.newComment);
            this.cancel();
        }
    },
}
</script>