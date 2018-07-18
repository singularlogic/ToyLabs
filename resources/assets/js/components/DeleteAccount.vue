<template>
    <div>
        <div class="ui left floated checkbox">
            <input type="checkbox" value="1" v-model="agreed">
            <label>I understand that by pressing delete, my account is queued to be deleted for ever!</label>
        </div>
        <button class="ui negative right floated button" :disabled="!agreed" @click="deleteAccount">Delete Account</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            agreed: 0,
        };
    },
    methods: {
        deleteAccount() {
            axios.delete('/profile/delete').then((res) => {
                if (res.status === 200) {
                    window.location = '/';
                }
            });
        },
    },
};
</script>
