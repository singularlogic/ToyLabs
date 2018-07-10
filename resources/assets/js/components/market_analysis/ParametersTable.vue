<template>
    <table class="ui celled table">
        <thead class="full-width">
            <tr>
                <th class="two wide">Name</th>
                <th class="ten wide">Values</th>
                <th class="one wide">Enabled</th>
                <th class="two wide">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="entries.length == 0">
                <td colspan="4" class="center aligned">No parameters defined yet</td>
            </tr>
            <tr v-for="(entry, index) in entries" :key="entry" :class="{ warning: index==editingIndex }">
                <td>{{ entry.name }}</td>
                <td>{{ entry.values }}</td>
                <td class="center aligned">
                    <i v-if="entry.is_enabled" class="large green checkmark icon"></i>
                </td>
                <td class="collapsing">
                    <button type="button" class="ui mini icon button" data-tooltip="Edit Parameter" @click="editEntry(entry)" :disabled="insertMode">
                        <i class="edit icon"></i>
                    </button>
                    <button type="button" class="ui mini red icon button" data-tooltip="Delete Parameter" @click="removeEntry(entry)" :disabled="insertMode">
                        <i class="trash icon"></i>
                    </button>
                </td>
            </tr>
        </tbody>
        <tfoot v-if="!insertMode">
            <th colspan="5">
                <div class="ui right floated small primary labeled icon button" @click="addEntry()">
                    <i class="marker icon"></i> Add Parameter
                </div>
            </th>
        </tfoot>
        <tfoot v-if="insertMode">
            <tr>
                <th>
                    <input type="text" v-model="newEntry.name" ref="entryName" placeholder="Enter a descriptive parameter name" required />
                </th>
                <th>
                    <input type="text" v-model="newEntry.values" placeholder="Enter comma separated parameter values" required />
                </th>
                <th class="center aligned">
                    <div class="ui checkbox" data-tooltip="Is parameter enabled?">
                        <input type="checkbox" v-model="newEntry.is_enabled">
                        <label></label>
                    </div>
                </th>
                <th class="collapsing">
                    <button type="button" class="ui mini basic green icon button" data-tooltip="Save Parameter" @click="saveEntry()" :disabled="!isEntryValid">
                        <i class="checkmark icon"></i>
                    </button>
                    <button type="button" class="ui mini basic red icon button" data-tooltip="Cancel" @click="cancelEntry()"><i class="remove icon"></i></button>
                </th>
            </tr>
        </tfoot>
    </table>
</template>

<script>
    export default {
        props: ['value'],
        data() {
            return {
                insertMode: false,
                entries: this.value || [],
                newEntry: {},
                editingIndex: -1
            };
        },
        computed: {
            isEntryValid() {
                return !!this.newEntry.values && !!this.newEntry.name;
            }
        },
        methods: {
            isEditMode() {
                return !this.insertMode
            },
            addEntry() {
                this.editingIndex = -1;
                this.newEntry = {
                    id: 0,
                    name: '',
                    values: '',
                    is_enabled: true
                };
                this.insertMode = true;
            },
            cancelEntry() {
                this.editingIndex = -1;
                this.insertMode = false;
            },
            removeEntry(entry) {
                const index = this.entries.indexOf(entry);
                if (~index) {
                    this.entries.splice(index, 1);
                }
            },
            editEntry(entry) {
                this.editingIndex = this.entries.indexOf(entry);
                this.newEntry = Object.assign({}, entry);
                this.insertMode = true;
            },
            saveEntry() {
                const index = this.entries.findIndex(x => x.name === this.newEntry.name);
                if (index >= 0 && this.editingIndex !== index){
                    $(this.$refs.entryName).popup({
                        on: 'focus',
                        html: '<i class="warning icon"></i>This Parameter Name already exists for this analysis. Choose a unique name.'
                    }).popup('show');
                    return false;
                }
                if (this.editingIndex >= 0){
                    // If the name changed, reset id so that we avoid name collision if user adds new with the old name.
                    if (this.entries[this.editingIndex].name != this.newEntry.name){
                        this.newEntry.id = 0;
                    }
                    this.entries[this.editingIndex] = this.newEntry;
                } else {
                    this.entries.push(this.newEntry);
                }
                this.editingIndex = -1;
                this.newEntry = {};
                this.insertMode = false;
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