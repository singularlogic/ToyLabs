<template>
    <div class="ui segment" style="height: 100%;">
        <h5 class="ui sub">{{ chartTitle }}
            <span class="longTooltip" :data-tooltip="chartInfo" v-if="chartInfo">
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
                type: Object,
                required: true
            },
            id: {
                type: String,
                required: true
            }
        },
        mounted() {
            let exportFields = ['key', 'doc_count'];

            AmCharts.makeChart(this.id,
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
                    "dataProvider": this.chartData.data,
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