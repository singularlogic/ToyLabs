<template>
    <div v-cloak class="thread">
        <div class="ui icon negative message" v-if="showWarning">
            <i class="warning icon"></i>
            <div class="content">
                <div class="header">Important</div>
                <p>Before you add someone to your {{ target.type }}, make sure you discuss and agree on the terms of your collaboration.  If needed, you can use the form below to attach and exchange documents (e.g. NDA agreements, contracts, etc) to help you reach an agreement.</p>
            </div>
        </div>

        <div class="ui error message" v-if="error">
            <div class="content">
                <p>{{ error }}</p>
            </div>
        </div>

        <div class="ui threaded comments">
            <message-view v-for="m in messages"
                :key="m.id"
                :message="m"
            ></message-view>
        </div>

        <dropzone id="files" ref="myDropzone"
            url="/file/upload"
            :useFontAwesome="true"
            :showRemoveLink="true"
            paramName="file"
            v-on:vdropzone-success="fileAdded"
            v-on:vdropzone-removed-file="fileRemoved"
            v-if="!thread.locked"
        >
            <input type="hidden" name="_token" :value="$parent.$root.crsf" />
        </dropzone>

        <form class="ui reply form" v-if="!thread.locked">
            <div class="field">
                <textarea v-model="reply"></textarea>
            </div>
            <button class="ui left floated positive button" type="button" @click="add()" v-if="canAdd">
                Add as collaborator
            </button>
            <button type="button" class="ui orange labeled icon right floated button" @click="sendReply" :disabled="reply.length == 0">
                <i class="send icon"></i> Send
            </button>
        </form>
        <div class="ui disabled default labeled icon right floated button" v-if="thread.locked">
            <i class="lock icon"></i> Locked
        </div>
      </div>
</template>

<script>
import MessageView from '../MessageView.vue';
import Dropzone from 'vue2-dropzone';
import _ from 'lodash';

export default {
    name: 'contact',
    components: { MessageView, Dropzone },
    beforeRouteEnter(to, from, next) {
        const type = typeof to.params.thread_type === 'undefined' ? '' : to.params.thread_type;
        axios.get(`/contact/${to.params.org_id}/${to.params.type}/${to.params.id}/${type}`).then((res) => {
            next(vm => vm.setData(res));
        });
    },
    beforeRouteUpdate(to, from, next) {
        const type = typeof to.params.thread_type === 'undefined' ? '' : to.params.thread_type;
        axios.get(`/contact/${to.params.org_id}/${to.params.type}/${to.params.id}/${type}`).then((res) => {
            this.setData(res);
            next();
        });
    },
    data() {
        return {
            organization: {},
            thread: {
                locked: true,
            },
            target: {},
            messages: [],
            reply: '',
            users: [],
            error: null,
            is_owner: false,
            uploadedFiles: [],
        };
    },
    computed: {
        showWarning() {
            return !this.thread.locked && this.thread.type === 'negotiation';
        },
        canAdd() {
            return !this.thread.locked && this.is_owner && this.thread.type === 'negotiation';
        },
    },
    methods: {
        add() {
            const params = this.$router.currentRoute.params;
            axios.post(`/${params.type}/${params.id}/collaborate/contact/${params.org_id}`).then(res => {
                this.thread.locked = true;
            });
        },
        fileAdded(file) {
            this.uploadedFiles.push({
                name: file.name,
                path: JSON.parse(file.xhr.response).path,
            });
        },
        fileRemoved(file, error, xhr) {
            const path = JSON.parse(file.xhr.response).path;
            const f = this.uploadedFiles.find((o) => o.path === path);
            const idx = this.uploadedFiles.indexOf(f);
        },
        capitalize(value) {
            return value.charAt(0).toUpperCase() + value.slice(1);
        },
        sendReply() {
            axios.post(`/contact/${this.organization.id}/${this.target.type}/${this.target.id}/${this.thread.type}`, {
                thread: this.thread,
                message: this.reply,
                files: this.uploadedFiles,
            }).then((response) => {
                if (response.status === 200) {
                    if (typeof response.data.thread !== 'undefined') {
                        this.thread = response.data.thread;
                    }
                    this.messages.push(response.data.message);
                    this.reply = '';
                    this.uploadedFiles = [];
                    this.$refs.myDropzone.removeAllFiles();
                }
            });
        },
        setData(res) {
            if (res.status === 200) {
                this.organization = res.data.organization;
                this.target = res.data.target;
                this.is_owner = res.data.is_owner;
                if (res.data.thread_id) {
                    this.thread_id = res.data.thread_id;
                    axios.get(`/messages/${this.thread_id}`).then((response) => {
                        if (response.status === 200) {
                            if (response.data.error) {
                                this.error = response.data.error;
                            } else {
                                this.thread = response.data.thread;
                                this.messages = response.data.messages;
                                this.users = response.data.users;
                                this.$store.commit('setActiveThread', { thread: _.cloneDeep(this.thread) });
                            }
                        } else {
                            this.error = 'An error occured';
                        }
                    });
                }
                this.$store.commit('setOrganization', { organization: _.cloneDeep(this.organization) });
            }
        },
    },
    destroyed() {
        this.$store.commit('setActiveThread', { thread: null });
        this.$store.commit('setOrganization', { organization: null });
    },
}
</script>