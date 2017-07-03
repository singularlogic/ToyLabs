import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import Echo from 'laravel-echo';
import VueTimeago from 'vue-timeago';
import Pusher from 'pusher-js';

Vue.use(VueTimeago, {
    locale: 'en-US',
    locales: { 'en-US': require('json-loader!vue-timeago/locales/en-US.json') }
});
Vue.use(VueRouter);

window.Vue = Vue;
window.VueRouter = VueRouter;

// Configure Laravel Echo
const { key, cluster } = window.Laravel.pusher;
if (key) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: key,
        cluster: cluster,
        encrypted: true,
    });

    axios.interceptors.request.use(
         config => {
            config.headers['X-Socket-ID'] = window.Echo.socketId();
            return config;
        },
        error => Promise.reject(error)
    );
}

window.axios = axios
