<template>
        <div class="ui modal">
            <div class="header">New Message</div>
            <div class="content">
                <div class="description">
                    <form class="ui form">
                        <div class="field">
                            <label>Subject</label>
                            <input type="text" name="subject" placeholder="Subject" v-model="message.subject" required />
                        </div>
                        <div class="field">
                            <label>To</label>
                            <select multiple="" class="ui dropdown" v-model="message.recipients">
                                <option v-for="u in users" v-bind:value="u.id">{{ u.name }}</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Message</label>
                            <textarea v-model="message.body" required></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="actions">
                <button class="ui cancel button">Cancel</button>
                <button class="ui orange approve left labeled icon button" @click="submit" :disabled="hasErrors">
                    <i class="send icon"></i> Send
                </button>
            </div>
        </div>
</template>

<script>
export default {
    props: ['message', 'users'],
    data() {
        return {
            errors: {},
        }
    },
    computed: {
        hasErrors() {
            return this.message.subject === '' || this.message.body === '' || this.message.recipients.length === 0;
        }
    },
    methods: {
        submit() {
            this.$emit('update:message', this.message)
        }
    }
}
</script>