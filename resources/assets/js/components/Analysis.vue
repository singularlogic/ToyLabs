<template>
    <div class="ui container analysis">
        <h4 class="ui dividing header">Overview</h4>

        <div class="ui stackable equal width grid">
            <div class="column">
                <div class="ui segment" style="height: 100%;">
                    <!--<div class="ui horizontal tiny statistic">-->
                        <!--<div class="value">2</div>-->
                        <!--<div class="label">Collected posts</div>-->
                    <!--</div>-->
                    <h5 class="ui horizontal divider header">Top Accounts</h5>
                    <div v-for="d in chart_data.top_accounts.accounts" class="ui tiny label" :data-tooltip="'No. of post: '+d.count">{{ d.name }}</div>
                    <h5 class="ui horizontal divider header">Top Keywords</h5>
                    <div v-for="d in chart_data.top_hashtags.hashtags" class="ui small label" :data-tooltip="'No. of post: '+d.count">{{ d.name }}</div>
                </div>
            </div>
            <div class="six wide column">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui sub">Timeline of all results</h5>
                    <div id="chart_overall_timeline" style="height: 230px;"></div>
                </div>
            </div>
            <div class="six wide column">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui sub">Timeline of mentions for Keywords/Hashtags</h5>
                    <div id="chart_timeline" style="height: 230px;"></div>

                </div>
            </div>
        </div>
        <template  v-if="analysis_type == 'trend'">
            <h4 class="ui dividing header">Settings-based analytics</h4>

            <div class="ui orange message" v-if="chart_data.concept_timelines.data.length == 0 && chart_data.two_word_phrases.data.length == 0 && chart_data.conceptParametersFacets.length == 0">
                There are no charts available in this section. Are there any Concepts' Settings defined for this analysis?
            </div>

            <div class="ui stackable two column grid">

                <div class="column" v-for="d in chart_data.two_word_phrases.meta.labels" v-if="chart_data.two_word_phrases.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">{{ d.label }} Concept</h5>
                        <div :id="'chart_twp_'+d.index" style="height: 250px;"></div>
                    </div>
                </div>

                <div class="column" v-show="chart_data.concept_timelines.data.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Daily timeline per concept</h5>
                        <div id="chart_concept_timeline" style="height: 230px;"></div>

                    </div>
                </div>

                <div class="column" v-for="(d, k) in chart_data.conceptParametersFacets">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Μentions per parameter for the concepts</h5>
                        <div :id="`chart_cpf_${k}`" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </template>
        <template  v-if="analysis_type == 'social'">
            <h4 class="ui dividing header">Market set-based analytics</h4>

            <!--<div class="ui orange message" v-if="chart_data.concept_timelines.data.length == 0 && chart_data.two_word_phrases.data.length == 0 && chart_data.conceptParametersFacets.length == 0">-->
                <!--There are no charts available in this section. Are there any Concepts' Settings defined for this analysis?-->
            <!--</div>-->

            <div class="ui stackable two column grid">
                <div class="column" v-if="chart_data.brand_timelines.data.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Daily timeline per brand name</h5>
                        <div id="chart_brand_timelines" style="height: 230px;"></div>
                    </div>
                </div>
                <div class="column" v-if="chart_data.product_timelines.data.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Daily timeline per product name</h5>
                        <div id="chart_product_timelines" style="height: 230px;"></div>
                    </div>
                </div>
                <div class="column" v-if="chart_data.sentiments.length > 0">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">Sentiment/Emotions for my brands & products</h5>
                        <div id="chart_sentiments" style="height: 230px;"></div>
                    </div>
                </div>
                <div class="column" v-for="d in chart_data.one_word_phrases_brands.meta.labels"
                     v-if="chart_data.one_word_phrases_brands.data[d.index].length">
                    <div class="ui segment" style="height: 100%;">
                        <h5 class="ui sub">One word related topics for brand: {{ d.label }}</h5>
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
                        <h5 class="ui sub">One word related topics for product: {{ d.label }}</h5>
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
                        <h5 class="ui sub">Two word related topics for brand: {{ d.label }}</h5>
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
                        <h5 class="ui sub">Two word related topics for product: {{ d.label }}</h5>
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
                        <h5 class="ui sub">Three word related topics for brand: {{ d.label }}</h5>
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
                        <h5 class="ui sub">Three word related topics for product: {{ d.label }}</h5>
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
            AmCharts.makeChart("chart_overall_timeline",
                {
                    "path": "/images/amcharts/",
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
                    "creditsPosition": "top-right",
                    "dataProvider": this.chart_data.overall_timeline.timeline
                }
            );

            var graphs = [];
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
                })
            }
            AmCharts.makeChart("chart_timeline",
                {
                    "path": "/images/amcharts/",
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
                    "dataProvider": this.chart_data.timelines.data
                }
            );

            if (this.analysis_type == 'trend') {
                for (var index = 0; index < this.chart_data.two_word_phrases.meta.graphs; index++) {
                    if (this.chart_data.two_word_phrases.data[index].length === 0){
                        continue;
                    }
                    AmCharts.makeChart('chart_twp_' + index,
                        {
                            "path": "/images/amcharts/",
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
                            "dataProvider": this.chart_data.two_word_phrases.data[index]
                        });
                }

                graphs = [];
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
                }
                AmCharts.makeChart("chart_concept_timeline",
                    {
                        "path": "/images/amcharts/",
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
                        "dataProvider": this.chart_data.concept_timelines.data
                    }
                );

                for (var j = 0; j < this.chart_data.conceptParametersFacets.length; j++) {
                    var graphs = [];
                    for (var i=0;i<this.chart_data.conceptParametersFacets[j].meta.graphs;i++){
                        graphs.push({
                            "type": "column",
                            "title": this.chart_data.conceptParametersFacets[j].meta.labels[i].label,
                            "valueField": this.chart_data.conceptParametersFacets[j].meta.valueFieldPrefix + i,
                            "balloonText": "[[title]]: <b>[[value]]</b>",
                            "fillAlphas": 1,
                            "lineColor": ColorService.getColor(i, this.chart_data.conceptParametersFacets[j].meta.graphs)
                        })
                    }
                    AmCharts.makeChart('chart_cpf_'+j,
                        {
                            "path": "/images/amcharts/",
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
                            "dataProvider": this.chart_data.conceptParametersFacets[j].data
                        }
                    );
                }
            } else if (this.analysis_type == 'social') {
                var chart = this.chart_data.brand_timelines;
                graphs = [];
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
                    })
                }
                AmCharts.makeChart("chart_brand_timelines",
                    {
                        "path": "/images/amcharts/",
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
                        "dataProvider": chart.data
                    }
                );

                chart = this.chart_data.product_timelines;
                graphs = [];
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
                    })
                }
                AmCharts.makeChart("chart_product_timelines",
                    {
                        "path": "/images/amcharts/",
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
                        "dataProvider": chart.data
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
                    "balloonText": "[[category]]: <b>[[value]]</b>",
                    "fillAlphas": 1,
                    "fillColorsField": "color",
                    "lineColorField": "color"
                });
                AmCharts.makeChart("chart_sentiments",
                    {
                        "path": "/images/amcharts/",
                        "type": "serial",
                        "marginRight": 20,
                        "marginLeft": 0,
                        "categoryField": "name",
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
                        "dataProvider": chart
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
</style>