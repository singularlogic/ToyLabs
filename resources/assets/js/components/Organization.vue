<template>
            <!-- Organization -->
            <form class="ui form" method="POST" action="/organization/edit">
                <input type="hidden" name="_token" :value="$parent.crsf" />

                <h3 class="ui header">General</h3>
                <div class="ui divider"></div>

                <div class="equal width fields">
                    <div class="field">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name" v-model="organization.name" autofocus required />
                    </div>
                    <div class="field">
                        <label>Legal Name</label>
                        <input type="text" name="legal_name" placeholder="Legal name" v-model="organization.legal_name" required />
                    </div>
                </div>

                <div class="equal width fields">
                    <div class="field">
                        <label>Registration Country</label>
                        <select class="ui search dropdown" name="reg_country" v-model="organization.reg_country">
                            <option value="">Select Country</option>
                            <option v-for="c in _countries" v-bind:value="c">{{ c }}</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Registration Number</label>
                        <input type="text" name="reg_number" placeholder="#########" v-model="organization.reg_number" />
                    </div>
                    <div class="field">
                        <label>Legal Form</label>
                        <select class="ui search dropdown" name="legal_form" v-model="organization.legal_form">
                            <option value="">Select One</option>
                            <option v-for="l in _legalForms" v-bind:value="l">{{ l }}</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>VAT Number</label>
                        <input type="text" name="vat_number" placeholder="########" v-model="organization.vat_number" />
                    </div>
                </div>
                <div class="field">
                    <label>Description</label>
                    <textarea name="description" v-model="organization.description"></textarea>
                </div>

                <h3 class="ui header">Address</h3>
                <div class="ui divider"></div>

                <div class="field">
                    <label>Street Name &amp; Number</label>
                    <input type="text" name="address" placeholder="Street Name and Number" v-model="organization.address" />
                </div>
                <div class="equal width fields">
                    <div class="field">
                        <label>P.O. Box</label>
                        <input type="text" name="po_box" placeholder="P.O. Box" v-model="organization.po_box" />
                    </div>
                    <div class="field">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" placeholder="Postal Code" v-model="organization.postal_code" />
                    </div>
                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="City" v-model="organization.city" />
                    </div>
                </div>
                <div class="fields">
                    <div class="four wide field">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="Phone" v-model="organization.phone" />
                    </div>
                    <div class="four wide field">
                        <label>Fax Number</label>
                        <input type="text" name="fax" placeholder="Fax Number" v-model="organization.fax" />
                    </div>
                    <div class="eight wide field">
                        <label>Website URL</label>
                        <input type="text" name="website_url" placeholder="Website URL" v-model="organization.website_url" />
                    </div>
                </div>

                <div class="ui divider"></div>

                <input type="hidden" name="id" :value="_id" />

                <button type="submit" class="ui orange submit right floated button">{{ submitText }}</button>
                <a href="/dashboard" class="ui default right floated button">Cancel</a>
            </form>
</template>

<script>
    export default {
        props: ['_countries', '_legalForms', '_id', '_organization'],
        data () {
            if (this._organization === null) {
                return {
                    organization: {
                        name: '',
                        description: '',
                        legal_name: '',
                        reg_country: '',
                        reg_number: '',
                        legal_form: '',
                        vat_number: '',
                        address: '',
                        po_box: '',
                        postal_code: '',
                        city: '',
                        phone: '',
                        fax: '',
                        website_url: '',
                    }
                };
            }

            return {
                organization: this._organization,
            }
        },
        computed: {
            submitText() {
                return this._id === 0 ? 'Create' : 'Update';
            }
        }
    }
</script>