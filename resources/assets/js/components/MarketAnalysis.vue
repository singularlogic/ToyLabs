<template>
    <div>
        <div class="ui pointing secondary menu">
            <a class="item orange" data-tab="analysis" v-bind:class="[ project.id ? 'active' : 'disabled']">Analyses</a>
            <a class="item orange" data-tab="configuration"  v-bind:class="[ project.id ? '' : 'active']">Project Configuration</a>
        </div>
        <div class="ui bottom attached tab active" data-tab="analysis"  v-if="project.id">
            <analyses-table
                    :product_id="_product_id"
                    :analyses="analyses"
                    :enabled_social="enabled_social"
            ></analyses-table>
        </div>
        <div class="ui bottom attached tab" data-tab="configuration"  v-bind:class="[ project.id ? '' : 'active']">
            <form class="ui form" method="POST" ref="form" v-on:submit="submit">
                <input type="hidden" name="_token" :value="$parent.crsf" />

                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" :value="project.name" placeholder="Enter a descriptive name for your market analysis project" />
                </div>

                <div class="ui divider"></div>

                <div class="ui error message"></div>

                <button type="submit" class="ui orange submit right floated labeled icon button" ref="submitButton">
                    <i class="edit icon"></i> {{ submitText }}
                </button>
            </form>
        </div>
    </div>


</template>

<script>
    $(document).ready(function() {
        $('.menu .item').tab();
    });
    import {AccountsTable, AnalysesTable} from './market_analysis'

    export default {
        props: ['_project', '_analyses', '_product_id'],
        components: {
            AccountsTable, AnalysesTable
        },
        data() {
            return {
                submitText: this._project.id ? 'Update' : 'Create',
                project: this._project,
                enabled_social: !!this._project.retriever,
                analyses: this._analyses || [],
            };
        },
        mounted() {
            $(this.$refs.form).form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'You must enter a Name'
                            }
                        ]
                    }
                }
            });
        },
        methods: {
            submit(event) {
                if (!$(this.$refs.form).form('is valid')){
                    event.preventDefault();
                    return false;
                }
                this.$refs.form.submit();
            }
        }
    }
</script>