<template>
    <div class="ui bottom attached tab">
        <h3 class="ui header dividing">Certifications</h3>

        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <th class="ten wide">Name</th>
                    <th class="five wide">Date</th>
                    <th class="one wide"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="certifications.length == 0">
                    <td colspan="5" class="center aligned">No certifications added</td>
                </tr>
                <tr v-for="certification in certifications">
                    <td>{{ certification.name }}</td>
                    <td>{{ certification.date }}</td>
                    <td class="collapsing">
                        <button type="button" class="ui mini red icon button" @click="removeCertification(certification)">
                            <i class="trash icon"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot v-if="!insertCertificationMode">
                <th colspan="5">
                    <div class="ui right floated small primary labeled icon button" @click="addCertification()">
                        <i class="marker icon"></i> Add Certification
                    </div>
                </th>
            </tfoot>
            <tfoot v-if="insertCertificationMode">
                <tr>
                    <th>
                        <select class="ui search dropdown" name="country_id" v-model="newCertification.type">
                            <option value="">Select Certification...</option>
                            <option v-for="c in certificationTypes" v-bind:value="c">{{ c.name }}</option>
                        </select>
                    </th>
                    <th>
                    </th>
                    <th class="collapsing">
                        <button type="button" class="ui mini basic green icon button" @click="saveCertification()" :disabled="!isCertificationValid">
                            <i class="checkmark icon"></i>
                        </button>
                        <button type="button" class="ui mini basic red icon button" @click="cancelCertification()"><i class="remove icon"></i></button>
                    </th>
                </tr>
            </tfoot>
        </table>

        <h3 class="ui header dividing">Awards</h3>

        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <th class="ten wide">Name</th>
                    <th class="five wide">Date</th>
                    <th class="one wide"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="awards.length == 0">
                    <td colspan="5" class="center aligned">No awards added</td>
                </tr>
                <tr v-for="award in awards">
                    <td>{{ award.name }}</td>
                    <td>{{ award.date }}</td>
                    <td class="collapsing">
                        <button type="button" class="ui mini red icon button" @click="removeAward(award)">
                            <i class="trash icon"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot v-if="!insertAwardMode">
                <th colspan="5">
                    <div class="ui right floated small primary labeled icon button" @click="addAward()">
                        <i class="marker icon"></i> Add Award
                    </div>
                </th>
            </tfoot>
            <tfoot v-if="insertAwardMode">
                <tr>
                    <th>
                        <select class="ui search dropdown" name="country_id" v-model="newAward.type">
                            <option value="">Select Award...</option>
                            <option v-for="a in awardTypes" v-bind:value="a">{{ a.name }}</option>
                        </select>
                    </th>
                    <th>
                    </th>
                    <th class="collapsing">
                        <button type="button" class="ui mini basic green icon button" @click="saveAward()" :disabled="!isAwardValid">
                            <i class="checkmark icon"></i>
                        </button>
                        <button type="button" class="ui mini basic red icon button" @click="cancelAward()"><i class="remove icon"></i></button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['certifications', 'awards', 'certificationTypes', 'awardTypes'],
        data() {
            return {
                insertCertificationMode: false,
                insertAwardMode: false,
                newCertification: {},
                newAward: {},
            };
        },
        computed: {
            isCertificationValid() {
                return !!this.newCertification.type.id;
            },
            isAwardValid() {
                return !!this.newAward.type.id;
            },
        },
        methods: {
            addCertification() {
                this.newCertification = {
                    type: {},
                    date: '',
                };
                this.insertCertificationMode = true;
            },
            cancelCertification() {
                this.insertCertificationMode = false;
            },
            removeCertification(certification) {
                this.$emit('removeCertification', certification);
            },
            saveCertification() {
                this.$emit('addCertification', this.newCertification);
                this.newCertification = {};
                this.insertCertificationMode = false;
            },
            addAward() {
                this.newAward = {
                    type: {},
                    date: '',
                };
                this.insertAwardMode = true;
            },
            cancelAward() {
                this.insertAwardMode = false;
            },
            removeAward(award) {
                this.$emit('removeAward', award);
            },
            saveAward() {
                this.$emit('addAward', this.newAward);
                this.newAward = {};
                this.insertAwardMode = false;
            },
        }
    }
</script>