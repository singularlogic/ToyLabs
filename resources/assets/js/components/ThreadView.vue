<template>
    <div v-cloak>
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
        <form class="ui reply form" v-if="messages.length > 0 && !thread.locked">
            <div class="field">
                <textarea v-model="reply"></textarea>
            </div>
            <div class="ui orange labeled icon right floated button" @click="sendReply">
                <i class="icon edit"></i> Add Reply
            </div>
        </form>
        <div class="ui default labelled disabled icon right floated button" v-if="thread.locked">
            <i class="lock icon"></i> Locked
        </div>
      </div>
</template>

<script>
import MessageView from './MessageView.vue';

export default {
    components: { MessageView },
    data() {
        return {
            thread: {
                subject: '',
            },
            messages: [],
            reply: '',
            users: [],
            error: null,
        };
    },
    created() {
        // TODO: Get messages
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
        });
    },
    methods: {
        sendReply() {
            const id = this.$route.params.id;
            axios.put(`/messages/${id}`, {
                message: this.reply
            }).then((response) => {
                if (response.status === 200) {
                    this.messages.push(response.data.message);
                    this.reply = '';
                }
            });
        }
    },
    destroyed() {
        this.$store.commit('setActiveThread', { thread: null });
    }
}
</script>