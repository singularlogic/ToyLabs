<template>
    <table class="ui celled table">
        <thead class="full-width">
            <tr>
                <th class="two wide">Type</th>
                <th class="ten wide">Name</th>
                <th class="one wide">Influencer</th>
                <th class="two wide">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="accounts.length == 0">
                <td colspan="5" class="center aligned">No accounts added</td>
            </tr>
            <tr v-for="account in accounts">
                <td>{{ account.source | capitalize }}</td>
                <td>{{ account.name }}</td>
                <td class="center aligned">
                    <i v-if="account.is_influencer" class="large green checkmark icon"></i>
                </td>
                <td class="collapsing">
                    <button type="button" class="ui mini red icon button" data-tooltip="Delete Account" @click="removeAccount(account)">
                        <i class="trash icon"></i>
                    </button>
                </td>
            </tr>
        </tbody>
        <tfoot v-if="!insertAccountMode">
            <th colspan="5">
                <div class="ui right floated small primary labeled icon button" @click="addAccount()">
                    <i class="marker icon"></i> Add Account
                </div>
            </th>
        </tfoot>
        <tfoot v-if="insertAccountMode">
            <tr>
                <th>
                    <select class="ui search dropdown" v-model="newAccount.source">
                        <option value="">Select source...</option>
                        <option v-for="t in accountTypes" v-bind:value="t.value">{{ t.label}}</option>
                    </select>
                </th>
                <th>
                    <input type="text" v-model="newAccount.name" placeholder="Enter a twitter account or facebook URL" required />
                </th>
                <th class="center aligned">
                    <div class="ui checkbox" data-tooltip="Is influencer account?">
                        <input type="checkbox" v-model="newAccount.is_influencer">
                        <label></label>
                    </div>
                </th>
                <th class="collapsing">
                    <button type="button" class="ui mini basic green icon button" data-tooltip="Save Account" @click="saveAccount()" :disabled="!isAccountValid">
                        <i class="checkmark icon"></i>
                    </button>
                    <button type="button" class="ui mini basic red icon button" data-tooltip="Cancel" @click="cancelAccount()"><i class="remove icon"></i></button>
                </th>
            </tr>
        </tfoot>
    </table>
</template>

<script>
    export default {
        props: ['value'],
        data() {
            return {
                insertAccountMode: false,
                accounts: this.value || [],
                newAccount: {},
                accountTypes: [
                    {value:'twitter',label:'Twitter'},
                    {value:'facebook',label:'Facebook'}
                ]
            };
        },
        computed: {
            isAccountValid() {
                return !!this.newAccount.source && !!this.newAccount.name;
            }
        },
        methods: {
            addAccount() {
                this.newAccount = {
                    name: '',
                    source: '',
                    is_influencer: false
                };
                this.insertAccountMode = true;
            },
            cancelAccount() {
                this.insertAccountMode = false;
            },
            removeAccount(account) {
                const index = this.accounts.indexOf(account);
                if (~index) {
                    this.accounts.splice(index, 1);
                }
            },
            saveAccount() {
                this.accounts.push(this.newAccount);
                this.newAccount = {};
                this.insertAccountMode = false;
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return '';
                value = value.toString();
                return value.charAt(0).toUpperCase() + value.slice(1);
            }
        }
    }
</script>