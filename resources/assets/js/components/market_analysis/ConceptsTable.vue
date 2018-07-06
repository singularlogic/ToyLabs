<template>
    <table class="ui celled table">
        <thead class="full-width">
            <tr>
                <th class="two wide">Name</th>
                <th class="ten wide">Values</th>
                <th class="two wide">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="entries.length == 0">
                <td colspan="3" class="center aligned">No concepts selected yet</td>
            </tr>
            <tr v-for="entry in entries" :key="entry">
                <td>{{ entry.name }}</td>
                <td><keywords-field
                        v-model="entry.values"
                        readonly
                ></keywords-field></td>
                <td class="collapsing">
                    <button type="button" class="ui mini red icon button" data-tooltip="Delete Concept" @click="removeEntry(entry)">
                        <i class="trash icon"></i>
                    </button>
                </td>
            </tr>
        </tbody>
        <tfoot v-if="!insertMode">
            <th colspan="5">
                <div class="ui right floated small primary labeled icon button" @click="addEntry()">
                    <i class="marker icon"></i> Add Concept
                </div>
            </th>
        </tfoot>
        <tfoot v-if="insertMode">
            <tr>
                <th>
                    <input type="text" v-model="newEntry.name" placeholder="Enter a descriptive concept name" required />
                </th>
                <th>
                    <keywords-field
                        v-model="newEntry.values"
                        placeholder="Comma separated concept values. Prefix keyword with ! to exclude or ' for exact phrases."
                    ></keywords-field>
                </th>
                <th class="collapsing">
                    <button type="button" class="ui mini basic green icon button" data-tooltip="Save Concept" @click="saveEntry()" :disabled="!isEntryValid">
                        <i class="checkmark icon"></i>
                    </button>
                    <button type="button" class="ui mini basic red icon button" data-tooltip="Cancel" @click="cancelEntry()"><i class="remove icon"></i></button>
                </th>
            </tr>
        </tfoot>
    </table>
</template>

<script>
    import KeywordsField from './KeywordsField.vue'
    export default {
        props: ['value'],
        components: {
            KeywordsField
        },
        data() {
            return {
                insertMode: false,
                entries: this.value || [],
                newEntry: {}
            };
        },
        computed: {
            isEntryValid() {
                return !!this.newEntry.values.length && !!this.newEntry.name;
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
                this.entries.push(this.newEntry);
                this.newEntry = {};
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