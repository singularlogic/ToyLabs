<template>
    <div class="ui segment chart-container" style="height: 100%;">
        <h5 class="ui sub">{{ chartTitle }}
            <span class="longTooltip" :data-tooltip="chartInfo" data-position="bottom center" v-if="chartInfo">
                <i class="icon info"></i>
            </span>
        </h5>
        <div :id="`${id}_export`" class="chart-export"></div>
        <div class="ui pointing secondary menu">
            <a class="item orange active" :data-tab="`${id}_list`">List</a>
            <a class="item orange" :data-tab="`${id}_chart`">Chart</a>
        </div>
        <div :id="id" style="height: 230px;" :data-tab="`${id}_chart`" class="ui bottom attached tab"></div>
        <div :id="`${id}_list`" style="min-height: 240px;" :data-tab="`${id}_list`" class="ui bottom attached tab active">
            <div class="ui three stackable cards">
                <div v-for="(w, k) in data" class="card" :data-tooltip="'No. of post: '+w.doc_count">
                    <div class="content">{{ w.key }}</div>
                    <div class="ui bottom attached progress">
                        <div class="bar" :style="{ background: getColor(k, data.length), width: w.doc_count/totalWords*100+'%'}"></div>
                    </div>
                </div>
            </div>
        </div>
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
            chartDataIndex: {
                type: Number,
                required: true
            },
            id: {
                type: String,
                required: true
            }
        },
        data() {
            let total = this.chartData.meta.labels[this.chartDataIndex].doc_count;
            if (total === undefined){
                total = 0;
                this.chartData.data[this.chartDataIndex].forEach((d) => {
                    total += d.doc_count;
                });
            }
            return {
                data: this.chartData.data[this.chartDataIndex],
                totalWords: total
            }
        },
        mounted() {
            let graphs = [];
            let exportFields = ['key', 'doc_count'];

            AmCharts.makeChart(this.id,
                {
                    "path": "/vendor/amcharts/",
                    "type": "pie",
                    "valueField": "doc_count",
                    "titleField": "key",
//                    "labelsEnabled": false,
//                    "hideLabelsPercent": 2,
                    "marginBottom": 0,
                    "marginTop": 0,
                    "labelText": "[[title]]",
                    "colors": ColorService.getColor(-1, this.data.length),
                    "sequencedAnimation": false,
                    "creditsPosition": "top-right",
                    "dataProvider": this.data,
                    "export": {
                        "enabled": true,
                        "exportTitles": true,
                        "exportFields": exportFields,
                        "columnNames": {
                            "key": "Words",
                            "doc_count": "No. of posts"
                        },
                        "divId": this.id + "_export"
                    }
                }
            );
        },
        methods: {
            getColor: ColorService.getColor
        }
    }
</script>
<style>
    .chart-container .ui.progress.attached .bar {
        min-width: inherit;
    }

    .chart-container .ui.secondary.pointing.menu {
        min-height: 0px;
    }
    .chart-container .ui.secondary.pointing.menu .item {
        padding-top: 0px;
    }

    .chart-container .ui.cards>.card{
        margin: 0.44em 1em;
    }
    .chart-container .ui.cards>.card>.content{
        padding: .6em;
    }
</style>