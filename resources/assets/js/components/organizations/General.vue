<template>
    <div class="ui bottom attached tab">
        <div class="fields">
            <div class="six wide field">
                <label>Name</label>
                <input type="text" name="name" placeholder="Name" v-model="organization.name" autofocus required />
            </div>
            <div class="six wide field">
                <label>Legal Name</label>
                <input type="text" name="legal_name" placeholder="Legal name" v-model="organization.legal_name" required />
            </div>
            <div class="four wide field">
                <label>Legal Form</label>
                <select class="ui search dropdown" name="legal_form" v-model="organization.legal_form">
                    <option value="">Select One</option>
                    <option v-for="l in legalForms" v-bind:value="l">{{ l }}</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label>Description</label>
            <textarea name="description" v-model="organization.description"></textarea>
        </div>

        <div class="field">
            <label>Address</label>
            <input type="text" name="address" placeholder="Street Name and Number" v-model="organization.address" />
        </div>
        <div class="equal width fields">
            <div class="field">
                <label>Postal Code</label>
                <input type="text" name="postal_code" placeholder="Postal Code" v-model="organization.postal_code" />
            </div>
            <div class="field">
                <label>City</label>
                <input type="text" name="city" placeholder="City" v-model="organization.city" />
            </div>
            <div class="field">
                <label>Country</label>
                <select class="ui search dropdown" name="country_id" v-model="organization.country_id">
                    <option value="">Select Country</option>
                    <option v-for="c in countries" v-bind:value="c.id">{{ c.name }}</option>
                </select>
            </div>
        </div>
        <div class="equal width fields">
            <div class="field">
                <label>P.O. Box</label>
                <input type="text" name="po_box" placeholder="P.O. Box" v-model="organization.po_box" />
            </div>
            <div class="field">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Phone" v-model="organization.phone" />
            </div>
            <div class="field">
                <label>Fax Number</label>
                <input type="text" name="fax" placeholder="Fax Number" v-model="organization.fax" />
            </div>
        </div>

        <div class="equal width fields">
            <div class="field">
                <label>Website URL</label>
                <input type="text" name="website_url" placeholder="http://..." v-model="organization.website_url" />
            </div>
            <div class="field">
                <label>Instagram</label>
                <input type="text" name="instagram" placeholder="https://www.instagram.com/..." v-model="organization.instagram" />
            </div>
        </div>
        <div class="equal width fields">
            <div class="field">
                <label>Facebook Page</label>
                <input type="text" name="facebook" placeholder="https://www.facebook.com/..." v-model="organization.facebook" />
            </div>
            <div class="field">
                <label>Twitter Account</label>
                <input type="text" name="twitter" placeholder="https://www.twitter.com/..." v-model="organization.twitter" />
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['organization', 'countries', 'legalForms']
    }
</script>