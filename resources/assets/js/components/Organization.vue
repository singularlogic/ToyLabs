<template>
    <!-- Organization -->
    <form class="ui form" method="POST" action="/organization/edit" ref="orgForm">
        <input type="hidden" name="_token" :value="$parent.crsf" />

        <div class="ui pointing secondary menu">
            <a class="item orange" data-tab="general">General</a>
            <a class="item orange" data-tab="facilities">Facilities</a>
            <a class="item orange active" data-tab="services">Services &amp; Pricing</a>
            <a class="item orange" data-tab="certifications">Certifications &amp; Awards</a>
        </div>

        <general-tab data-tab="general"
            :organization="organization"
            :countries="_countries"
            :legalForms="_legalForms"
        ></general-tab>

        <facilities-tab data-tab="facilities"
            :facilities="facilities"
            :countries="_countries"
            @add="addFacility"
            @remove="removeFacility"
        ></facilities-tab>

        <services-tab class="active" data-tab="services"
            :services="services"
            :competencies="_competencies"
            :markets="_markets"
            :categories="_categories"
            :scales="scales"
            :payments="payments"
        ></services-tab>

        <certifications-tab data-tab="certifications"
            :certifications="certifications"
            :awards="awards"
        ></certifications-tab>


        <div class="ui divider"></div>

        <input type="hidden" name="id" :value="_id" />
        <input type="hidden" name="facilities" ref="facilities" />
        <input type="hidden" name="services" ref="services" />
        <button type="button" class="ui orange submit right floated button" @click="submit">{{ submitText }}</button>
        <a href="/dashboard" class="ui default right floated button">Cancel</a>
    </form>
</template>

<script>
    import { GeneralTab, FacilitiesTab, ServicesTab, CertificationsTab } from './organizations';

    export default {
        props: [
            '_countries', '_legalForms', '_id', '_organization', '_facilities', '_competencies', '_markets',
            '_categories', '_services'
        ],
        components: {
            GeneralTab, FacilitiesTab, ServicesTab, CertificationsTab,
        },
        data () {
            let organization;

            if (this._organization === null) {
                organization = {
                    name: '',
                    legal_name: '',
                    legal_form: '',
                    description: '',
                    address: '',
                    postal_code: '',
                    city: '',
                    country: '',
                    po_box: '',
                    phone: '',
                    fax: '',
                    website_url: '',
                    instagram: '',
                    facebook: '',
                    twitter: '',
                };
            } else {
                organization = this._organization;
            }

            return {
                organization: organization,
                scales: [
                    { id: 1, name: 'Prototyping Only' },
                    { id: 2, name: 'Small' },
                    { id: 3, name: 'Medium' },
                    { id: 4, name: 'Large' },
                    { id: 5, name: 'Extra Large' },
                ],
                payments: [
                    { id: 1, name: 'Bank Transfer' },
                    { id: 2, name: 'Check' },
                    { id: 3, name: 'Credit Card' },
                    { id: 4, name: 'Paypal' },
                    { id: 5, name: 'Bitcoin' },
                ],
                facilities: this._facilities === null ? [] : this._facilities,
                services: this._services,
                certifications: [],
                awards: [],
            }
        },
        mounted() {
            this.$nextTick(() => {
                $('.menu .item').tab();
            });
        },
        computed: {
            submitText() {
                return this._id === 0 ? 'Create' : 'Update';
            },
        },
        methods: {
            submit() {
                this.$refs.services.value = JSON.stringify(this.services);
                this.$refs.facilities.value = JSON.stringify(this.facilities);
                this.$refs.orgForm.submit();
            },
            addFacility(facility) {
                this.facilities.push(facility);
            },
            removeFacility(facility) {
                const index = this.facilities.indexOf(facility);
                if (~index) {
                    this.facilities.splice(index, 1);
                }
            },
        }
    }
</script>