<template>
    <div>
        <form class="ui form" method="POST" ref="marketAnalysisForm" v-on:submit="submit">
            <input type="hidden" name="_token" :value="$parent.crsf" />

            <div class="ui pointing secondary menu">
                <a class="item orange" data-tab="configuration">Custom Settings</a>
                <a class="item orange" data-tab="timesettings">Time Settings</a>
                <a class="item orange" data-tab="conceptsettings">Concepts' Settings</a>
            </div>
            <div class="ui bottom attached tab active" data-tab="configuration">
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" v-model="name" placeholder="Enter a descriptive name for your market trend analysis" required />
                </div>
                <div class="field">
                    <label>Keywords</label>
                    <keywords-field
                        v-model="keyphrases_settings"
                    ></keywords-field>
                    <small class="helper">
                        Enter the words, phrases and hashtags to be used as search terms. Single keywords are separated with commas.
                        Use ‘ for exact phrases and ! to exclude a term/phrase.
                    </small>
                    <div class="ui info message">
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
                        The data sources to be used for the analysis. Choose one or more among Facebook and Twitter.
                    </small>
                    <input type="hidden" name="sources" ref="sources" />

                </div>

                <div class="field">
                    <label>Influencers</label>
                    <div class="ui toggle checkbox">
                        <input type="checkbox" v-model="analysis.influencers_mode">
                        <label>Only influencers</label>
                        <input type="hidden" name="influencers_mode" v-bind:value="analysis.influencers_mode" />
                    </div>

                    <small class="helper">
                        Select this option to activate influencer mode. If influencer mode is activated only designated influencer data sources will be used.
                    </small>
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

            <div class="ui bottom attached tab" data-tab="conceptsettings">
                <div class="ui info message">
                    Provide specific meaningful <strong>concepts of interest</strong> (e.g. baby dolls) and <strong>parameters</strong> (e.g. colour, material)
                    that will be presented and formulate the visualisations accordingly in order to be provided with useful
                    and meaningful information customized to your needs.
                    <br/><br/>
                    The <strong>concepts of interest</strong> are terms/ideas that you want to monitor and understand crowd perception and
                    market trends spinning around these terms. The value format follows the same logic as the keywords in the Custom Settings tab.
                    <br/><br/>
                    The <strong>parameters</strong> you need to specify are features on which the concepts of interest will be analysed
                    and compared (e.g. a baby doll made of wood or plastic. The parameter name here is the "material" and the values "wood, plastic"). The value format is comma separated values.
                </div>
                <div class="field">
                    <label>Concepts</label>
                    <concepts-table
                        v-model="concepts"
                    ></concepts-table>
                    <input type="hidden" name="concepts" ref="concepts" />

                </div>
                <div class="field">
                    <label>Parameters</label>
                    <parameters-table
                        v-model="parameters"
                    ></parameters-table>
                    <input type="hidden" name="parameters" ref="parameters" />

                </div>
            </div>
            <div class="ui divider"></div>
            <input type="hidden" name="product_id" v-model="_product_id" />
            <input type="hidden" name="languages" ref="languages" />
            <input type="hidden" name="action" v-model="action" />

            <button type="submit" class="ui orange submit right floated labeled icon button" ref="submitButton">
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
    import {ParametersTable, ConceptsTable, KeywordsField} from './market_analysis'
    import Datepicker from 'vuejs-datepicker';

    export default {
        props: ['_analysis', '_product_id'],
        components: {
            ParametersTable, ConceptsTable, KeywordsField, Datepicker
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
                action: this._analysis.id ? 'save' : 'create',
                submitText: this._analysis.id ? 'Update' : 'Create',
                analysis: this._analysis,
                name: this._analysis.name || "",
                sources: this._analysis.sources || ["twitter"],
                languages: this._analysis.languages || ["en"],
                keyphrases_settings: this._analysis.keyphrases_settings || [],
                parameters: this._analysis.parameters || [],
                concepts: this._analysis.concepts || [],
                start_date: start_date,
                end_date: end_date

            };
        },
        methods: {
            submit() {
                this.$refs.start_date.value = this.start_date.toISOString().substring(0, 10);
                this.$refs.end_date.value = this.end_date.toISOString().substring(0, 10);
                this.$refs.sources.value = JSON.stringify(this.sources);
                this.$refs.languages.value = JSON.stringify(this.languages);
                this.$refs.keyphrases_settings.value = JSON.stringify(this.keyphrases_settings);
                this.$refs.parameters.value = JSON.stringify(this.parameters);
                this.$refs.concepts.value = JSON.stringify(this.concepts);
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