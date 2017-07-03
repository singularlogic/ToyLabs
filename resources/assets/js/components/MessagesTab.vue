<template>
    <div>
        <button class="ui primary right floated labeled icon button" @click="newMessage" ref="newMessageButton">
            <i class="icon edit"></i> New Message
        </button>

        <div class="ui hidden clearing divider"></div>

        <div class="ui comments">
            <message-thread v-for="m of messages"
                :key="m.id"
                :thread="m"
            ></message-thread>
        </div>

        <div class="ui info message" v-if="messages.length === 0">
            <p>Your inbox is empty!</p>
        </div>

        <new-message-modal
            :message.sync="message"
            :users="users"
        ></new-message-modal>
    </div>
</template>

<script>
import NewMessageModal from './NewMessageModal.vue';
import MessageThread from './MessageThread.vue';
import { mapGetters } from 'vuex';

export default {
    components: { MessageThread, NewMessageModal },
    data() {
        return {
            users: [],
            message: {},
        };
    },
    created() {
        this.clearMessageModal()
    },
    computed: {
        ...mapGetters({
            messages: 'messagesArray',
        }),
    },
    methods: {
        loadUsers() {
            axios.get('/messages/users').then(response => {
                this.users.splice(0);
                this.users.push(...response.data.users);
            });
        },
        clearMessageModal() {
            this.message = {
                subject: '',
                recipients: [],
                body: '',
            };
        },
        sendMessage() {
            axios.post('/messages', this.message).then(() => {
                this.clearMessageModal();
            });
        },
        newMessage() {
            this.loadUsers();
            $('.ui.modal').modal({
                closable: false,
                onDeny: () => {
                    this.clearMessageModal();
                },
                onApprove: () => {
                    this.sendMessage();
                }
            }).modal('show');
        },
    }
}
</script>