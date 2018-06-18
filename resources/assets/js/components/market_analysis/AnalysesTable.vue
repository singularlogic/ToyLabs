<template>
    <div class="field">
        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <th class="two wide">Type</th>
                    <th class="ten wide">Name</th>
                    <th class="two wide">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="analyses.length == 0">
                    <td colspan="5" class="center aligned">No analyses created yet</td>
                </tr>
                <tr v-for="analysis in analyses">
                    <td>{{ analysisType(analysis) }}</td>
                    <td>
                        <a :href="analysisUrl(analysis)">
                            {{ analysis.name }}
                        </a>
                    </td>
                    <td class="collapsing">
                        <div class="ui tiny basic icon buttons">
                            <a class="ui button" :href="analysisUrl(analysis, '/edit')" data-tooltip="Edit Analysis">
                                <i class="pencil icon"></i>
                            </a>
                            <a class="ui button" :href="analysisUrl(analysis)" data-tooltip="View Analysis">
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
                    <a :href="`/product/${product_id}/marketanalysis/social`" class="ui right floated small primary labeled icon button"
                       v-bind:class="[ enabled_social ? '' : 'disabled']">
                        <i class="marker icon"></i> Create Social Feedback Analysis
                    </a>
                    <div v-if="!enabled_social" class="longTooltip" style="float: right" data-tooltip="Social Feedback Analysis is only available for products owned by organizations with configured Market Analysis Settings">
                        <i class="circular info circle icon"></i>
                    </div>
                </th>
            </tfoot>
        </table>
    </div>

</template>

<script>
    export default {
        props: ['analyses', 'product_id', 'enabled_social'],
        methods: {
            analysisUrl(analysis, path) {
                path = path || '/';
                switch (analysis.type) {
                    case 'social':
                        return `/social/${analysis.id}${path}`;
                        break;
                    case 'trend':
                        return `/analysis/${analysis.id}${path}`;
                        break;
                    default:
                        return "#";
                        break;
                }
            },
            analysisType(analysis) {
                switch (analysis.type) {
                    case 'social':
                        return `Social Feedback`;
                        break;
                    case 'trend':
                        return `Market Trend`;
                        break;
                    default:
                        return "";
                        break;
                }
            }
        }
    }
</script>