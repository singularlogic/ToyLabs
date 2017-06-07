<template>
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
                        <select class="ui search dropdown" name="country" v-model="personal.country">
                            <option value="">Select Country</option>
                            <option v-for="c in _countries" v-bind:value="c">{{ c }}</option>
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
                    <label>Is your organization already in ToyLabs?</label>
                    <div class="field">
                        <div class="ui radio checkbox">
                            <input type="radio" name="state" value="existing" v-model="organizationState" />
                            <label>Yes. I want to join</label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui radio checkbox">
                            <input type="radio" name="state" value="new" v-model="organizationState" />
                            <label>No. I want to create a new one</label>
                        </div>
                    </div>
                </div>

                <div style="display: none;" v-show="organizationState === 'existing' && isProfessional">
                    <h3 class="ui header">Organization</h3>
                    <div class="ui divider"></div>

                    <div class="field">
                        <select class="ui search dropdown" v-model="professional.organization">
                            <option value="">Select Organization</option>
                            <option v-for="o in _organizations" v-bind:value="o.id">{{ o.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="ui divider"></div>

                <button type="submit" class="ui orange submit right floated button">{{ submitText }}</button>
            </form>
</template>
<script>
export default {
    props: ['_countries', '_organizations', '_personal', '_professional'],
    data () {
        return {
            hasOrganization: '',
            organizationState: '',
            personal: this._personal,
            professional: this._professional
        };
    },
    computed: {
        isProfessional() { return this.professional.role !== 'end_user'; },
        submitText() {
            if (this.organizationState === 'new' && this.isProfessional && this.hasOrganization === 'true') {
                return 'Save and Create Organization';
            }

            return 'Update';
        }
    }
};
</script>