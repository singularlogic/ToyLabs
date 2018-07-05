<template>
    <div class="ui segment" style="height: 100%;">
        <h5 class="ui sub">{{ chartTitle }}
            <span class="longTooltip" :data-tooltip="chartInfo" v-if="chartInfo">
                <i class="icon info"></i>
            </span>
        </h5>
        <div :id="id + '_export'" class="chart-export"></div>
        <div :id="id" style="height: 250px;"></div>
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
                    "type": "column",
                    "title": this.chartData.meta.labels[i].label,
                    "valueField": this.chartData.meta.valueFieldPrefix + i,
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    "fillAlphas": 1,
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
                        "parseDates": false,
                        "title": this.chartData.meta.parameter.name,
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