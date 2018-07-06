<template>
    <div>
        <form class="ui form" method="POST" ref="marketAnalysisForm" v-on:submit="submit">
            <input type="hidden" name="_token" :value="$parent.crsf" />

            <div class="ui pointing secondary menu">
                <a class="item orange active" data-tab="configuration">Custom Settings</a>
                <a class="item orange" data-tab="timesettings">Time Settings</a>
            </div>
            <div class="ui bottom attached tab active" data-tab="configuration">
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" v-model="name" placeholder="Enter a descriptive name for your social feedback analysis" />
                </div>
                <div class="field">
                    <label>Keywords</label>
                    <keywords-field
                        v-model="keyphrases_settings"
                    ></keywords-field>
                    <small class="helper">
                        Enter the words, phrases and hashtags to be used as search terms. Single keywords (grey) are separated with commas.
                        Use ‘ for exact phrases (green) and ! to exclude a term/phrase (orange).
                    </small>
                    <div class="ui info message">
                        <i class="info icon"></i>
                        For example by pressing <strong>toy, cube, ‘puzzle toy, !sphere</strong> the tool will search in social media for posts that
                        include the words “toy” and “cube”, the exact phrase “puzzle toy” and exclude results that include the word
                        “sphere”. In other words this search will return results relevant to puzzle toys that are shaped like cubes but not like spheres.
                        <br/><br/>
                        In another example the user could be searching for toys that are shaped like cubes or spheres but are not puzzle toys.
                        In this case the search would look like so: <strong>toy, cube, sphere, !puzzle toy</strong>
                    </div>
                    <input type="hidden" name="keyphrases_settings" ref="keyphrases_settings" />
                </div>
                <div class="field">
                    <label>Source type</label>
                    <div class="ui toggle checkbox">
                        <input type="checkbox" value="twitter" v-model="sources">
                        <label>Twitter</label>
                    </div>
                    <div class="ui toggle checkbox">
                        <input type="checkbox" value="facebook" v-model="sources">
                        <label>Facebook</label>
                    </div>

                    <small class="helper">
                        The data sources to be used for the analysis. Choose between Twitter and/or Facebook.
                    </small>
                    <input type="hidden" name="sources" ref="sources" />

                </div>
            </div>
            <div class="ui bottom attached tab" data-tab="timesettings">

                <div class="field">
                    <label>From date</label>
                        <datepicker
                                v-model="start_date"
                        ></datepicker>
                    <input type="hidden" name="start_date" ref="start_date" />
                </div>
                <div class="field">
                    <label>To date</label>
                    <datepicker
                            v-model="end_date"
                    ></datepicker>
                    <input type="hidden" name="end_date" ref="end_date" />

                </div>
                <div class="ui small primary labeled icon button" @click="calculateDatesLast(7)">
                    <i class="calendar icon"></i> Last week
                </div>
                <div class="ui small primary labeled icon button" @click="calculateDatesLast(30)">
                    <i class="calendar icon"></i> Last month
                </div>
                <div class="ui small primary labeled icon button" @click="calculateDatesLast(365)">
                    <i class="calendar icon"></i> Last year
                </div>
            </div>
            <div class="ui divider"></div>
            <input type="hidden" name="product_id" v-model="_product_id" />
            <input type="hidden" name="languages" ref="languages" />
            <input type="hidden" name="action" v-model="action" />

            <div class="ui error message"></div>

            <button type="submit" class="ui orange submit right floated labeled icon button" ref="submitButton" v-on:click="action=defaultAction">
                <i class="edit icon"></i> {{ submitText }}
            </button>
            <button v-if="analysis.id" type="submit" class="ui orange submit right floated labeled icon button" ref="submitCopyButton" v-on:click="action='copy'">
                <i class="edit icon"></i> Save as copy
            </button>
        </form>
    </div>
</template>

<script>
    $(document).ready(function() {
        $('.menu .item').tab();
    });
    import {KeywordsField} from './market_analysis'
    import Datepicker from 'vuejs-datepicker';

    export default {
        props: ['_analysis', '_product_id'],
        components: {
            KeywordsField, Datepicker
        },
        data() {
            var start_date, end_date;

            if (this._analysis.start_date){
                start_date = new Date(this._analysis.start_date);
            } else {
                start_date = new Date();
                start_date.setDate(start_date.getDate() - 7);
            }

            if (this._analysis.end_date){
                end_date = new Date(this._analysis.end_date);
            } else {
                end_date = new Date();
            }

            return {
                defaultAction: this._analysis.id ? 'save' : 'create',
                action: this.defaultAction,
                submitText: this._analysis.id ? 'Update' : 'Create',
                analysis: this._analysis,
                name: this._analysis.name || "",
                sources: this._analysis.sources || ["twitter"],
                languages: this._analysis.languages || ["en"],
                keyphrases_settings: this._analysis.keyphrases_settings || [],
                start_date: start_date,
                end_date: end_date

            };
        },
        mounted() {
            $(this.$refs.marketAnalysisForm).form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'You must enter a Name'
                            },
                            {
                                type   : 'uniqueNameRule',
                                prompt : 'You must change the Name in order to save as copy'
                            }
                        ]
                    },
                    source: {
                        identifier: 'sources',
                        rules: [
                            {
                                type   : 'sourcesRule',
                                prompt : 'You must select at least one Source'
                            }
                        ]
                    },
                    start_date: {
                        identifier: 'start_date',
                        rules: [
                            {
                                type   : 'startDateRule',
                                prompt : 'You must select a From date that is earlier date than today'
                            }
                        ]
                    },
                    end_date: {
                        identifier: 'end_date',
                        rules: [
                            {
                                type   : 'dateRangeRule',
                                prompt : 'You must select a To date that is later that the From date'
                            }
                        ]
                    }
                },
                rules: {
                    sourcesRule: () => {
                        return !!this.sources.length;
                    },
                    uniqueNameRule: () => {
                        if (this.action == 'copy')
                            return this.name != this._analysis.name;
                        return true;
                    },
                    startDateRule: () => {
                        return this.start_date <= new Date();
                    },
                    dateRangeRule: () => {
                        return this.start_date <= this.end_date;
                    }
                }
            });
        },
        methods: {
            submit(event) {
                if (!$(this.$refs.marketAnalysisForm).form('is valid')){
                    event.preventDefault();
                    return false;
                }
                this.$refs.start_date.value = this.start_date.toISOString().substring(0, 10);
                this.$refs.end_date.value = this.end_date.toISOString().substring(0, 10);
                this.$refs.sources.value = JSON.stringify(this.sources);
                this.$refs.languages.value = JSON.stringify(this.languages);
                this.$refs.keyphrases_settings.value = JSON.stringify(this.keyphrases_settings);
                this.$refs.marketAnalysisForm.submit();
            },
            calculateDatesLast(period) {
                period = period || 0;
                this.end_date = new Date();
                var tmp = new Date();
                tmp.setDate(tmp.getDate() - period);
                this.start_date = tmp;
            }
        }
    }
</script>