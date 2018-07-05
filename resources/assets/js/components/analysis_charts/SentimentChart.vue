<template>
    <div class="ui segment" style="height: 100%;">
        <h5 class="ui sub">{{ chartTitle }}
            <span class="longTooltip" :data-tooltip="chartInfo" data-position="bottom center" v-if="chartInfo">
                <i class="icon info"></i>
            </span>
        </h5>
        <div :id="id + '_export'" class="chart-export"></div>
        <div :id="id" style="height: 230px;"></div>
    </div>
</template>

<script>
    export default {
        props: {
            chartTitle: {
                type: String,
                required: true
            },
            chartInfo: {
                type: String
            },
            chartData: {
                type: Array,
                required: true
            },
            id: {
                type: String,
                required: true
            }
        },
        mounted() {
            let graphs = [];
            let exportFields = ['name', 'count'];
            let colors = {
                'negative': '#cc2929',
                'neutral': '#999999',
                'positive': '#04D215'
            };
            for (var i=0;i<this.chartData.length;i++) {
                this.chartData[i].color = colors[this.chartData[i].name];
            }

            graphs.push({
                "type": "column",
                "valueField": 'count',
                "title": "No. of posts",
                "balloonText": "[[category]]: <b>[[value]]</b>",
                "fillAlphas": 1,
                "fillColorsField": "color",
                "lineColorField": "color"
            });

            AmCharts.makeChart(this.id,
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
                        "valueLineEnabled": true,
                        "valueLineBalloonEnabled": true,
                        "cursorAlpha":1,
                        "cursorColor":"#258cbb",
                        "valueLineAlpha":0.2
                    },
                    "creditsPosition": "top-right",
                    "dataProvider": this.chartData,
                    "export": {
                        "enabled": true,
                        "exportTitles": true,
                        "exportFields": exportFields,
                        "divId": this.id + "_export"
                    }
                }
            );
        }
    }
</script>