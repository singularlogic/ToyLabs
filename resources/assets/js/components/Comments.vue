<template>
<div class="ui basic segment">
    <h3 class="ui dividing header">Comments ({{ localComments.length }})</h3>

    <div class="ui vertical segment">
        <div class="ui threaded comments">
            <comment v-for="c of children"
                :model="model"
                :key="c.id"
                :comments="localComments"
                :comment="c"
                v-on:reply="addComment"
                v-on:delete="deleteComment"
            ></comment>
        </div>

        <div class="ui clearing hidden divider"></div>

        <form class="ui reply form" v-if="isLogged">
            <div class="field">
                <textarea rows="5" v-model="newComment.body"></textarea>
            </div>
            <div class="ui orange labeled icon right floated button" @click="newReply">
                <i class="icon edit"></i> Add Comment
            </div>
        </form>
    </div>

    <confirm-dialog
        id="commentDelete"
        icon="trash"
        title="Comment Delete"
        body="Are you sure you want to delete this comment and all its replies?"
    ></confirm-dialog>
</div>
</template>

<script>
import Comment from './Comment.vue';
import ConfirmDialog from './ConfirmDialog.vue';
import { mapGetters } from 'vuex';

export default {
    props: ['comments', 'model'],
    components: { Comment, ConfirmDialog },
    data() {
        return {
            localComments: this.comments,
            displayForm: this.comments.length === 0,
            newComment: {
                title: '',
                body: '',
                parent_id: null,
            },
        };
    },
    computed: {
        children() {
            return this.localComments.filter((c) => c.parent_id === null);
        },
        ...mapGetters([
            'isLogged'
        ]),
    },
    methods: {
        addComment(comment) {
            this.saveComment(comment);
        },
        saveComment(comment) {
            axios.post('/user/comment', {
                model: this.model,
                comment: comment,
                parent_id: comment.parent_id,
            }).then((response) => {
                if (response.status === 200) {
                    const c = response.data;
                    this.localComments.push(c);
                }
            });
        },
        cancel() {
            this.newComment.title = '';
            this.newComment.body = '';
        },
        newReply() {
            this.saveComment({
                body: this.newComment.body,
                title: this.newComment.title,
                parent_id: null,
            });
            this.cancel();
        },
        deleteComment(comment) {
            $('#commentDelete').modal({
                closable: false,
                onApprove: () => {
                    axios.delete(`/user/comment/${comment.id}`).then((response) => {
                        const index = this.localComments.indexOf(comment);
                        if (~index) {
                            this.localComments.splice(index, 1);
                        }
                    });
                },
            }).modal('show');
        }
    }
}
</script>

<style>
.ui.vertical.segment {
    border-bottom: none;
}
</style>