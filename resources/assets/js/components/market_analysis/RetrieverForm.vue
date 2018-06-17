<template>
    <div>
        <div class="field">
            <label>Keywords</label>
            <keywords-field
                    v-model="value.keyphrases_list"
                    v-bind:readonly="readonly"
            ></keywords-field>
            <small class="helper">
                Enter the words, phrases and hashtags to be used as search terms. Single keywords are separated with commas.
                Use ‘ for exact phrases and ! to exclude a term/phrase.
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
        </div>
        <div class="field" v-if="!nomarketset">
            <label>Market Set</label>
            <marketset-table
                    v-model="value.brands"
                    v-bind:readonly="readonly"
            ></marketset-table>
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
