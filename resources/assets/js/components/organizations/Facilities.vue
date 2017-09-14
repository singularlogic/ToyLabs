<template>
    <div class="ui bottom attached tab">
        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <th class="four wide">Name</th>
                    <th class="five wide">Country</th>
                    <th class="three wide">State</th>
                    <th class="three">City</th>
                    <th class="one wide"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="facilities.length == 0">
                    <td colspan="5" class="center aligned">No facilities added</td>
                </tr>
                <tr v-for="facility in facilities">
                    <td>{{ facility.name }}</td>
                    <td>{{ facility.country.name }}</td>
                    <td>{{ facility.state.name }}</td>
                    <td>{{ facility.city.name }}</td>
                    <td class="collapsing">
                        <button type="button" class="ui mini red icon button" @click="removeFacility(facility)"><i class="trash icon"></i></button>
                    </td>
                </tr>
            </tbody>
            <tfoot v-if="!insertMode">
                <th colspan="5">
                    <div class="ui right floated small primary labeled icon button" @click="addFacility()">
                        <i class="marker icon"></i> Add Facility
                    </div>
                </th>
            </tfoot>
            <tfoot v-if="insertMode">
                <tr>
                    <th><input type="text" v-model="newFacility.name" /></th>
                    <th>
                        <select class="ui search dropdown" name="country_id" v-model="newFacility.country" @change="countryChanged()">
                            <option value="">Select Country</option>
                            <option v-for="c in countries" v-bind:value="c">{{ c.name }}</option>
                        </select>
                    </th>
                    <th>
                        <select class="ui search dropdown" name="state_id" v-model="newFacility.state" @change="stateChanged()" v-if="states.length > 0">
                            <option value="">Select State</option>
                            <option v-for="s in states" v-bind:value="s">{{ s.name }}</option>
                        </select>
                    </th>
                    <th>
                        <select class="ui search dropdown" name="city_id" v-model="newFacility.city" v-if="cities.length > 0">
                            <option value="">Select City</option>
                            <option v-for="c in cities" v-bind:value="c">{{ c.name }}</option>
                        </select>
                    </th>
                    <th class="collapsing">
                        <button type="button" class="ui mini basic green icon button" @click="save()" :disabled="!isValid">
                            <i class="checkmark icon"></i>
                        </button>
                        <button type="button" class="ui mini basic red icon button" @click="cancel()"><i class="remove icon"></i></button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['facilities', 'countries'],
        data() {
            return {
                newFacility: {},
                states: [],
                cities: [],
                insertMode: false,
            };
        },
        computed: {
            isValid() {
                return /\S/.test(this.newFacility.name) && ~this.newFacility.city.id;
            },
        },
        methods: {
            addFacility() {
                this.newFacility = {
                    name: '',
                    country: {
                        id: -1,
                    },
                    state: {
                        id: -1,
                    },
                    city: {
                        id: -1,
                    }
                };
                this.insertMode = true;
            },
            removeFacility(facility) {
                this.$emit('remove', facility);
            },
            save() {
                this.$emit('add', this.newFacility);
                this.newFacility = {};
                this.insertMode = false;
            },
            cancel() {
                this.newFacility = {};
                this.insertMode = false;
            },
            countryChanged() {
                this.states.splice(0, this.states.length);
                axios.get(`/api/states/${this.newFacility.country.id}`).then((response) => {
                    this.states = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },
            stateChanged() {
                this.cities.splice(0, this.cities.length);
                axios.get(`/api/cities/${this.newFacility.state.id}`).then((response) => {
                    this.cities = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },
        }
    }
</script>