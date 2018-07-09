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
                    <button type="button" class="ui mini red icon button" data-tooltip="Delete Concept" @click="removeEntry(entry)" :disabled="insertMode">
                        <i class="trash icon"></i>
                    </button>
                </td>
            </tr>
        </tbody>
        <tfoot v-if="!insertMode">
            <th colspan="5">
                <button class="ui right floated small primary labeled icon button" @click="addEntry()">
                    <i class="marker icon"></i> Add New Concept
                </button>
                <button class="ui right floated small primary labeled icon button" @click="addExistingEntry()" :disabled="isAddExistingDisabled">
                    <i class="marker icon"></i> Add Existing Concept
                </button>
            </th>
        </tfoot>
        <tfoot v-if="insertMode && !insertExisting">
            <tr>
                <th>
                    <input type="text" v-model="newEntry.name" ref="entryName" placeholder="Enter a descriptive concept name" required />
                </th>
                <th>
                    <div ref="entryValues">
                    <keywords-field
                        v-model="newEntry.values"
                        placeholder="Comma separated concept values. Prefix keyword with ! to exclude or ' for exact phrases."
                    ></keywords-field>
                    </div>
                </th>
                <th class="collapsing">
                    <button type="button" class="ui mini basic green icon button" data-tooltip="Save Concept" @click="saveEntry()" :disabled="!isEntryValid">
                        <i class="checkmark icon"></i>
                    </button>
                    <button type="button" class="ui mini basic red icon button" data-tooltip="Cancel" @click="cancelEntry()">
                        <i class="remove icon"></i>
                    </button>
                </th>
            </tr>
        </tfoot>
        <tfoot v-if="insertMode && insertExisting">
            <tr>
                <th colspan="2">
                    <select class="ui search dropdown" v-model="selectedConcept">
                        <option value="">Choose an existing Concept</option>
                        <option v-for="c in all_concepts" v-if="!c.used" :value="c.name">{{c.name}}</option>
                    </select>
                </th>
                <th class="collapsing">
                    <button type="button" class="ui mini basic green icon button" data-tooltip="Add Concept" @click="doAddExistingEntry(selectedConcept)" :disabled="!selectedConcept">
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
        props: ['value', '_available_concepts'],
        components: {
            KeywordsField
        },
        data() {
            let all_concepts = {};
            let existingConcepts = [];
            this._available_concepts.forEach((entry, index) => {
                if (this.value.find(x => x.name === entry.name)){
                    entry.used = true;
                } else {
                    entry.used = false;
                    existingConcepts.push(entry.name);
                }
                all_concepts[entry.name] = entry;
            });
            return {
                insertMode: false,
                insertExisting: false,
                selectedConcept: '',
                entries: this.value || [],
                newEntry: {},
                all_concepts: all_concepts,
                existingConcepts: existingConcepts
            };
        },
        computed: {
            isEntryValid() {
                return !!this.newEntry.name;
            },
            isAddExistingDisabled() {
                return !this.existingConcepts.length;
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
                this.insertExisting = false;
                this.insertMode = true;
            },
            addExistingEntry() {
                this.selectedConcept = '';
                this.insertExisting = true;
                this.insertMode = true;
            },
            cancelEntry() {
                this.insertExisting = false;
                this.insertMode = false;
            },
            removeEntry(entry) {
                const index = this.entries.indexOf(entry);
                if (~index) {
                    this.entries.splice(index, 1);
                }
                if (entry.id > 0) {
                    this.all_concepts[entry.name].used = false;
                    this.existingConcepts.push(entry.name);
                } else {
                    delete this.all_concepts[entry.name];
                }
                this.$emit('input', this.entries);
            },
            doAddExistingEntry(name) {
                if (!this.entryNameExists(name) && this.all_concepts[name].used){
                    $(this.$refs.entryName).popup({
                        on: 'focus',
                        html: '<i class="warning icon"></i>This is not an existing Concept.'
                    }).popup('show');
                    return false;
                }
                const index = this.existingConcepts.indexOf(name);
                if (~index) {
                    this.existingConcepts.splice(index, 1);
                }
                this.entries.push(this.all_concepts[name]);
                this.all_concepts[name].used = true;
                this.insertMode = false;
                this.$emit('input', this.entries);
            },
            saveEntry() {
                if (this.entryNameExists(this.newEntry.name)){
                    $(this.$refs.entryName).popup({
                        on: 'focus',
                        html: '<i class="warning icon"></i>This Concept Name already exists for this product. Either choose a unique name or add an existing concept.'
                    }).popup('show');
                    return false;
                }
                if (!this.newEntry.values.length){
                    $(this.$refs.entryValues).popup({
                        on: 'focus',
                        html: '<i class="warning icon"></i>You need to specify Values for this concept.'
                    }).popup('show');
                    return false;
                }
                this.entries.push(this.newEntry);
                this.all_concepts[this.newEntry.name] = this.newEntry;
                this.all_concepts[this.newEntry.name].used = true;
                this.newEntry = {};
                this.insertMode = false;
                this.$emit('input', this.entries);
            },
            entryNameExists(name) {
                return !!this.all_concepts[name];
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