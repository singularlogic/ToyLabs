<template>
    <div class="card">
        <div class="ui fluid image">
            <img :src="product.image" />
            <span class="ui right ribbon label" :class="getClass">{{ product.type }}</span>
        </div>
        <div class="content">
            <div class="header"><a :href="url">{{ product.title }}</a></div>
            <div class="description">{{ product.description }}</div>
        </div>
        <div class="extra content">
            <span class="right floated"><i class="heart icon"></i> {{ likes }}</span>
            <span><i class="comments icon"></i> {{ comments }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: ['product'],
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
            return this.product.comments === 1 ? `${this.product.comments} Comment` : `${this.product.comments} Comments`;
        },
        likes() {
            return this.product.likes === 1 ? `${this.product.likes} Like` : `${this.product.likes} Likes`;
        }
    },
}
</script>

<style>
.ui.ribbon.label {
    text-transform: capitalize!important;
}
</style>