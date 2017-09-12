<template>
        <div>
            <!-- Profile -->
            <form class="ui form" method="POST">
                <input type="hidden" name="_token" :value="$parent.crsf" />

                <h3 class="ui header">Personal</h3>
                <div class="ui divider"></div>

                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Name" v-model="personal.name" autofocus />
                </div>
                <div class="fields">
                    <div class="eight wide field">
                        <label>Address</label>
                        <input type="text" name="address" placeholder="Address" v-model="personal.address" />
                    </div>
                    <div class="four wide field">
                        <label>Country</label>
                        <select class="ui search dropdown" name="country_id" v-model="personal.country_id">
                            <option value="">Select Country</option>
                            <option v-for="c in _countries" v-bind:value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div class="four wide field">
                        <label>Telephone</label>
                        <input type="text" name="telephone" placeholder="Telephone" v-model="personal.telephone" />
                    </div>
                </div>
                <div class="three fields">
                    <div class="field">
                        <label>Facebook</label>
                        <input type="text" name="facebook" placeholder="http://" v-model="personal.facebook" />
                    </div>
                    <div class="field">
                        <label>Twitter</label>
                        <input type="text" name="twitter" placeholder="http://" v-model="personal.twitter" />
                    </div>
                    <div class="field">
                        <label>LinkedIn</label>
                        <input type="text" name="linkedin" placeholder="http://" v-model="personal.linkedin" />
                    </div>
                </div>

                <div v-if="personal.isNew">
                    <h3 class="ui header">Professional</h3>
                    <div class="ui divider"></div>

                    <div class="inline fields">
                        <label>What's the role you have in ToyLabs?</label>
                        <div class="field" :class="{ disabled: !personal.isNew }">
                            <div class="ui radio checkbox">
                                <input type="radio" name="role" id="manufacturer" value="manufacturer" v-model="professional.role" />
                                <label for="manufacturer">Manufacturer</label>
                            </div>
                        </div>
                        <div class="field" :class="{ disabled: !personal.isNew }">
                            <div class="ui radio checkbox">
                                <input type="radio" name="role" id="fablab" value="fablab" v-model="professional.role" />
                                <label for="fablab">FabLab</label>
                            </div>
                        </div>
                        <div class="field" :class="{ disabled: !personal.isNew }">
                            <div class="ui radio checkbox">
                                <input type="radio" name="role" id="safety_expert" value="safety_expert" v-model="professional.role" />
                                <label for="safety_expert">Safety Expert</label>
                            </div>
                        </div>
                        <div class="field" :class="{ disabled: !personal.isNew }">
                            <div class="ui radio checkbox">
                                <input type="radio" name="role" id="child_expert" value="child_expert" v-model="professional.role" />
                                <label for="child_expert">Childhood Expert</label>
                            </div>
                        </div>
                        <div class="field" :class="{ disabled: !personal.isNew }">
                            <div class="ui radio checkbox">
                                <input type="radio" name="role" id="end_user" value="end_user" v-model="professional.role" />
                                <label for="end_user">Just a User!</label>
                            </div>
                        </div>
                    </div>

                    <div class="inline fields" v-if="isProfessional">
                        <label>Are you a member of an organization?</label>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="has_organization" value="true" v-model="hasOrganization" />
                                <label>Yes</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="has_organization" value="false" v-model="hasOrganization" />
                                <label>No, I work alone</label>
                            </div>
                        </div>
                    </div>

                    <div class="inline fields" v-if="hasOrganization == 'true' && isProfessional">
                        <label>Has organization already joined ToyLabs?</label>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="state" value="existing" v-model="organizationState" />
                                <label>Yes, and I want to join</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="state" value="new" v-model="organizationState" />
                                <label>Not yet. I want to create it myself</label>
                            </div>
                        </div>
                    </div>

                    <div style="display: none;" v-show="organizationState === 'existing' && isProfessional">
                        <h3 class="ui header">Organization</h3>
                        <div class="ui divider"></div>

                        <div class="field">
                            <select class="ui search dropdown" v-model="joinOrg">
                                <option value="" ref="selectOrg">Select Organization</option>
                                <option v-for="o in organizations" :value="o.id">{{ o.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div v-if="!personal.isNew">
                    <h3 class="ui header" style="margin-top: 20px;">
                        Professional
                        <span class="ui basic blue label">{{ myRole }}</span>
                    </h3>
                    <div class="ui divider"></div>

                    <h4 class="ui header">Organization<span v-if="orgCount > 1">s</span>:</h4>
                    <div class="ui middle aligned divided list" style="padding-left: 50px;">
                        <div class="item" v-for="o in professional.organizations">
                            <div class="right floated content">
                                <a class="ui negative mini button" v-if="o.owner_id != personal.id" @click="leaveGroup(o, false)">Leave</a>
                                <div class="ui basic green label" v-if="o.owner_id == personal.id">Owner</div>
                            </div>
                            <img class="ui avatar image" src="/images/avatar/small/elliot.jpg" />
                            <div class="content">
                                <div class="header" v-if="o.owner_id !== personal.id">{{ o.name }}</div>
                                <a class="header" v-if="o.owner_id == personal.id" :href="`/organization/${o.id}/edit`">{{ o.name }}</a>
                            </div>
                        </div>
                        <div class="item" v-for="o in professional.pending">
                            <div class="right floated content">
                                <div class="ui basic black label">Pending</div>
                                <a class="ui negative mini button" v-if="o.owner_id != personal.id" @click="leaveGroup(o, true)">Cancel</a>
                            </div>
                            <img class="ui avatar image" src="/images/avatar/small/elliot.jpg" />
                            <div class="content">
                                <div class="header" v-if="o.owner_id !== personal.id">{{ o.name }}</div>
                                <a class="header" v-if="o.owner_id == personal.id" :href="`/organization/${o.id}/edit`">{{ o.name }}</a>
                            </div>
                        </div>
                        <div class="item" style="margin-top: 20px;" v-if="orgCount == 0">
                            <div class="right floated content">
                                <a class="ui blue mini button" @click="joinOrganization()">
                                    <i class="plus icon"></i>Join
                                </a>
                                <a class="ui black mini button" @click="createOrganization()">
                                    <i class="write icon"></i>Create
                                </a>
                            </div>
                            <div class="content" v-if="orgCount == 0">
                                <em>You haven't joined any organization yet.</em>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ui divider"></div>

                <input type="hidden" name="createOrganization" ref="createOrganization" />
                <!-- <input type="hidden" name="newOrganizations" ref="newOrganizations" /> -->

                <button type="submit" class="ui orange submit right floated button" ref="submitButton">{{ submitText }}</button>
                <a href="/dashboard" class="ui default right floated button">Cancel</a>
            </form>

            <div class="ui modal" id="joinGroup">
                <div class="header">Join Organization</div>
                <div class="content ui form">
                    <div class="field">
                        <input type="text" style="position: fixed; left: -10000000px;" disabled />
                        <select class="ui search dropdown" v-model="joinOrg">
                            <option value="" ref="selectOrg">Select Organization to join</option>
                            <option v-for="o in organizations" :value="o.id">{{ o.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="actions">
                    <div class="ui cancel button">Cancel</div>
                    <button class="ui orange ok button" :disabled="joinOrg === ''">Request to Join</button>
                </div>
            </div>

            <confirm-dialog
                id="leaveGroup"
                icon="trash"
                title="Leave Organization?"
                body="Are you sure you want to leave this organization? The owner will have to approve your request, if you decide you want to join again"
            ></confirm-dialog>

        </div>
</template>
<script>
import ConfirmDialog from './ConfirmDialog.vue';

export default {
    components: { ConfirmDialog },
    props: ['_countries', '_organizations', '_personal', '_professional', '_types'],
    data () {
        return {
            hasOrganization: !!this._professional.organizations,
            organizationState: '',
            personal: this._personal,
            professional: this._professional,
            joinOrg: '',
        };
    },
    computed: {
        isProfessional() { return this.professional.role !== 'end_user'; },
        submitText() {
            if (this.organizationState === 'new' && this.isProfessional && this.hasOrganization === 'true') {
                return 'Save and Create Organization';
            }

            return 'Update';
        },
        organizations() {
            return this._organizations.filter(org => org.typeSlug === this.professional.role);
        },
        myRole() {
            return this._types.find(type => type.slug === this.professional.role).name;
        },
        orgCount() {
            return parseInt(this.professional.organizations.length) + parseInt(this.professional.pending.length);
        }
    },
    methods: {
        createOrganization: function() {
            // Save changes and redirect to create organization page. Might be better if we prompt user to save changes
            this.$refs.createOrganization.value = true;
            this.$refs.submitButton.click();
        },
        joinOrganization: function() {
            $('#joinGroup').modal({
                transition: 'scale',
                closable: false,
                onApprove: () => {
                    axios.put(`/organization/${this.joinOrg}/join`).then((response) => {
                        if (response.status === 200) {
                            const org = this._organizations.find(org => org.id = this.joinOrg);
                            this.professional.pending.push(org);
                        }
                    });
                }
            }).modal('show');
        },
        leaveGroup(org, pending) {
            $('#leaveGroup').modal({
                closable: false,
                onApprove: () => {
                    let idx = -1;
                    if (pending) {
                        idx = this.professional.pending.find(o => o.id == org.id);
                    } else {
                        idx = this.professional.organizations.find(o => o.id == org.id);
                    }

                    if (~idx) {
                        axios.put(`/organization/${org.id}/leave`).then((response) => {
                            if (response.status === 200) {
                                if (pending) {
                                    this.professional.pending.splice(idx, 1);
                                } else {
                                    this.professional.organizations.splice(idx, 1);
                                }
                            }
                        });
                    }
                },
            }).modal('show');
        }
    },
    watch: {
        'professional.role': function(val, oldValue) {
            this.hasOrganization = null;
            this.organizationState = null;
            this.joinOrg = '';
        }
    }
};
</script>