<template>
    <a class="card" :href="url" :target="target">
        <div class="ui fluid image">
            <div class="image-container">
                <img :src="product.image" v-on:load="imageLoaded"/>
            </div>
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
    props: ['product', 'detailed', 'target'],
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

            if (text === null || text.length <= length) {
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
    },
    methods: {
        imageLoaded(event) {
            let img = event.target;
            let container = $(".image-container")[0];
            let scaleX = container.offsetWidth / img.width;
            let scaleY = container.offsetHeight / img.height;
            img.style.cssText = `--normal-scale: ${Math.max(scaleX, scaleY)}; --hover-scale: ${Math.min(scaleX, scaleY)};`;
        }
    }
}
</script>

<style>
.ui.ribbon.label {
    text-transform: capitalize!important;
}
</style>