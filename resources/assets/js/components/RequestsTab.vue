<template>
    <div>
        <div class="ui info message" v-if="requests.length === 0">
            <p>You have no pending requests!</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['requests'],
        data() {
            return {
            };
        },
    }
</script>