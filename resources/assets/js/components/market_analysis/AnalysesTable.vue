<template>
    <div class="field">
        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <!--<th class="two wide">Type</th>-->
                    <th class="ten wide">Name</th>
                    <th class="two wide">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="analyses.length == 0">
                    <td colspan="5" class="center aligned">No analyses created yet</td>
                </tr>
                <tr v-for="analysis in analyses">
                    <!--<td>{{ analysis.type | capitalize }}</td>-->
                    <td>
                        <a :href="`/analysis/${analysis.id}/`">
                            {{ analysis.name }}
                        </a>
                    </td>
                    <td class="collapsing">
                        <div class="ui tiny basic icon buttons">
                            <a class="ui button" :href="`/analysis/${analysis.id}/edit`" data-tooltip="Edit Analysis">
                                <i class="pencil icon"></i>
                            </a>
                            <a class="ui button" :href="`/analysis/${analysis.id}/`" data-tooltip="View Analysis">
                                <i class="bar chart icon"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <th colspan="3">
                    <a :href="`/product/${product_id}/marketanalysis/trend`" class="ui right floated small primary labeled icon button">
                        <i class="marker icon"></i> Create Market Trend Analysis
                    </a>
                </th>
            </tfoot>
        </table>
    </div>

</template>

<script>
    export default {
        props: ['analyses', 'product_id'],
        data() {
            return {
                analysisTypes: [
                    {value:'trend',label:'Trend'},
                    {value:'social',label:'Social Feedback'}
                ]
            };
        },
        methods: {
            addAccount() {
                this.newAccount = {
                    name: '',
                    source: '',
                    is_influencer: ''
                };
                this.insertAccountMode = true;
            },
            cancelAccount() {
                this.insertAccountMode = false;
            },
            removeAccount(account) {
                this.$emit('removeAccount', account);
            },
            saveAccount() {
                this.$emit('addAccount', this.newAccount);
                this.newAccount = {};
                this.insertAccountMode = false;
            },
            createTrendAnalysis() {
                window.location = "/product/" + this.product_id + "/marketanalysis/trend";
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