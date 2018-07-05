<template>
    <div>
        <div class="field">
            <label>Keywords</label>
            <keywords-field
                    v-model="value.keyphrases_list"
                    v-bind:readonly="readonly"
            ></keywords-field>
            <small class="helper">
                Enter the words, phrases and hashtags to be used as search terms. Single keywords (grey) are separated with commas.
                Use ‘ for exact phrases (green) and ! to exclude a term/phrase (orange).
            </small>
            <div class="ui info message">
                <i class="info icon"></i>
                For example by pressing <strong>toy, cube, ‘puzzle toy, !sphere</strong> the tool will search in social media for posts that
                include the words “toy” and “cube”, the exact phrase “puzzle toy” and exclude results that include the word
                “sphere”. In other words this search will return results relevant to puzzle toys that are shaped like cubes but not like spheres.
                <br/><br/>
                In another example the user could be searching for toys that are shaped like cubes or spheres but are not puzzle toys.
                In this case the search would look like so: <strong>toy, cube, sphere, !puzzle toy</strong>
            </div>
        </div>
        <div class="field">
            <label>Accounts</label>
            <accounts-table
                    v-model="value.accounts"
                    v-bind:readonly="readonly"
            ></accounts-table>
            <div class="ui info message">
                <i class="info icon"></i>
                Provide the accounts (from Twitter or Facebook) that will be monitored for the analysis. For Twitter you need to prepend @ to the account (eg. @Toy_Labs).
            </div>
        </div>
        <div class="field" v-if="!nomarketset">
            <label>Market Set</label>
            <marketset-table
                    v-model="value.brands"
                    v-bind:readonly="readonly"
            ></marketset-table>
            <div class="ui info message">
                <i class="info icon"></i>
                Create Market Sets, i.e. combinations of brands and products (that may belong to the organisation or its competitors) upon which
                the visualisations will be built. <br/> For example a brand could be Mattel and the product could be Barbie. The visualisations will
                return related topics for both the brands and the related products. <br/> Both brand's and product's value format follows the same logic as the keywords above.
            </div>
        </div>
    </div>
</template>

<script>
    import KeywordsField from './KeywordsField.vue'
    import AccountsTable from './AccountsTable.vue'
    import MarketsetTable from './MarketsetTable.vue'
    export default {
        props: {
            value: Object,
            readonly: Boolean,
            nomarketset: Boolean
        },
        components: {
            KeywordsField,
            AccountsTable,
            MarketsetTable
        },
        data() {
            return {

            };
        },
        methods: {

        }
    }
</script>
