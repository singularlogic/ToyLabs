<template>
    <div>
        <div class="ui info message" v-if="messages.length === 0">
            <p>Your inbox is empty!</p>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    computed: {
        ...mapGetters({
            messages: 'messagesArray',
        }),
    },
}
</script>