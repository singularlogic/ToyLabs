<template>
    <div>
        <div class="ui info message" v-if="numberOfPendingRatings == 0">
            <p>You have no collaborations to be rated!</p>
        </div>
        <div class="ui middle aligned divided list" v-else>
            <div class="item" v-for="rating in pendingRatings" :key="rating.id">
                <div class="right floated content">
                    <button class="ui small basic blue button" @click="showRatingPopup(rating)">Rate</button>
                </div>
                <div class="content">
                    <strong>{{ rating.organization.name }}</strong>
                    <div class="description">
                        <em>For</em> {{ rating.collaboration.collaboratable.type }}: {{ rating.collaboration.collaboratable.title }}
                    </div>
                </div>
            </div>
        </div>

        <div class="ui small modal" id="ratingModal" v-if="activeRating">
            <div class="header">Feedback for {{ activeRating.organization.name }}</div>
            <div class="content">
                <p>Please rate your collaboration with <strong>{{ activeRating.organization.name }}</strong> on {{ activeRating.collaboration.collaboratable.type }} <strong>{{ activeRating.collaboration.collaboratable.title }}</strong>.</p>

                <div class="ui middle aligned divided list">
                    <div class="item">
                        <div class="right floated content">
                            <div class="ui huge star rating" data-max-rating="5" id="rating_1"></div>
                        </div>
                        <div class="content">
                            <h3>Quality</h3>
                        </div>
                    </div>
                    <div class="item">
                        <div class="right floated content">
                            <div class="ui huge star rating" data-max-rating="5" id="rating_2"></div>
                        </div>
                        <div class="content">
                            <h3>Cooperation</h3>
                        </div>
                    </div>
                    <div class="item">
                        <div class="right floated content">
                            <div class="ui huge star rating" data-max-rating="5" id="rating_3"></div>
                        </div>
                        <div class="content">
                            <h3>Communication</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui cancel basic button">Cancel</div>
                <div class="ui approve green button">Submit</div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import { mapGetters } from 'vuex';

export default {
    data() {
        return {
            activeRating: null,
            rating_1: 0,
            rating_2: 0,
            rating_3: 0,
        };
    },
    computed: {
        ...mapGetters([
            'numberOfPendingRatings', 'pendingRatings'
        ]),
    },
    methods: {
        showRatingPopup(rating) {
            this.activeRating = rating;
            this.$nextTick(() => {
                $('#rating_1').rating({
                    onRate: (rating) => {
                        this.rating_1 = rating;
                    },
                });
                $('#rating_2').rating({
                    onRate: (rating) => {
                        this.rating_2 = rating;
                    },
                });
                $('#rating_3').rating({
                    onRate: (rating) => {
                        this.rating_3 = rating;
                    },
                });
                $('#ratingModal').modal({
                    closable: false,
                    onApprove: () => {
                        this.$store.dispatch('submitFeedback', {
                            id: this.activeRating.id,
                            rating_1: this.rating_1,
                            rating_2: this.rating_2,
                            rating_3: this.rating_3,
                        });
                    },
                    onDeny: () => {
                        this.activeRating = null;
                        this.rating_1 = 0;
                        this.rating_2 = 0;
                        this.rating_3 = 0;
                    },
                }).modal('show');
            });
        },
    },
};
</script>