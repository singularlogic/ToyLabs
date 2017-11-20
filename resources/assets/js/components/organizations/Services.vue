<template>
    <div class="ui bottom attached tab">
        <h3 class="ui header dividing">Services</h3>

        <div class="grouped fields">
            <label for="expertise">Geographical Market(s)</label>
            <div class="ui four column stackable grid basic segment">
                <div class="field column" v-for="m of markets">
                    <div class="ui checkbox">
                        <input type="checkbox" :value="m.id" v-model="services.markets" />
                        <label>{{ m.name }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="grouped fields">
            <label for="expertise">Competencies</label>
            <div class="ui three column stackable grid basic segment">
                <div class="field column" v-for="c of competencies">
                    <div class="ui checkbox">
                        <input type="checkbox" :value="c.id" v-model="services.competencies" />
                        <label>{{ c.name }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="grouped fields">
            <label for="expertise">Toy Categories</label>
            <div class="ui three column stackable grid basic segment">
                <div class="field column" v-for="c of categories">
                    <div class="ui checkbox">
                        <input type="checkbox" :value="c.id" v-model="services.expertise" />
                        <label>{{ c.title }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="inline fields">
            <label for="scale">Production Scale:</label>
            <div class="field" v-for="s of scales">
                <div class="ui radio checkbox">
                    <input type="radio" :value="s.id" v-model="services.production_scale" />
                    <label>{{ s.name }}</label>
                </div>
            </div>
        </div>

        <h3 class="ui header dividing">Pricing</h3>

        <div class="inline fields">
            <label for="payment">Ways of Payment:</label>
            <div class="field" v-for="p of paymentTypes">
                <div class="ui checkbox">
                    <input type="checkbox" :value="p.id" v-model="services.payment_types" />
                    <label>{{ p.name }}</label>
                </div>
            </div>
        </div>

        <div class="inline fields">
            <label for="payment">Paid in (delay):</label>
            <div class="field" v-for="d of delays">
                <div class="ui radio checkbox">
                    <input type="radio" :value="d.name" v-model="services.payment_in" />
                    <label>{{ d.name }}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['services', 'competencies', 'markets', 'categories', 'scales', 'paymentTypes'],
        data() {
            return {
                delays: [
                    { days: 0, name: 'On Delivery' },
                    { days: 15, name: '15 Days' },
                    { days: 30, name: '30 Days' },
                    { days: 45, name: '45 Days' },
                    { days: 60, name: '60 Days' },
                    { days: 90, name: '90 Days' },
                ],
            };
        },
    }
</script>

<style>
.ui.action.input input[type="file"] {
  display: none;
}
</style>