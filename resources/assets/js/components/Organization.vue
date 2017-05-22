<template>
            <!-- Organization -->
            <form class="ui form" method="POST">
                <input type="hidden" name="_token" :value="$parent.crsf" />

                <h3 class="ui header">General</h3>
                <div class="ui divider"></div>

                <div class="field">
                    <label>Legal Name</label>
                    <input type="text" name="legal_name" placeholder="Legal name" v-model="legal_name" autofocus required />
                </div>
                <div class="inline fields">
                    <label>Legal Status</label>
                    <div class="field">
                        <div class="ui radio checkbox">
                            <input type="radio" name="legal_status" value="natural_person" v-model="legal_status" required />
                            <label>A Natural Person</label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui radio checkbox">
                            <input type="radio" name="legal_status" value="legal_person" v-model="legal_status" required />
                            <label>A Legal Person</label>
                        </div>
                    </div>
                </div>

                <div class="equal width fields">
                    <div class="field">
                        <label>Registration Country</label>
                        <select class="ui search dropdown" name="reg_country" v-model="reg_country">
                            <option value="">Select Country</option>
                            <option v-for="c in _countries" v-bind:value="c">{{ c }}</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Registration Number</label>
                        <input type="text" name="reg_number" placeholder="#########" v-model="reg_number" />
                    </div>
                    <div class="field">
                        <label>Legal Form</label>
                        <select class="ui search dropdown" name="legal_form" v-model="legal_form">
                            <option value="">Select One</option>
                            <option v-for="l in _legalForms" v-bind:value="l">{{ l }}</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>VAT Number</label>
                        <input type="text" name="vat_number" placeholder="########" v-model="vat_number" />
                    </div>
                </div>

                <h3 class="ui header">Address</h3>
                <div class="ui divider"></div>

                <div class="field">
                    <label>Street Name &amp; Number</label>
                    <input type="text" name="address" placeholder="Street Name and Number" v-model="address" />
                </div>
                <div class="equal width fields">
                    <div class="field">
                        <label>P.O. Box</label>
                        <input type="text" name="po_box" placeholder="P.O. Box" v-model="po_box" />
                    </div>
                    <div class="field">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" placeholder="Postal Code" v-model="postal_code" />
                    </div>
                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="City" v-model="city" />
                    </div>
                </div>
                <div class="fields">
                    <div class="four wide field">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="Phone" v-model="phone" />
                    </div>
                    <div class="four wide field">
                        <label>Fax Number</label>
                        <input type="text" name="fax" placeholder="Fax Number" v-model="fax" />
                    </div>
                    <div class="eight wide field">
                        <label>Website URL</label>
                        <input type="text" name="website_url" placeholder="Website URL" v-model="website_url" />
                    </div>
                </div>
            </form>
</template>

<script>
    export default {
        props: ['_countries', '_legalForms'],
        data () {
            return {
                legal_name: '',
                legal_status: '',
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
            };
        },
        computed: {}
    }
</script>