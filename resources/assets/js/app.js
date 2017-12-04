import './bootstrap';
import Vue from 'vue';
import store from './store';

// Import VueJS Components
import Profile from './components/Profile.vue';
import Organization from './components/Organization.vue';
import NotificationArea from './components/NotificationArea.vue';
import NotificationsPage from './components/NotificationsPage.vue';
import ProductsGrid from './components/ProductsGrid.vue';
import Comments from './components/Comments.vue';
import Likes from './components/Likes.vue';
import ProductList from './components/ProductList.vue';
import ProductCreate from './components/ProductCreate.vue';
import DesignCreate from './components/DesignCreate.vue';
import PrototypeCreate from './components/PrototypeCreate.vue';
import DesignsTable from './components/DesignsTable.vue';
import PrototypesTable from './components/PrototypesTable.vue';
import Gallery from './components/Gallery.vue';
import Search from './components/Search.vue';
import Contact from './components/collaborations/Contact.vue';
import CollaborationsPage from './components/collaborations/CollaborationsPage.vue';
import FeedbackPage from './components/FeedbackPage.vue';

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
    $('#orgPage .menu .item').tab();
    $('#dashboard .menu .item').tab();
});

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [],
});

Vue.use(require('vue-moment'));

const app = new Vue({
    store,
    router,
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
        NotificationsPage,
        CollaborationsPage,
        FeedbackPage,
        ProductsGrid,
        Comments,
        Likes,
        ProductList,
        ProductCreate,
        DesignCreate,
        PrototypeCreate,
        DesignsTable,
        PrototypesTable,
        Gallery,
        Search,
        Contact,
    }
});
