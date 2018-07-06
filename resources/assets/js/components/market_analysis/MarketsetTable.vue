<template>
    <table class="ui celled table">
        <thead class="full-width">
            <tr>
                <th class="two wide">Type</th>
                <th class="two wide">Name</th>
                <th class="ten wide">Values</th>
                <th class="two wide" v-if="!readonly">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="entries.length == 0">
                <td colspan="4" class="center aligned">No market sets defined yet</td>
            </tr>
            <template v-for="(entry, index) in entries">
            <tr v-bind:class="{ highlight: index % 2}" :key="entry">
                <td>Brand</td>
                <td>{{ entry.name }}</td>
                <td>
                    <keywords-field
                        v-model="entry.values"
                        readonly
                    ></keywords-field>
                </td>
                <td class="collapsing" v-bind:rowspan="entry.products.length + 1" v-if="!readonly">
                    <button type="button" class="ui mini red icon button" data-tooltip="Delete Concept" @click="removeEntry(entry)">
                        <i class="trash icon"></i>
                    </button>
                </td>
            </tr>
            <tr v-for="prod in entry.products" :key="prod" v-bind:class="{ highlight: index % 2}">
                <td>Product</td>
                <td>{{ prod.name }}</td>
                <td>
                    <keywords-field
                        v-model="prod.values"
                        readonly
                    ></keywords-field>
                </td>
            </tr>
            </template>
        </tbody>
        <tfoot v-if="!readonly && !insertMode">
            <th colspan="5">
                <div class="ui right floated small primary labeled icon button" @click="addEntry()">
                    <i class="marker icon"></i> Add Market Set
                </div>
            </th>
        </tfoot>
        <tfoot v-if="!readonly && insertMode">
            <tr>
                <th>Brand:</th>
                <th>
                    <input type="text" v-model="newEntry.name" placeholder="Enter a descriptive brand name" required />
                </th>
                <th>
                    <keywords-field
                        v-model="newEntry.values"
                        placeholder="Comma separated brand values. Prefix keyword with ! to exclude or ' for exact phrases."
                    ></keywords-field>
                </th>
                <th class="collapsing" rowspan="2">
                    <button type="button" class="ui mini basic green icon button" data-tooltip="Save Market Set" @click="saveEntry()" :disabled="!isEntryValid">
                        <i class="checkmark icon"></i>
                    </button>
                    <button type="button" class="ui mini basic red icon button" data-tooltip="Cancel" @click="cancelEntry()"><i class="remove icon"></i></button>
                </th>
            </tr>
            <tr>
                <th>Product:</th>
                <th>
                    <input type="text" v-model="newEntryProd.name" placeholder="Enter a descriptive product name" required />
                </th>
                <th>
                    <keywords-field
                        v-model="newEntryProd.values"
                        placeholder="Comma separated product values. Prefix keyword with ! to exclude or ' for exact phrases."
                    ></keywords-field>
                </th>
            </tr>
        </tfoot>
    </table>
</template>

<script>
    import KeywordsField from './KeywordsField.vue'
    export default {
        props: {
            value: Array,
            readonly: Boolean
        },
        components: {
            KeywordsField
        },
        data() {
            return {
                insertMode: false,
                entries: this.value || [],
                newEntry: {},
                newEntryProd: {},
            };
        },
        computed: {
            isEntryValid() {
                return !!this.newEntry.values.length && !!this.newEntry.name && !!this.newEntryProd.values.length && !!this.newEntryProd.name;
            }
        },
        methods: {
            isEditMode() {
                return !this.insertMode
            },
            addEntry() {
                this.newEntry = {
                    id: 0,
                    name: '',
                    values: [],
                    products: []
                };
                this.newEntryProd = {
                    id: 0,
                    name: '',
                    values: []
                };
                this.insertMode = true;
            },
            cancelEntry() {
                this.insertMode = false;
            },
            removeEntry(entry) {
                const index = this.entries.indexOf(entry);
                if (~index) {
                    this.entries.splice(index, 1);
                }
                this.$emit('input', this.entries);
            },
            saveEntry() {
                this.newEntry.products.push(this.newEntryProd);
                this.entries.push(this.newEntry);
                this.newEntry = {};
                this.newEntryProd = {};
                this.insertMode = false;
                this.$emit('input', this.entries);
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return '';
                value = value.toString();
                return value.charAt(0).toUpperCase() + value.slice(1);
            }
        }
    }
</script>

<style>
    .highlight {
        background-color: rgba(0,0,50,.02);
    }
</style>