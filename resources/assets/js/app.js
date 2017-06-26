import './bootstrap';
import Vue from 'vue';

// Import VueJS Components
import Profile from './components/Profile.vue';
import Organization from './components/Organization.vue';
import NotificationArea from './components/NotificationArea.vue';

/**
 * Navbar transition for the homepage
 */
$(document).ready(function() {
    // fix menu when passed
    $('.masthead').visibility({
        once: false,
        onBottomPassed: function() {
            $('.fixed.menu').transition('fade in');
        },
        onBottomPassedReverse: function() {
            $('.fixed.menu').transition('fade out');
        }
    });

    // Initialize UI components
    $('.ui.dropdown').dropdown();
    $('.message .close').on('click', function() {
        $(this).closest('.message').transition('fade');
    });
    $('.ui.rating').rating();
    $('#notificationsIcon').popup({
        popup: $('#notificationsPopup'),
        on: 'click',
        position: 'bottom center',
        variation: 'very wide',
    });
});

const app = new Vue({
    el: '#app',
    data() {
        return {
            crsf: document.querySelector('#token').getAttribute('value')
        };
    },
    components: {
        Profile,
        Organization,
        NotificationArea,
    }
});