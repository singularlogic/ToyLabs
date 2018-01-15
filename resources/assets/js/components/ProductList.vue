<template>
    <div>
        <div class="ui icon info message" v-if="$parent.isEmpty">
            <i class="info icon"></i>
            <div class="content">
                <div class="header">No products or collaborations found!</div>
                <p>
                    <li>Use the <strong>New Product</strong> button to create a new product.</li>
                    <li>Make sure your professional profile is complete so that you can be found by others to collaborate with.</li>
                </p>
            </div>
        </div>


        <div class="ui very relaxed divided list">
            <product-progress v-for="product of products"
                :product="product"
                :key="product.id"
            ></product-progress>
        </div>
    </div>
</template>

<script>
import ProductProgress from './ProductProgress.vue';

export default {
    components: { ProductProgress },
    props: ['products'],
    created() {
        if (this.products.length === 0 && !this.$parent.isEmpty) {
            // Redirect to appropriate path
            if (this.$parent.activeCollaborations.length > 0) {
                this.$router.push({ name: 'collaborations' });
            } else {
                this.$router.push({ name: 'archive' });
            }
        }
    },
}
</script>