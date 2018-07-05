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
    import ColorService from '../../service/colorService'

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
            let graphs = [];
            let exportFields = ['key'];

            for (var i=0; i<this.chartData.meta.graphs; i++){
                graphs.push({
                    "type": "line",
                    "title": this.chartData.meta.labels[i].label,
                    "valueField": this.chartData.meta.valueFieldPrefix + i,
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    "fillAlphas": 0.2,
                    "bullet": "round",
                    "hideBulletsCount": 50,
                    "bulletSize": 4,
                    "lineColor":  ColorService.getColor(i, this.chartData.meta.graphs)
                });
                exportFields.push(this.chartData.meta.valueFieldPrefix + i);
            }

            AmCharts.makeChart(this.id,
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