<template>
    <div class="ui container analysis">
        <h4 class="ui dividing header">Overview</h4>

        <div class="ui stackable equal width grid">
            <div class="column">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui horizontal divider header">Top Accounts
                        <span class="longTooltip" data-tooltip="10 most active accounts (based on the number of documents published by them) from the documents that are retrieved based on the analysis settings.">
                            <i class="icon info"></i>
                        </span>
                    </h5>
                    <div v-for="d in chart_data.top_accounts.accounts" class="ui tiny label" :data-tooltip="'No. of post: '+d.count">{{ d.name }}</div>
                    <h5 class="ui horizontal divider header">Top Keywords
                        <span class="longTooltip" data-tooltip="10 most popular keywords/hashtags from the documents that are retrieved based on the analysis settings.">
                            <i class="icon info"></i>
                        </span>
                    </h5>
                    <div v-for="d in chart_data.top_hashtags.hashtags" class="ui small label" :data-tooltip="'No. of post: '+d.count">{{ d.name }}</div>
                </div>
            </div>
            <div class="six wide column">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui sub">Timeline of all results
                        <span class="longTooltip" data-tooltip="Daily number of documents that are retrieved based on the analysis settings. Use the mouse scroll wheel to zoom in and out of the chart.">
                            <i class="icon info"></i>
                        </span>
                    </h5>
                    <div id="chart_overall_timeline_export" class="chart-export"></div>
                    <div id="chart_overall_timeline" style="height: 230px;"></div>
                </div>
            </div>
            <div class="six wide column">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui sub">Timeline of mentions for Keywords/Hashtags
                        <span class="longTooltip" data-tooltip="Daily number of retrieved documents for each of the keyphrases defined in the analysis. Use the mouse scroll wheel to zoom in and out of the chart.">
                            <i class="icon info"></i>
                        </span>
                    </h5>
                    <div id="chart_timeline_export" class="chart-export"></div>
                    <div id="chart_timeline" style="height: 230px;"></div>

                </div>
            </div>
        </div>
        <template  v-if="analysis_type == 'trend'">
            <h4 class="ui dividing header">Settings-based analytics
                <span class="longTooltip" data-tooltip="Contains a number of visualisations based on the concepts/parameters pairs defined in the settings.">
                    <i class="icon info"></i>
                </span>
            </h4>

            <div class="ui orange message" v-if="chart_data.concept_timelines.data.length == 0 && chart_data.two_word_phrases.data.length == 0 && chart_data.conceptParametersFacets.length == 0">
                There are no charts available in this section. Are there any Concepts' Settings defined for this analysis?
            </div>

            <div class="ui stackable two column grid">

                <div class="column" v-for="d in chart_data.two_word_phrases.meta.labels" v-if="chart_data.two_word_phrases.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">{{ d.label }} Concept
                            <span class="longTooltip" data-tooltip="Most popular keywords for each concept defined in the settings. The number of pie charts equals the number of concepts defined.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div :id="'chart_twp_'+d.index+'_export'" class="chart-export"></div>
                        <div :id="'chart_twp_'+d.index" style="height: 250px;"></div>
                    </div>
                </div>

                <div class="column" v-show="chart_data.concept_timelines.data.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Daily timeline per concept
                            <span class="longTooltip" data-tooltip="Daily number of retrieved documents for each of the concepts used in the analysis. Use the mouse scroll wheel to zoom in and out of the chart.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div id="chart_concept_timeline_export" class="chart-export"></div>
                        <div id="chart_concept_timeline" style="height: 230px;"></div>

                    </div>
                </div>

                <div class="column" v-for="(d, k) in chart_data.conceptParametersFacets">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Μentions per parameter for the concepts
                            <span class="longTooltip" data-tooltip="For each parameter specified in the settings, show the number of results for each of the (concept,parameter value) pairs per concept.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div :id="`chart_cpf_${k}_export`" class="chart-export"></div>
                        <div :id="`chart_cpf_${k}`" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </template>
        <template  v-if="analysis_type == 'social'">
            <h4 class="ui dividing header">Market set-based analytics
                <span class="longTooltip" data-tooltip="Contains a number of visualisations based on the market sets defined in the Market Analysis Settings of your organization.">
                    <i class="icon info"></i>
                </span>
            </h4>

            <!--<div class="ui orange message" v-if="chart_data.concept_timelines.data.length == 0 && chart_data.two_word_phrases.data.length == 0 && chart_data.conceptParametersFacets.length == 0">-->
                <!--There are no charts available in this section. Are there any Concepts' Settings defined for this analysis?-->
            <!--</div>-->

            <div class="ui stackable two column grid">
                <div class="column" v-if="chart_data.brand_timelines.data.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Daily timeline per brand name
                            <span class="longTooltip" data-tooltip="Daily number of documents that are retrieved for each brand name defined in the settings. Use the mouse scroll wheel to zoom in and out of the chart.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div id="chart_brand_timelines_export" class="chart-export"></div>
                        <div id="chart_brand_timelines" style="height: 230px;"></div>
                    </div>
                </div>
                <div class="column" v-if="chart_data.product_timelines.data.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Daily timeline per product name
                            <span class="longTooltip" data-tooltip="Daily number of documents that are retrieved for each product defined in the settings. Use the mouse scroll wheel to zoom in and out of the chart.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div id="chart_product_timelines_export" class="chart-export"></div>
                        <div id="chart_product_timelines" style="height: 230px;"></div>
                    </div>
                </div>
                <div class="column" v-if="chart_data.sentiments.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Sentiment/Emotions for my brands & products
                            <span class="longTooltip" data-tooltip="This chart shows the sentiments (happy, sad or neutral) expressed in the retrieved documents for the brands and products defined in the Market Set.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div id="chart_sentiments_export" class="chart-export"></div>
                        <div id="chart_sentiments" style="height: 230px;"></div>
                    </div>
                </div>
                <div class="column" v-for="d in chart_data.one_word_phrases_brands.meta.labels"
                     v-if="chart_data.one_word_phrases_brands.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">One word related topics for brand: {{ d.label }}
                            <span class="longTooltip" data-tooltip="15 most commonly found one-word phrases in the documents that are retrieved based on the analysis settings per product.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div style="min-height: 250px;">
                            <div :id="'chart_owpb_'+d.index" class="ui three stackable cards">
                                <div v-for="(w,k) in chart_data.one_word_phrases_brands.data[d.index]" class="card" :data-tooltip="'No. of post: '+w.doc_count">
                                    <div class="content">{{ w.key }}</div>
                                    <div class="ui bottom attached progress">
                                        <div class="bar" :style="{ background: getColor(k, chart_data.one_word_phrases_brands.data[d.index].length), width: w.doc_count/d.doc_count*100+'%'}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column" v-for="d in chart_data.one_word_phrases_products.meta.labels"
                     v-if="chart_data.one_word_phrases_products.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">One word related topics for product: {{ d.label }}
                            <span class="longTooltip" data-tooltip="15 most commonly found one-word phrases in the documents that are retrieved based on the analysis settings per product.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div style="min-height: 250px;">
                            <div :id="'chart_owpp_'+d.index" class="ui three stackable cards">
                                <div v-for="(w,k) in chart_data.one_word_phrases_products.data[d.index]" class="card" :data-tooltip="'No. of post: '+w.doc_count">
                                    <div class="content">{{ w.key }}</div>
                                    <div class="ui bottom attached progress">
                                        <div class="bar" :style="{ background: getColor(k, chart_data.one_word_phrases_products.data[d.index].length), width: w.doc_count/d.doc_count*100+'%'}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column" v-for="d in chart_data.two_word_phrases_brands.meta.labels"
                     v-if="chart_data.two_word_phrases_brands.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Two word related topics for brand: {{ d.label }}
                            <span class="longTooltip" data-tooltip="15 most commonly found two-word phrases in the documents that are retrieved based on the analysis settings per brand.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div style="min-height: 250px;">
                            <div :id="'chart_twpb_'+d.index" class="ui three stackable cards">
                                <div v-for="(w,k) in chart_data.two_word_phrases_brands.data[d.index]" class="card" :data-tooltip="'No. of post: '+w.doc_count">
                                    <div class="content">{{ w.key }}</div>
                                    <div class="ui bottom attached progress">
                                        <div class="bar" :style="{ background: getColor(k, chart_data.two_word_phrases_brands.data[d.index].length), width: w.doc_count/d.doc_count*100+'%'}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column" v-for="d in chart_data.two_word_phrases_products.meta.labels"
                     v-if="chart_data.two_word_phrases_products.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Two word related topics for product: {{ d.label }}
                            <span class="longTooltip" data-tooltip="15 most commonly found two-word phrases in the documents that are retrieved based on the analysis settings per product.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div style="min-height: 250px;">
                            <div :id="'chart_twpp_'+d.index" class="ui three stackable cards">
                                <div v-for="(w,k) in chart_data.two_word_phrases_products.data[d.index]" class="card" :data-tooltip="'No. of post: '+w.doc_count">
                                    <div class="content">{{ w.key }}</div>
                                    <div class="ui bottom attached progress">
                                        <div class="bar" :style="{ background: getColor(k, chart_data.two_word_phrases_products.data[d.index].length), width: w.doc_count/d.doc_count*100+'%'}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column" v-for="d in chart_data.three_word_phrases_brands.meta.labels"
                     v-if="chart_data.three_word_phrases_brands.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Three word related topics for brand: {{ d.label }}
                            <span class="longTooltip" data-tooltip="15 most commonly found three-word phrases in the documents that are retrieved based on the analysis settings per brand.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div style="min-height: 250px;">
                            <div :id="'chart_thwpb_'+d.index" class="ui three stackable cards">
                                <div v-for="(w,k) in chart_data.three_word_phrases_brands.data[d.index]" class="card" :data-tooltip="'No. of post: '+w.doc_count">
                                    <div class="content">{{ w.key }}</div>
                                    <div class="ui bottom attached progress">
                                        <div class="bar" :style="{ background: getColor(k, chart_data.three_word_phrases_brands.data[d.index].length), width: w.doc_count/d.doc_count*100+'%'}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column" v-for="d in chart_data.three_word_phrases_products.meta.labels"
                     v-if="chart_data.three_word_phrases_products.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Three word related topics for product: {{ d.label }}
                            <span class="longTooltip" data-tooltip="15 most commonly found three-word phrases in the documents that are retrieved based on the analysis settings per product.">
                                <i class="icon info"></i>
                            </span>
                        </h5>
                        <div style="min-height: 250px;">
                            <div :id="'chart_thwpp_'+d.index" class="ui three stackable cards">
                                <div v-for="(w,k) in chart_data.three_word_phrases_products.data[d.index]" class="card" :data-tooltip="'No. of post: '+w.doc_count">
                                    <div class="content">{{ w.key }}</div>
                                    <div class="ui bottom attached progress">
                                        <div class="bar" :style="{ background: getColor(k, chart_data.three_word_phrases_products.data[d.index].length), width: w.doc_count/d.doc_count*100+'%'}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import ColorService from '../service/colorService'

    export default {
        props: ['analysis', 'analysis_type', 'analysis_id', 'chart_data'],
        created () {
            var exportFields = ["key", "doc_count"];
            AmCharts.makeChart("chart_overall_timeline",
                {
                    "path": "/vendor/amcharts/",
                    "type": "serial",
                    "marginRight": 20,
                    "marginLeft": 0,
                    "valueAxes": [ {
                        "position": "left",
                        "title": "No. of posts",
                        "titleBold": false
                    }],
                    "categoryField": "key",
                    "categoryAxis": {
                        "parseDates": true,
                        "title": "Time",
                        "titleBold": false
                    },
                    "graphs": [
                        {
                            "type": "line",
                            "balloonText": "<b>[[value]]</b>",
                            "title": "No. of posts",
                            "valueField": "doc_count",
                            "fillAlphas": 0.2,
                            "bullet": "round",
                            "hideBulletsCount": 50,
                            "bulletSize": 4,
                            "lineColor": "#CD2929"
                        }
                    ],
                    "chartScrollbar": {
                        "oppositeAxis":false,
                    },
                    "mouseWheelZoomEnabled":true,
                    "chartCursor": {
                        "pan": true,
                        "valueLineEnabled": true,
                        "valueLineBalloonEnabled": true,
                        "cursorAlpha":1,
                        "cursorColor":"#258cbb",
                        "valueLineAlpha":0.2,
                        "valueZoomable":true
                    },
                    "creditsPosition": "top-left",
                    "dataProvider": this.chart_data.overall_timeline.timeline,
                    "export": {
                        "enabled": true,
                        "exportTitles": true,
                        "exportFields": exportFields,
                        "divId": "chart_overall_timeline" + "_export"
                    }
                }
            );

            var graphs = [];
            exportFields = ["key"];
            for (var i=0;i<this.chart_data.timelines.meta.graphs;i++){
                graphs.push({
                    "type": "line",
                    "title": this.chart_data.timelines.meta.labels[i].label,
                    "valueField": this.chart_data.timelines.meta.valueFieldPrefix + i,
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    "fillAlphas": 0.2,
                    "bullet": "round",
                    "hideBulletsCount": 50,
                    "bulletSize": 4,
                    "lineColor":  ColorService.getColor(i, this.chart_data.timelines.meta.graphs)
                });
                exportFields.push(this.chart_data.timelines.meta.valueFieldPrefix + i);
            }
            AmCharts.makeChart("chart_timeline",
                {
                    "path": "/vendor/amcharts/",
                    "type": "serial",
                    "marginRight": 20,
                    "marginLeft": 0,
                    "categoryField": "key",
                    "categoryAxis": {
                        "parseDates": true,
                        "title": "Time",
                        "titleBold": false
                    },
                    "graphs": graphs,
                    "valueAxes": [ {
                        "position": "left",
                        "title": "No. of posts",
                        "titleBold": false
                    }],
                    "chartScrollbar": {
                        "oppositeAxis":false,
                    },
                    "mouseWheelZoomEnabled":true,
                    "chartCursor": {
                        "pan": true,
                        "valueLineEnabled": true,
                        "valueLineBalloonEnabled": true,
                        "cursorAlpha":1,
                        "cursorColor":"#258cbb",
                        "valueLineAlpha":0.2,
                        "valueZoomable":true
                    },
                    "creditsPosition": "top-left",
                    "dataProvider": this.chart_data.timelines.data,
                    "export": {
                        "enabled": true,
                        "exportTitles": true,
                        "exportFields": exportFields,
                        "divId": "chart_timeline" + "_export"
                    }
                }
            );

            if (this.analysis_type == 'trend') {
                for (var index = 0; index < this.chart_data.two_word_phrases.meta.graphs; index++) {
                    if (this.chart_data.two_word_phrases.data[index].length === 0){
                        continue;
                    }
                    exportFields = ["key", "doc_count"];
                    AmCharts.makeChart('chart_twp_' + index,
                        {
                            "path": "/vendor/amcharts/",
                            "type": "pie",
                            "valueField": "doc_count",
                            "titleField": "key",
//                            "labelsEnabled": false,
//                            "hideLabelsPercent": 2,
                            "marginBottom": 0,
                            "marginTop": 0,
                            "labelText": "[[title]]",
                            "colors": ColorService.getColor(-1, this.chart_data.two_word_phrases.data[index].length),
                            "sequencedAnimation": false,
                            "creditsPosition": "top-right",
                            "dataProvider": this.chart_data.two_word_phrases.data[index],
                            "export": {
                                "enabled": true,
                                "exportTitles": true,
                                "exportFields": exportFields,
                                "columnNames": {
                                    "key": "Concept",
                                    "doc_count": "No. of posts"
                                },
                                "divId": 'chart_twp_' + index + "_export"
                            }
                        });
                }

                graphs = [];
                exportFields = ["key"];
                for (var i=0;i<this.chart_data.concept_timelines.meta.graphs;i++){
                    graphs.push({
                        "type": "line",
                        "title": this.chart_data.concept_timelines.meta.labels[i].label,
                        "valueField": this.chart_data.concept_timelines.meta.valueFieldPrefix + i,
                        "balloonText": "[[title]]: <b>[[value]]</b>",
                        "fillAlphas": 0.2,
                        "bullet": "round",
                        "hideBulletsCount": 50,
                        "bulletSize": 4,
                        "lineColor":  ColorService.getColor(i, this.chart_data.concept_timelines.meta.graphs)
                    });
                    exportFields.push(this.chart_data.concept_timelines.meta.valueFieldPrefix + i);
                }
                AmCharts.makeChart("chart_concept_timeline",
                    {
                        "path": "/vendor/amcharts/",
                        "type": "serial",
                        "marginRight": 20,
                        "marginLeft": 0,
                        "categoryField": "key",
                        "categoryAxis": {
                            "parseDates": true,
                            "title": "Time",
                            "titleBold": false
                        },
                        "graphs": graphs,
                        "valueAxes": [ {
                            "position": "left",
                            "title": "No. of posts",
                            "titleBold": false
                        }],
                        "chartScrollbar": {
                            "oppositeAxis":false,
                        },
                        "mouseWheelZoomEnabled":true,
                        "chartCursor": {
                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "valueLineAlpha":0.2,
                            "valueZoomable":true
                        },
                        "creditsPosition": "top-right",
                        "dataProvider": this.chart_data.concept_timelines.data,
                        "export": {
                            "enabled": true,
                            "exportTitles": true,
                            "exportFields": exportFields,
                            "divId": "chart_concept_timeline" + "_export"
                        }
                    }
                );

                for (var j = 0; j < this.chart_data.conceptParametersFacets.length; j++) {
                    var graphs = [];
                    exportFields = ["key"];
                    for (var i=0;i<this.chart_data.conceptParametersFacets[j].meta.graphs;i++){
                        graphs.push({
                            "type": "column",
                            "title": this.chart_data.conceptParametersFacets[j].meta.labels[i].label,
                            "valueField": this.chart_data.conceptParametersFacets[j].meta.valueFieldPrefix + i,
                            "balloonText": "[[title]]: <b>[[value]]</b>",
                            "fillAlphas": 1,
                            "lineColor": ColorService.getColor(i, this.chart_data.conceptParametersFacets[j].meta.graphs)
                        });
                        exportFields.push(this.chart_data.conceptParametersFacets[j].meta.valueFieldPrefix + i);
                    }
                    AmCharts.makeChart('chart_cpf_'+j,
                        {
                            "path": "/vendor/amcharts/",
                            "type": "serial",
                            "marginRight": 20,
                            "marginLeft": 0,
                            "categoryField": "key",
                            "categoryAxis": {
                                "parseDates": false,
                                "title": this.chart_data.conceptParametersFacets[j].meta.parameter.name,
                                "titleBold": false
                            },
                            "graphs": graphs,
                            "valueAxes": [ {
                                "position": "left",
                                "title": "No. of posts",
                                "titleBold": false
                            }],
//                        "chartScrollbar": {
//                            "oppositeAxis":false,
//                        },
//                        "mouseWheelZoomEnabled":true,
                            "chartCursor": {
//                            "pan": true,
                                "valueLineEnabled": true,
                                "valueLineBalloonEnabled": true,
                                "cursorAlpha":1,
                                "cursorColor":"#258cbb",
                                "valueLineAlpha":0.2,
//                            "valueZoomable":true
                            },
                            "creditsPosition": "top-right",
                            "dataProvider": this.chart_data.conceptParametersFacets[j].data,
                            "export": {
                                "enabled": true,
                                "exportTitles": true,
                                "exportFields": exportFields,
                                "divId": 'chart_cpf_' + j + "_export"
                            }
                        }
                    );
                }
            } else if (this.analysis_type == 'social') {
                var chart = this.chart_data.brand_timelines;
                graphs = [];
                exportFields = ["key"];
                for (var i=0;i<chart.meta.graphs;i++){
                    graphs.push({
                        "type": "line",
                        "title": chart.meta.labels[i].label,
                        "valueField": chart.meta.valueFieldPrefix + i,
                        "balloonText": "[[title]]: <b>[[value]]</b>",
                        "fillAlphas": 0.2,
                        "bullet": "round",
                        "hideBulletsCount": 50,
                        "bulletSize": 4,
                        "lineColor": ColorService.getColor(i, chart.meta.graphs)
                    });
                    exportFields.push(chart.meta.valueFieldPrefix + i);
                }
                AmCharts.makeChart("chart_brand_timelines",
                    {
                        "path": "/vendor/amcharts/",
                        "type": "serial",
                        "marginRight": 20,
                        "marginLeft": 0,
                        "categoryField": "key",
                        "categoryAxis": {
                            "parseDates": true,
                            "title": "Time",
                            "titleBold": false
                        },
                        "graphs": graphs,
                        "valueAxes": [ {
                            "position": "left",
                            "title": "No. of posts",
                            "titleBold": false
                        }],
                        "chartScrollbar": {
                            "oppositeAxis":false,
                        },
                        "mouseWheelZoomEnabled":true,
                        "chartCursor": {
                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "valueLineAlpha":0.2,
                            "valueZoomable":true
                        },
                        "creditsPosition": "top-left",
                        "dataProvider": chart.data,
                        "export": {
                            "enabled": true,
                            "exportTitles": true,
                            "exportFields": exportFields,
                            "divId": "chart_brand_timelines" + "_export"
                        }
                    }
                );

                chart = this.chart_data.product_timelines;
                graphs = [];
                exportFields = ["key"];
                for (var i=0;i<chart.meta.graphs;i++){
                    graphs.push({
                        "type": "line",
                        "title": chart.meta.labels[i].label,
                        "valueField": chart.meta.valueFieldPrefix + i,
                        "balloonText": "[[title]]: <b>[[value]]</b>",
                        "fillAlphas": 0.2,
                        "bullet": "round",
                        "hideBulletsCount": 50,
                        "bulletSize": 4,
                        "lineColor": ColorService.getColor(i, chart.meta.graphs)
                    });
                    exportFields.push(chart.meta.valueFieldPrefix + i);
                }
                AmCharts.makeChart("chart_product_timelines",
                    {
                        "path": "/vendor/amcharts/",
                        "type": "serial",
                        "marginRight": 20,
                        "marginLeft": 0,
                        "categoryField": "key",
                        "categoryAxis": {
                            "parseDates": true,
                            "title": "Time",
                            "titleBold": false
                        },
                        "graphs": graphs,
                        "valueAxes": [ {
                            "position": "left",
                            "title": "No. of posts",
                            "titleBold": false
                        }],
                        "chartScrollbar": {
                            "oppositeAxis":false,
                        },
                        "mouseWheelZoomEnabled":true,
                        "chartCursor": {
                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "valueLineAlpha":0.2,
                            "valueZoomable":true
                        },
                        "creditsPosition": "top-left",
                        "dataProvider": chart.data,
                        "export": {
                            "enabled": true,
                            "exportTitles": true,
                            "exportFields": exportFields,
                            "divId": "chart_product_timelines" + "_export"
                        }
                    }
                );

                chart = this.chart_data.sentiments;
                let colors = {
                    'negative': '#cc2929',
                    'neutral': '#999999',
                    'positive': '#04D215'
                };
                for (var i=0;i<chart.length;i++) {
                    chart[i].color = colors[chart[i].name];
                }
                graphs = [];
                graphs.push({
                    "type": "column",
                    "valueField": 'count',
                    "title": "No. of posts",
                    "balloonText": "[[category]]: <b>[[value]]</b>",
                    "fillAlphas": 1,
                    "fillColorsField": "color",
                    "lineColorField": "color"
                });
                exportFields = ["name", "count"];
                AmCharts.makeChart("chart_sentiments",
                    {
                        "path": "/vendor/amcharts/",
                        "type": "serial",
                        "marginRight": 20,
                        "marginLeft": 0,
                        "categoryField": "name",
                        "title": "Sentiment",
                        "categoryAxis": {
                            "parseDates": false,
                            "title": "Sentiment",
                            "titleBold": false
                        },
                        "graphs": graphs,
                        "valueAxes": [ {
                            "position": "left",
                            "title": "No. of posts",
                            "titleBold": false
                        }],
                        "chartCursor": {
//                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "valueLineAlpha":0.2,
//                            "valueZoomable":true
                        },
                        "creditsPosition": "top-right",
                        "dataProvider": chart,
                        "export": {
                            "enabled": true,
                            "exportTitles": true,
                            "exportFields": exportFields,
                            "divId": "chart_sentiments" + "_export"
                        }
                    }
                );

            }
        },
        methods: {
            getColor: ColorService.getColor
        }
    }
</script>
<style>
    .ui.container.analysis .ui.progress.attached .bar {
        min-width: inherit;
    }

    .ui.segment .chart-export > .amcharts-export-menu,
    .ui.segment .chart-export > .amcharts-export-menu.active {
        opacity: 0.3;
    }
    .ui.segment:hover .chart-export > .amcharts-export-menu,
    .ui.segment:hover .chart-export > .amcharts-export-menu.active {
        opacity: 0.6;
    }
    .ui.segment:hover .chart-export > .amcharts-export-menu:hover,
    .ui.segment:hover .chart-export > .amcharts-export-menu:hover.active {
        opacity: 0.9;
    }
</style>