<template>
    <div class="ui vertical container" v-if="products.length > 0">
        <div class="ui cards" :class="[size]">
            <Product v-for="p of products"
                :key="p.id"
                :product="p"
                :detailed="detailed"
            ></Product>
        </div>
    </div>
</template>

<script>
import Product from './Product.vue';

export default {
    components: { Product },
    props: ['products', 'size', 'detailed'],
    data() {
        return {};
    },
    created() {
        // TODO: Get products/designs/prototypes from backend
    }
}
</script>