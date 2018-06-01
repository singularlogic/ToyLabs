<template>
    <div class="ui divided items">
        <div class="item" v-for="(result, index) of results">
            <div class="content">
                <h3 class="ui header">
                    <div class="ui horizontal label" :style="{ backgroundColor: scoreColor(result.score) }">{{ result.score | percentage }}</div>
                    <a :href="`/organization/${result.id}`" target="_BLANK">{{ result.name }}</a>
                    <span class="rating" v-if="rating(result)">(Avg. Rating: {{ rating(result) }}/5.00)</span>
                    <span class="rating" v-else>(Not rated yet)</span>
                </h3>
                <div class="ui right floated tiny blue label" v-for="cert in result.certifications" :class="{ basic: !cert.pivot.is_verified }">
                    {{ cert.name }}
                </div>
                <div class="meta">
                    <i class="green check circle icon" v-if="result.is_verified"></i>
                    {{ result.organization_type.name }}
                    <i class="flag" :class="flag" v-for="flag of flags(result)"></i>
                </div>
                <div class="description">{{ result.description | trim(500) }}</div>
                <div class="extra">
                    <button class="ui right floated positive mini button" type="button" @click="add(result.id)" :disabled="canAdd">Add</button>
                    <router-link :to="`/${$parent.type}/${$parent.id}/collaborate/contact/${result.id}`" class="ui right floated orange mini button">
                        Contact
                    </router-link>

                    <div class="ui tiny basic label" :class="{ red: !rule.matched, green: rule.matched }" v-for="rule of rules(result)">
                        <i class="checkmark icon" v-if="rule.matched"></i>
                        <i class="remove icon" v-if="!rule.matched"></i>
                        {{ rule.name }}
                    </div>
                </div>
            </div>
        </div>
        <div class="ui warning message" v-if="results.length == 0">
            No results found. Change your search and try again.
        </div>
    </div>
</template>

<script>
import numeral from 'numeral';

export default {
    props: ['results', 'query', 'canAdd'],
    methods: {
        flags(org) {
            let flags = [];
            if (org.country !== null) {
                flags.push(org.country.sortcode.toLowerCase());
            }
            org.facilities.reduce((arr, obj) => {
                if (arr.indexOf(obj.country.sortcode.toLowerCase()) == -1) {
                    arr.push(obj.country.sortcode.toLowerCase());
                }
                return arr;
            }, flags);
            return flags;
        },
        rules(org) {
            let rules = [];
            this.$parent.competencies.reduce((arr, obj) => {
                if (this.query.competencies.indexOf(obj.id) >= 0) {
                    let matched = false;
                    if (org.competencies) {
                        matched = !!org.competencies.find(o => o.id === obj.id);
                    }
                    arr.push({ id: obj.id, name: obj.name, matched: matched });
                }

                return arr;
            }, rules);
            return rules;
        },
        scoreColor(value) {
            const hue = (value*120).toString(10);
            return ['hsl(', hue, ',100%,50%)'].join('');
        },
        add(id) {
            this.$emit('add', id);
        },
        rating(org) {
            if (org.rating1 !== null && org.rating2 !== null && org.rating3 !== null) {
                return numeral((parseFloat(org.rating1) + parseFloat(org.rating2) + parseFloat(org.rating3)) / 3).format('0.00');
            }

            return false;
        },
    },
    filters: {
        percentage(value) {
            return numeral(value).format('0%');
        },
        trim(value, length) {
            if (value === null) return 'No description';
            return value.substring(0, length) + '...';
        },
    },
};
</script>

<style>
.header .rating {
    color: rgba(0, 0, 0, 0.6);
    font-size: 1rem;
}
</style>