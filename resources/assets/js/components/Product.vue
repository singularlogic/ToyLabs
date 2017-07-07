<template>
    <a class="card" :href="url">
        <div class="ui fluid image">
            <img :src="product.image" />
            <span class="ui right ribbon label" :class="getClass" v-if="detailed">{{ product.type }}</span>
        </div>
        <div class="content">
            <div class="header">{{ product.title }}</div>
            <div class="description" v-if="detailed">{{ product.description | truncate }}</div>
        </div>
        <div class="extra">
            <span class="right floated"><i class="heart icon"></i> {{ likes }}</span>
            <span><i class="comments icon"></i> {{ comments }}</span>
        </div>
    </a>
</template>

<script>
export default {
    props: ['product', 'detailed'],
    data() {
        return {};
    },
    computed: {
        getClass() {
            switch (this.product.type) {
            case 'design':
                return 'yellow';
            case 'prototype':
                return 'orange';
            default:
                return 'blue';
            }
        },
        url() {
            return `/${this.product.type}/${this.product.id}`;
        },
        comments() {
            return this.product.commentCount === 1 ? `${this.product.commentCount} Comment` : `${this.product.commentCount} Comments`;
        },
        likes() {
            return this.product.likeCount === 1 ? `${this.product.likeCount} Like` : `${this.product.likeCount} Likes`;
        }
    },
    filters: {
        truncate(text, l) {
            const length = l || 200;

            if (text.length <= length) {
                return text;
            }

            let tcText = text.slice(0, length - 3);
            let last = tcText.length - 1;

            while (last > 0 && tcText[last] !== ' ' && tcText[last] !== '.') {
                last -= 1;
            }

            last = last || length - 3;
            tcText =  tcText.slice(0, last);

            return tcText + '...';
        }
    }
}
</script>

<style>
.ui.ribbon.label {
    text-transform: capitalize!important;
}
</style>