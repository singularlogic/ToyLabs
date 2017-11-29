<template>
    <div id="searchForm">
        <div class="ui segment">
            <div class="ui basic segment">
                <div class="ui fluid category search" ref="textSearch">
                    <div class="ui fluid icon input">
                        <input class="prompt" type="text" placeholder="Search by name..." />
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
            </div>

            <div class="ui horizontal divider">Advanced Search</div>

            <div class="ui fluid form">
                <div class="inline fields">
                    <label for="role">I am looking for a:</label>
                    <div class="field" v-for="role of roles">
                        <div class="ui radio checkbox">
                            <input type="radio" :value="role.id" v-model="query.role" />
                            <label>{{ role.name }}</label>
                        </div>
                    </div>
                </div>
                <div class="grouped fields">
                    <label for="expertise">That has <em style="underline">any</em> the following competencies:</label>
                    <div class="ui three column stackable grid basic segment" style="margin-top: 0;">
                        <div class="field column" v-for="competency of competencies">
                            <div class="ui checkbox">
                                <input type="checkbox" :value="competency.id" v-model="query.competencies" />
                                <label>{{ competency.name }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inline fields">
                    <label for="scale">Scale of production needed:</label>
                    <div class="field" v-for="scale of scales">
                        <div class="ui radio checkbox">
                            <input type="radio" :value="scale.id" v-model="query.scale" />
                            <label>{{ scale.name }}</label>
                        </div>
                    </div>
                </div>
                <div class="inline fields">
                    <label for="payment">I will pay using:</label>
                    <div class="field" v-for="type of paymentTypes">
                        <div class="ui radio checkbox">
                            <input type="radio" :value="type.id" v-model="query.paymentType" />
                            <label>{{ type.name }}</label>
                        </div>
                    </div>
                </div>
                <div class="inline fields">
                    <label for="delay">Payment will be:</label>
                    <div class="field" v-for="d of delays">
                        <div class="ui radio checkbox">
                            <input type="radio" :value="d.days" v-model="query.paymentDelay" />
                            <label>{{ d.name }}</label>
                        </div>
                    </div>
                </div>

                <div class="ui divider"></div>
                <button class="ui default tiny submit button" type="button" @click="clear">Clear</button>
                <button class="ui primary right floated tiny submit button" type="button" @click="search">Search</button>
            </div>
        </div>


        <h2 class="ui dividing header" v-if="searchComplete">Search results</h2>

        <div class="ui active centered inline loader" v-if="loading"></div>

        <search-results
            :results="results"
            :query="activeQuery"
            v-if="searchComplete"
        ></search-results>
    </div>
</template>

<script>
import SearchResults from './SearchResults';
import _ from 'lodash';

export default {
    props: ['roles', 'competencies', 'paymentTypes', 'back', 'type', 'id'],
    components: { SearchResults },
    data() {
        return {
            loading: false,
            searchComplete: false,
            nameLoading: false,
            query: {},
            activeQuery: {},
            results: [],
            scales: [
                { id: 'prototype', name: 'Prototyping Only' },
                { id: 'small', name: 'Small' },
                { id: 'medium', name: 'Medium' },
                { id: 'large', name: 'Large' },
                { id: 'xlarge', name: 'Extra Large' },
            ],
            delays: [
                { days: 0, name: 'On Delivery' },
                { days: 15, name: 'In 15 Days' },
                { days: 30, name: 'In 30 Days' },
                { days: 45, name: 'In 45 Days' },
                { days: 60, name: 'In 60 Days' },
                { days: 90, name: 'In 90 Days' },
            ],
        };
    },
    created() {
        this.clear();
    },
    mounted() {
        $(this.$refs.textSearch).search({
            minCharacters: 3,
            apiSettings: {
                url: `/organizations/search?query={query}&type=${this.type}&id=${this.id}`,
            },
        });
    },
    methods: {
        clear() {
            this.query = {
                product_id: this.back.id,
                role: null,
                competencies: [],
                scale: null,
                paymentType: null,
                paymentDelay: null,
            };
            this.searchComplete = false;
        },
        search() {
            this.loading = true;
            this.searchComplete = false;

            axios.post('/partner/search', this.query).then(res => {
                if (res.status === 200) {
                    this.activeQuery = _.cloneDeep(this.query);
                    this.results = res.data;
                    this.loading = false;
                    this.searchComplete = true;
                }
            });
        },
    }
};
</script>