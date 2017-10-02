<template>
            <!-- OrganizationsModal -->
            <div class="ui modal">
                <div class="header">Join Organization</div>
                <div class="content ui form">
                    <div class="field">
                        <input type="text" style="position: fixed; left: -10000000px;" disabled />
                        <select class="ui search dropdown" v-model="joinOrg">
                            <option value="" ref="selectOrg">Select Organization to join</option>
                            <option v-for="o in _organizations" :value="o.id" :disabled="~_myOrganizations.indexOf(o)">{{ o.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="actions">
                    <div class="ui cancel button">Cancel</div>
                    <button class="ui orange ok button" :disabled="joinOrg === ''">Request to Join</button>
                </div>
            </div>
</template>

<script>
export default {
    name: 'OrganizationsModal',
    props: ['organizations', 'myOrganizations'],
    data() {
        return {
            joinOrg: ''
        };
    },
}
</script>