<template>
    <!-- Organization -->
    <form class="ui form" method="POST" action="/organization/edit" ref="orgForm">
        <input type="hidden" name="_token" :value="$parent.crsf" />

        <div class="ui pointing secondary menu">
            <a class="item orange active" data-tab="general">General</a>
            <a class="item orange" data-tab="facilities">Facilities</a>
            <a class="item orange" data-tab="services">Services &amp; Pricing</a>
            <a class="item orange" data-tab="certifications">Certifications &amp; Awards</a>
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
            :competencies="_competencies"
            :markets="_markets"
            :categories="_categories"
            :scales="scales"
            :paymentTypes="_paymentTypes"
        ></services-tab>

        <certifications-tab data-tab="certifications"
            :certification-types="_certificationtypes"
            :award-types="_awardtypes"
            :certifications="certifications"
            :awards="awards"
            @addAward="addAward"
            @addCertification="addCertification"
            @removeAward="removeAward"
            @removeCertification="removeCertification"
        ></certifications-tab>


        <div class="ui divider"></div>

        <input type="hidden" name="id" :value="_id" />
        <input type="hidden" name="facilities" ref="facilities" />
        <input type="hidden" name="services" ref="services" />
        <input type="hidden" name="awards" ref="awards" />
        <input type="hidden" name="certifications" ref="certifications" />
        <button type="button" class="ui orange submit right floated button" @click="submit">{{ submitText }}</button>
        <a href="/dashboard" class="ui default right floated button">Cancel</a>
    </form>
</template>

<script>
    import moment from 'moment';
    import { GeneralTab, FacilitiesTab, ServicesTab, CertificationsTab } from './organizations';

    export default {
        props: [
            '_countries', '_legalForms', '_id', '_organization', '_facilities', '_competencies', '_markets',
            '_categories', '_services', '_paymentTypes', '_awardtypes', '_certificationtypes', '_certifications', '_awards'
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
                    { id: 'prototype', name: 'Prototyping Only' },
                    { id: 'small', name: 'Small' },
                    { id: 'medium', name: 'Medium' },
                    { id: 'large', name: 'Large' },
                    { id: 'xlarge', name: 'Extra Large' },
                ],
                facilities: this._facilities === null ? [] : this._facilities,
                services: this._services,
                certifications: this._certifications,
                awards: this._awards,
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
                let awards = [];
                this.awards.reduce((arr, obj) => {
                        arr.push({
                            award_id: obj.id,
                            awarded_at: moment(obj.pivot.awarded_at).format('YYYY-MM-DD HH:mm:ss'),
                        });
                        return arr;
                }, awards);
                this.$refs.awards.value = JSON.stringify(awards);

                let certifications = [];
                this.certifications.reduce((arr, obj) => {
                        arr.push({
                            certification_id: obj.id,
                            certified_at: moment(obj.pivot.certified_at).format('YYYY-MM-DD HH:mm:ss'),
                        });
                        return arr;
                }, certifications);
                this.$refs.certifications.value = JSON.stringify(certifications);
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
            addAward(award) {
                this.awards.push(award);
            },
            addCertification(certification) {
                this.certifications.push(certification);
            },
            removeAward(award) {
                const index = this.awards.indexOf(award);
                if (~index) {
                    this.awards.splice(index, 1);
                }
            },
            removeCertification(certification) {
                const index = this.certifications.indexOf(certification);
                if (~index) {
                    this.certifications.splice(index, 1);
                }
            },
        }
    }
</script>