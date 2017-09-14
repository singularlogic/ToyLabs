<template>
    <!-- Organization -->
    <form class="ui form" method="POST" action="/organization/edit" ref="orgForm">
        <input type="hidden" name="_token" :value="$parent.crsf" />

        <div class="ui pointing secondary menu">
            <a class="item orange active" data-tab="general">General</a>
            <a class="item orange" data-tab="facilities">Facilities</a>
            <a class="item orange" data-tab="services">Services &amp; Prices</a>
            <a class="item orange" data-tab="technical">Technical</a>
            <a class="item orange" data-tab="certifications">Certifications</a>
            <a class="item orange" data-tab="awards">Awards</a>
        </div>

        <general-tab class="active" data-tab="general"
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

        <services-tab data-tab="services"
            :services="services"
        ></services-tab>

        <technical-tab data-tab="technical"
            :technical="technical"
        ></technical-tab>

        <certifications-tab data-tab="certifications"
            :certifications="certifications"
        ></certifications-tab>


        <awards-tab data-tab="awards"
            :awards="awards"
        ></awards-tab>

        <div class="ui divider"></div>

        <input type="hidden" name="id" :value="_id" />
        <input type="hidden" name="facilities" ref="facilities" />
        <button type="button" class="ui orange submit right floated button" @click="submit">{{ submitText }}</button>
        <a href="/dashboard" class="ui default right floated button">Cancel</a>
    </form>
</template>

<script>
    import { GeneralTab, FacilitiesTab, ServicesTab, TechnicalTab, CertificationsTab, AwardsTab } from './organizations';

    export default {
        props: ['_countries', '_legalForms', '_id', '_organization', '_facilities'],
        components: {
            GeneralTab, FacilitiesTab, ServicesTab, TechnicalTab, CertificationsTab, AwardsTab,
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
                facilities: this._facilities === null ? [] : this._facilities,
                services: {},
                technical: {},
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