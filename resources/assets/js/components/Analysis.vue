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

        <h4 class="ui dividing header">Settings-based analytics</h4>

        <div class="ui stackable two column grid">
            <div class="column" v-for="d in chart_data.two_word_phrases.meta.labels" v-if="chart_data.two_word_phrases.data[d.index].length">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui sub">{{ d.label }} Concept</h5>
                    <div :id="'chart_twp_'+d.index" style="height: 250px;"></div>
                </div>
            </div>

            <div class="column">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui sub">Daily timeline per concept</h5>
                    <div id="chart_concept_timeline" style="height: 230px;"></div>

                </div>
            </div>

            <div class="column" v-for="(d, k) in chart_data.conceptParametersFacets">
                <div class="ui segment" style="height: 100%;">
                    <h5 class="ui sub">Μentions per parameter for the concepts</h5>
                    <div :id="'chart_cpf_'+k" style="height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>



</template>

<script>

    export default {
        props: ['analysis', 'analysis_id', 'chart_data'],
        data() {
            return {

            };
        },
        created () {
            var hexColors = function(t) {
                t = parseInt(t);
                // distribute the colors evenly on
                // the hue range (the 'H' in HSV)
                var i = 350 / t;

                // hold the generated colors
                var r = [];
                var sv = 70;
                for (var x = 0; x < t; x++) {
                    // alternate the s, v for more
                    // contrast between the colors.
                    sv = sv > 90 ? 70 : sv+10;
                    var rgb = hsvToRgb(i * x, sv, sv);
                    r.push(rgbToHex(rgb[0],rgb[1],rgb[2]));
                }
                return r;
            };
            /**
             * HSV to RGB color conversion
             *
             * H runs from 0 to 360 degrees
             * S and V run from 0 to 100
             *
             * Ported from the excellent java algorithm by Eugene Vishnevsky at:
             * http://www.cs.rit.edu/~ncs/color/t_convert.html
             */
            var hsvToRgb = function(h, s, v) {
                var r, g, b;
                var i;
                var f, p, q, t;

                // Make sure our arguments stay in-range
                h = Math.max(0, Math.min(360, h));
                s = Math.max(0, Math.min(100, s));
                v = Math.max(0, Math.min(100, v));

                // We accept saturation and value arguments from 0 to 100 because that's
                // how Photoshop represents those values. Internally, however, the
                // saturation and value are calculated from a range of 0 to 1. We make
                // That conversion here.
                s /= 100;
                v /= 100;

                if (s == 0) {
                    // Achromatic (grey)
                    r = g = b = v;
                    return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
                }

                h /= 60; // sector 0 to 5
                i = Math.floor(h);
                f = h - i; // factorial part of h
                p = v * (1 - s);
                q = v * (1 - s * f);
                t = v * (1 - s * (1 - f));

                switch (i) {
                    case 0:
                        r = v;
                        g = t;
                        b = p;
                        break;

                    case 1:
                        r = q;
                        g = v;
                        b = p;
                        break;

                    case 2:
                        r = p;
                        g = v;
                        b = t;
                        break;

                    case 3:
                        r = p;
                        g = q;
                        b = v;
                        break;

                    case 4:
                        r = t;
                        g = p;
                        b = v;
                        break;

                    default: // case 5:
                        r = v;
                        g = p;
                        b = q;
                }

                return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
            };
            function componentToHex(c) {
                var hex = c.toString(16);
                return hex.length == 1 ? "0" + hex : hex;
            }
            function rgbToHex(r, g, b) {
                return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
            }

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
                    "dataProvider": this.chart_data.overall_timeline.timeline
                }
            );
            var colors = hexColors(this.chart_data.timelines.meta.graphs);
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
                    "lineColor": colors[i]
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
                    "dataProvider": this.chart_data.timelines.data
                }
            );

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
                        "labelsEnabled": false,
                        "sequencedAnimation": false,
                        "dataProvider": this.chart_data.two_word_phrases.data[index]
                    });
            }

            colors = hexColors(this.chart_data.concept_timelines.meta.graphs);
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
                    "lineColor": colors[i]
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
                    "dataProvider": this.chart_data.concept_timelines.data
                }
            );

            for (var j = 0; j < this.chart_data.conceptParametersFacets.length; j++) {
                var colors = hexColors(this.chart_data.conceptParametersFacets[j].meta.graphs);
                var graphs = [];
                for (var i=0;i<this.chart_data.conceptParametersFacets[j].meta.graphs;i++){
                    graphs.push({
                        "type": "column",
                        "title": this.chart_data.conceptParametersFacets[j].meta.labels[i].label,
                        "valueField": this.chart_data.conceptParametersFacets[j].meta.valueFieldPrefix + i,
                        "balloonText": "[[title]]: <b>[[value]]</b>",
                        "fillAlphas": 1,
                        "lineColor": colors[i]
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
                        "dataProvider": this.chart_data.conceptParametersFacets[j].data
                    }
                );
            }
        },
        methods: {

        }
    }
</script>