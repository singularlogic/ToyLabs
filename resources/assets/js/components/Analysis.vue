<template>
    <div class="ui container analysis">
        <h4 class="ui dividing header">Overview</h4>

        <div class="ui stackable equal width grid">
            <div class="column">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui horizontal divider header">Top Accounts
                        <span class="longTooltip" data-tooltip="10 most active accounts (based on the number of documents published by them) from the documents that are retrieved based on the analysis settings." data-position="bottom center">
                            <i class="icon info"></i>
                        </span>
                    </h5>
                    <div v-for="d in chart_data.top_accounts.accounts" class="ui tiny label" :data-tooltip="'No. of post: '+d.count">{{ d.name }}</div>
                    <h5 class="ui horizontal divider header">Top Keywords
                        <span class="longTooltip" data-tooltip="10 most popular keywords/hashtags from the documents that are retrieved based on the analysis settings." data-position="bottom center">
                            <i class="icon info"></i>
                        </span>
                    </h5>
                    <div v-for="d in chart_data.top_hashtags.hashtags" class="ui small label" :data-tooltip="'No. of post: '+d.count">{{ d.name }}</div>
                </div>
            </div>
            <div class="six wide column">
                <overall-timeline-chart
                        id="chart_overall_timeline"
                        :chartData="chart_data.overall_timeline"
                        chartTitle="Timeline of all results"
                        chartInfo="Daily number of documents that are retrieved based on the analysis settings. Use the mouse scroll wheel to zoom in and out of the chart."
                ></overall-timeline-chart>
            </div>
            <div class="six wide column">
                <timeline-chart
                        id="chart_timeline"
                        :chartData="chart_data.timelines"
                        chartTitle="Timeline of mentions for Keywords/Hashtags"
                        chartInfo="Daily number of retrieved documents for each of the keyphrases defined in the analysis. Use the mouse scroll wheel to zoom in and out of the chart. Keywords in parentheses correspond to non-order specific matches of all words, whereas keywords in quotation marks correspond to verbatim search matches."
                ></timeline-chart>
            </div>
        </div>
        <template  v-if="analysis_type == 'trend'">
            <h4 class="ui dividing header">Settings-based analytics
                <span class="longTooltip" data-tooltip="Contains a number of visualisations based on the concepts/parameters pairs defined in the settings." data-position="bottom center">
                    <i class="icon info"></i>
                </span>
            </h4>

            <div class="ui orange message" v-if="chart_data.concept_timelines.data.length == 0 && chart_data.two_word_phrases.data.length == 0 && chart_data.conceptParametersFacets.length == 0">
                There are no charts available in this section. Are there any Concepts' Settings defined for this analysis?
            </div>

            <div class="ui stackable two column grid">

                <div class="column" v-for="d in chart_data.two_word_phrases.meta.labels" v-if="chart_data.two_word_phrases.data[d.index].length">
                    <word-chart
                            :id="'chart_twp_'+d.index"
                            :chartData="chart_data.two_word_phrases"
                            :chartDataIndex="d.index"
                            :chartTitle="`Two word related topics for concept: ${d.label}`"
                            chartInfo="Most popular keywords for each concept defined in the settings. The number of pie charts equals the number of concepts defined."
                    ></word-chart>
                </div>

                <div class="column" v-show="chart_data.concept_timelines.data.length > 0">
                    <timeline-chart
                            id="chart_concept_timeline"
                            :chartData="chart_data.concept_timelines"
                            chartTitle="Daily timeline per concept"
                            chartInfo="Daily number of retrieved documents for each of the concepts used in the analysis. Use the mouse scroll wheel to zoom in and out of the chart."
                    ></timeline-chart>
                </div>

                <div class="column" v-for="(d, k) in chart_data.conceptParametersFacets">
                    <column-chart
                            :id="`chart_cpf_${k}`"
                            :chartData="chart_data.conceptParametersFacets[k]"
                            chartTitle="Μentions per parameter for the concepts"
                            chartInfo="For each parameter specified in the settings, show the number of results for each of the (concept, parameter value) pairs per concept."
                    ></column-chart>
                </div>
            </div>
        </template>
        <template  v-if="analysis_type == 'social'">
            <h4 class="ui dividing header">Market set-based analytics
                <span class="longTooltip" data-tooltip="Contains a number of visualisations based on the market sets defined in the Market Analysis Settings of your organization." data-position="bottom center">
                    <i class="icon info"></i>
                </span>
            </h4>

            <!--<div class="ui orange message" v-if="chart_data.concept_timelines.data.length == 0 && chart_data.two_word_phrases.data.length == 0 && chart_data.conceptParametersFacets.length == 0">-->
                <!--There are no charts available in this section. Are there any Concepts' Settings defined for this analysis?-->
            <!--</div>-->

            <div class="ui stackable two column grid">
                <div class="column" v-if="chart_data.brand_timelines.data.length > 0">
                    <timeline-chart
                            id="chart_brand_timelines"
                            :chartData="chart_data.brand_timelines"
                            chartTitle="Daily timeline per brand name"
                            chartInfo="Daily number of documents that are retrieved for each brand name defined in the settings. Use the mouse scroll wheel to zoom in and out of the chart. Keywords in parentheses correspond to non-order specific matches of all words, whereas keywords in quotation marks correspond to verbatim search matches."
                    ></timeline-chart>
                </div>
                <div class="column" v-if="chart_data.product_timelines.data.length > 0">
                    <timeline-chart
                            id="chart_product_timelines"
                            :chartData="chart_data.product_timelines"
                            chartTitle="Daily timeline per product name"
                            chartInfo="Daily number of documents that are retrieved for each product defined in the settings. Use the mouse scroll wheel to zoom in and out of the chart. Keywords in parentheses correspond to non-order specific matches of all words, whereas keywords in quotation marks correspond to verbatim search matches."
                    ></timeline-chart>
                </div>
                <div class="column" v-if="chart_data.sentiments.length > 0">
                    <sentiment-chart
                            id="chart_sentiments"
                            :chartData="chart_data.sentiments"
                            chartTitle="Sentiment/Emotions for my brands & products"
                            chartInfo="This chart shows the sentiments (happy, sad or neutral) expressed in the retrieved documents for the brands and products defined in the Market Set."
                    ></sentiment-chart>
                </div>
                <div class="column" v-for="d in chart_data.one_word_phrases_brands.meta.labels"
                     v-if="chart_data.one_word_phrases_brands.data[d.index].length">
                    <word-chart
                            :id="'chart_owpb_'+d.index"
                            :chartData="chart_data.one_word_phrases_brands"
                            :chartDataIndex="d.index"
                            :chartTitle="`One word related topics for brand: ${d.label}`"
                            chartInfo="15 most commonly found one-word phrases in the documents that are retrieved based on the analysis settings per brand."
                    ></word-chart>
                </div>
                <div class="column" v-for="d in chart_data.one_word_phrases_products.meta.labels"
                     v-if="chart_data.one_word_phrases_products.data[d.index].length">
                    <word-chart
                            :id="'chart_owpp_'+d.index"
                            :chartData="chart_data.one_word_phrases_products"
                            :chartDataIndex="d.index"
                            :chartTitle="`One word related topics for product: ${d.label}`"
                            chartInfo="15 most commonly found one-word phrases in the documents that are retrieved based on the analysis settings per product."
                    ></word-chart>
                </div>
                <div class="column" v-for="d in chart_data.two_word_phrases_brands.meta.labels"
                     v-if="chart_data.two_word_phrases_brands.data[d.index].length">
                    <word-chart
                            :id="'chart_twpb_'+d.index"
                            :chartData="chart_data.two_word_phrases_brands"
                            :chartDataIndex="d.index"
                            :chartTitle="`Two word related topics for brand: ${d.label}`"
                            chartInfo="15 most commonly found two-word phrases in the documents that are retrieved based on the analysis settings per brand."
                    ></word-chart>
                </div>
                <div class="column" v-for="d in chart_data.two_word_phrases_products.meta.labels"
                     v-if="chart_data.two_word_phrases_products.data[d.index].length">
                    <word-chart
                            :id="'chart_twpp_'+d.index"
                            :chartData="chart_data.two_word_phrases_products"
                            :chartDataIndex="d.index"
                            :chartTitle="`Two word related topics for product: ${d.label}`"
                            chartInfo="15 most commonly found two-word phrases in the documents that are retrieved based on the analysis settings per product."
                    ></word-chart>
                </div>
                <div class="column" v-for="d in chart_data.three_word_phrases_brands.meta.labels"
                     v-if="chart_data.three_word_phrases_brands.data[d.index].length">
                    <word-chart
                            :id="'chart_thwpb_'+d.index"
                            :chartData="chart_data.three_word_phrases_brands"
                            :chartDataIndex="d.index"
                            :chartTitle="`Three word related topics for brand: ${d.label}`"
                            chartInfo="15 most commonly found three-word phrases in the documents that are retrieved based on the analysis settings per brand."
                    ></word-chart>
                </div>
                <div class="column" v-for="d in chart_data.three_word_phrases_products.meta.labels"
                     v-if="chart_data.three_word_phrases_products.data[d.index].length">
                    <word-chart
                            :id="'chart_thwpp_'+d.index"
                            :chartData="chart_data.three_word_phrases_products"
                            :chartDataIndex="d.index"
                            :chartTitle="`Three word related topics for product: ${d.label}`"
                            chartInfo="15 most commonly found three-word phrases in the documents that are retrieved based on the analysis settings per product."
                    ></word-chart>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import ColorService from '../service/colorService'

    import { OverallTimelineChart, TimelineChart, ColumnChart, SentimentChart, WordChart } from './analysis_charts/';

    export default {
        props: ['analysis', 'analysis_type', 'analysis_id', 'chart_data'],
        components: {
            OverallTimelineChart,
            TimelineChart,
            ColumnChart,
            SentimentChart,
            WordChart
        }
    }
</script>
<style>
    .ui.segment .chart-export > .amcharts-export-menu,
    .ui.segment .chart-export > .amcharts-export-menu.active {
        opacity: 0.3;
    }
    .ui.segment:hover .chart-export > .amcharts-export-menu,
    .ui.segment:hover .chart-export > .amcharts-export-menu.active {
        opacity: 0.7;
    }
    .ui.segment:hover .chart-export > .amcharts-export-menu:hover,
    .ui.segment:hover .chart-export > .amcharts-export-menu:hover.active {
        opacity: 0.9;
    }
</style>