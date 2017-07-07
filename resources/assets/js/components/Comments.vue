<template>
    <div class="ui stripe vertical segment">
        <div class="ui threaded comments">
            <comment v-for="c of comments"
                :key="c.id"
                :comment="c"
            ></comment>
        </div>
    </div>
</template>

<script>
import Comment from './Comment.vue';

export default {
    props: ['comments'],
    components: { Comment },
    data() {
        return {};
    },
}
</script>