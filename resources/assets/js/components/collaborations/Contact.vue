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

        <!-- TODO: Attach files -->

        <form class="ui reply form" v-if="!thread.locked">
            <div class="field">
                <textarea v-model="reply"></textarea>
            </div>
            <div class="ui orange labeled icon right floated button" @click="sendReply">
                <i class="send icon"></i> Send
            </div>
        </form>
        <div class="ui disabled default labeled icon right floated button" v-if="thread.locked">
            <i class="lock icon"></i> Locked
        </div>
      </div>
</template>

<script>
import MessageView from '../MessageView.vue';

export default {
    components: { MessageView },
    props: ['organization', 'thread_id', 'target'],
    data() {
        return {
            thread: {
                subject: `[${this.capitalize(this.target.type)}: ${this.target.title}] Collaboration discussion with ${this.organization.name}`,
                locked: false,
                type: 'negotiation',
                target_id: this.target.id,
                target_type: `App\\${this.capitalize(this.target.type)}`,
                organization_id: this.organization.id,
            },
            messages: [],
            reply: '',
            users: [],
            error: null,
        };
    },
    created() {
        if (this.thread_id) {
            axios.get(`/messages/${this.thread_id}`).then((response) => {
                if (response.status === 200) {
                    if (response.data.error) {
                        this.error = response.data.error;
                    } else {
                        this.thread = response.data.thread;
                        this.messages = response.data.messages;
                        this.users = response.data.users;
                    }
                } else {
                    this.error = 'An error occured';
                }
            });
        }
    },
    methods: {
        capitalize(value) {
            return value.charAt(0).toUpperCase() + value.slice(1);
        },
        sendReply() {
            axios.post(`/contact/${this.organization.id}/${this.target.type}/${this.target.id}`, {
                thread: this.thread,
                message: this.reply,
            }).then((response) => {
                if (response.status === 200) {
                    if (typeof response.data.thread !== 'undefined') {
                        this.thread = response.data.thread;
                    }
                    this.messages.push(response.data.message);
                    this.reply = '';
                }
            });
        }
    },
}
</script>