<template>
    <div>
        <div class="ui pointing secondary menu">
            <a class="item orange active" data-tab="organization">Organization Configuration</a>
            <a class="item orange" data-tab="server">Toylabs Configuration</a>
        </div>
        <form class="ui form" method="POST" ref="form" v-on:submit="submit">
            <input type="hidden" name="_token" :value="$parent.crsf" />

            <div class="ui bottom attached tab active" data-tab="organization">
                <retriever-form
                        v-model="retriever"
                        v-bind:readonly="retriever_readonly"
                ></retriever-form>
                <input type="hidden" name="retriever" ref="retriever" />
            </div>
            <div class="ui bottom attached tab" data-tab="server">
                <retriever-form
                        v-model="retriever_master"
                        v-bind:readonly="retriever_master_readonly"
                        nomarketset
                ></retriever-form>
                <input type="hidden" name="retriever_master" ref="retriever_master" />
            </div>

            <div class="ui divider"></div>

            <button type="submit" class="ui orange submit right floated button" ref="submitButton">
                Update
            </button>
            <a href="/dashboard" class="ui default right floated button">Cancel</a>
        </form>
    </div>


</template>

<script>
    import {RetrieverForm} from './market_analysis'

    export default {
        props: {
            '_retriever': Object,
            '_retriever_master': Object,
            '_can_edit': Boolean,
            '_can_edit_master': Boolean
        },
        components: {
            RetrieverForm
        },
        data() {
            return {
                retriever: this._retriever || {},
                retriever_master: this._retriever_master || {},
                retriever_readonly: !this._can_edit,
                retriever_master_readonly: !this._can_edit_master,
            };
        },
        methods: {
            submit() {
                this.$refs.retriever.value = JSON.stringify(this.retriever);
                this.$refs.retriever_master.value = JSON.stringify(this.retriever_master);
                this.$refs.form.submit();
            }
        }
    }
</script>