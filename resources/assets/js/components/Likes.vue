<template>
    <div class="ui right labeled button" tabindex="0">
        <button class="ui red button" @click="like" :class="{ basic: isLiked }">{{ likeLabel }}</button>
        <span class="ui red label" :class="{ basic: !isLiked }">
            <i class="heart icon"></i> {{ count }}
        </span>
    </div>
</template>

<script>
export default {
    props: ['likes', 'id', 'type', 'liked'],
    data() {
        return {
            count: this.likes,
            isLiked: this.liked == 1 || this.liked == true,
        };
    },
    computed: {
        likeLabel() {
            return this.isLiked ? 'Unlike' : 'Like';
        }
    },
    methods: {
        like() {
            if (this.isLiked) {
                axios.put(`/user/unlike/${this.type}/${this.id}`).then((response) => {
                    if (response.status === 200) {
                        this.count -= 1;
                        this.isLiked = false;
                    }
                }).catch(() => {}); // Emprty catch statement
            } else {
                axios.put(`/user/like/${this.type}/${this.id}`).then((response) => {
                    if (response.status === 200) {
                        this.count += 1;
                        this.isLiked = true;
                    }
                }).catch(() => {}); // Emprty catch statement
            }
        }
    },
}
</script>