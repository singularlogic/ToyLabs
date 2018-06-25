<template>
    <div class="ui vertical container" v-if="filteredProducts.length > 0">
        <div class="ui right floated basic buttons">
            <button class="ui button" :class="{ active: filter == 'all' }" @click="show('all')">
                All
            </button>
            <button class="ui button" :class="{ active: filter == 'product' }" @click="show('product')">
                <i class="blue circle icon" />
                Products
            </button>
            <button class="ui button" :class="{ active: filter == 'design' }" @click="show('design')">
                <i class="yellow circle icon" />
                Designs
            </button>
            <button class="ui button" :class="{ active: filter == 'prototype' }" @click="show('prototype')">
                <i class="orange circle icon" />
                Prototypes
            </button>
        </div>

        <div class="ui clearing divider" style="border-color: rgba(0,0,0,0)" />

        <div class="ui cards" :class="[size]">
            <Product v-for="p of filteredProducts"
                :key="p.id"
                :product="p"
                :detailed="detailed"
                :target="target"
            ></Product>
        </div>
    </div>
</template>

<script>
import Product from './Product.vue';

export default {
    components: { Product },
    props: ['products', 'size', 'detailed', 'target'],
    data() {
        return {
            filter: 'all',
        };
    },
    computed: {
        filteredProducts() {
            if (this.filter === 'all') return this.products;

            return this.products.filter(p => p.type === this.filter);
        }
    },
    methods: {
        show(filter) {
            this.filter = filter;
        }
    }
}
</script>