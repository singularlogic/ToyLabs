<template>
    <div class="item">
        <h3 class="ui left floated header">
            <!-- <a :href="`/product/${product.id}`">{{ product.title }}</a> -->
            {{ product.title }}
            <div class="ui label" :class="{ blue: owner !== 'Personal', orange: owner === 'Personal' }">{{ owner }}</div>
        </h3>
        <small class="ui right floated">
            <strong>Last Modified:</strong> <em>{{ product.updated_at | moment('DD.MM.YYYY') }}</em>
            <a class="ui mini blue icon button" :href="`/product/${product.id}/edit`"
                style="margin-left: 10px;" data-tooltip="Edit product..." data-inverted>
                <i class="icon edit"></i>
            </a>
        </small>

        <div class="ui hidden clearing divider"></div>

        <div class="content">
            <div class="ui five mini steps">
                <a class="step" :class="getClass('concept')" :href="`/product/${product.id}`">
                    <i class="idea icon"></i>
                    <div class="content">
                        <div class="title">Concept</div>
                        <div class="description">Basic Product Information</div>
                    </div>
                </a>
                <a class="step" :class="getClass('research')" :href="`/product/${product.id}/marketanalysis`">
                    <i class="line chart icon"></i>
                    <div class="content">
                        <div class="title">Research</div>
                        <div class="description">Market Analysis</div>
                    </div>
                </a>
                <a class="step" :class="getClass('design')" :href="`/product/${product.id}/designs`">
                    <i class="pencil icon"></i>
                    <div class="content">
                        <div class="title">Design</div>
                        <div class="description">Exploring different designs</div>
                    </div>
                </a>
                <a class="step" :class="getClass('prototype')" :href="`/product/${product.id}/prototypes`">
                    <i class="icon cube"></i>
                    <div class="content">
                        <div class="title">Prototype</div>
                        <div class="description">Testing prototypes</div>
                    </div>
                </a>
                <a class="step" :class="getClass('production')">
                    <i class="industry icon"></i>
                    <div class="content">
                        <div class="title">Production</div>
                        <div class="description">Product is released</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['product'],
    data() {
        return {};
    },
    methods: {
        valueOfStep(step) {
            switch (step) {
                case 'concept':
                    return 1;
                case 'research':
                    return 2;
                case 'design':
                    return 3;
                case 'prototype':
                    return 4;
                case 'production':
                    return 5;
            }
        },
        getClass(step) {
            if (step === this.product.status) {
                return 'active';
            }

            if (this.valueOfStep(step) < this.valueOfStep(this.product.status)) {
                return 'completed';
            }

            return 'disabled';
        },
    },
    computed: {
        owner() {
            return this.product.owner_type === 'App\\User' ? 'Personal' : this.product.owner.name;
        }
    }
}
</script>

<style>
.ui.steps {
    margin: 0!important;
}

.ui.steps .step {
    padding: 0.2em 2em!important;
}

.ui.hidden.divider {
    margin: 1px 0!important;
}
.ui.steps .step.completed .title {
    color: #4183c4;
}
</style>