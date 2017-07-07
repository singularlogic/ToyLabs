<template>
    <div class="comment">
        <span class="avatar">
            <img :src="comment.author.image" />
        </span>
        <div class="content">
            <a class="author">{{ comment.author.name }}</a>
            <div class="metadata">
                <timeago class="date" :since="comment.created_at" :auto-update="30"></timeago>
            </div>
            <div class="text">{{ comment.body }}</div>
            <div class="actions">
                <a class="reply" @click="reply">Reply</a>
            </div>
        </div>
        <div class="comments" v-if="comment.comments.length > 0">
            <comment v-for="c of comment.comments"
                :key="c.id"
                :comment="c"
            ></comment>
        </div>
    </div>
</template>

<script>
export default {
    name: 'comment',
    props: ['comment'],
    data() {
        return {};
    },
    methods: {
        reply() {
            console.log('NYI: reply');
        }
    },
}
</script>