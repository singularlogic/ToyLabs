<template>
    <div v-cloak class="thread">
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
            v-if="!thread.locked && !error"
        >
            <input type="hidden" name="_token" :value="$parent.$root.crsf" />
        </dropzone>

        <form class="ui reply form" v-if="!thread.locked && !error">
            <div class="field">
                <textarea v-model="reply"></textarea>
            </div>

            <button
                class="ui orange labeled icon right floated button"
                :class="{ loading: isSending }"
                type="button" @click="sendReply"
                :disabled="reply.length == 0 || isSending"
            >
                <i class="icon edit"></i> Add Reply
            </button>
        </form>
        <div class="ui default labelled disabled icon right floated button" v-if="thread.locked">
            <i class="lock icon"></i> Locked
        </div>
    </div>
</template>

<script>
import MessageView from './MessageView.vue';
import Dropzone from 'vue2-dropzone';

export default {
    components: { MessageView, Dropzone },
    data() {
        return {
            thread: {
                subject: '',
            },
            messages: [],
            reply: '',
            users: [],
            error: null,
            uploadedFiles: [],
            isSending: false,
        };
    },
    created() {
        const id = this.$route.params.id;
        axios.get(`/messages/${id}`).then((response) => {
            if (response.status === 200) {
                if (response.data.error) {
                    this.error = response.data.error;
                } else {
                    this.thread = response.data.thread;
                    this.messages = response.data.messages;
                    this.users = response.data.users;
                    this.$store.commit('setActiveThread', { thread: this.thread });
                }
            } else {
                this.error = 'An error occured';
            }
        }).catch(error => {
            this.error = 'Unauthorized access';
        });
    },
    methods: {
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
        sendReply() {
            this.isSending = true;
            const id = this.$route.params.id;
            axios.put(`/messages/${id}`, {
                message: this.reply,
                files: this.uploadedFiles,
            }).then((response) => {
                if (response.status === 200) {
                    this.messages.push(response.data.message);
                    this.reply = '';
                    this.uploadedFiles = [];
                    this.$refs.myDropzone.removeAllFiles();
                }
                this.isSending = false;
            });
        }
    },
    destroyed() {
        this.$store.commit('setActiveThread', { thread: null });
    }
}
</script>

<style>
.thread .dropzone .dz-message {
    margin: 1em 0;
}
</style>