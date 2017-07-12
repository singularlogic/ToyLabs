<template>
    <div class="ui very relaxed divided list">
        <product-progress v-for="product of products"
            :product="product"
            :key="product.id"
        ></product-progress>
    </div>
</template>

<script>
import ProductProgress from './ProductProgress.vue';

export default {
    components: { ProductProgress },
    props: ['products'],
    data() {
        return {};
    },
}
</script>