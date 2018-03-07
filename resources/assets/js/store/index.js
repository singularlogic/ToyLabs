import Vue from 'vue';
import Vuex from 'vuex';
import * as getters from './getters';
import * as actions from './actions';
import * as mutations from './mutations';

const state = {
    user: window.Laravel.user,
    messages: [],
    notifications: [],
    pendingRatings: [],
    unreadMessagesCounter: 0,
    activeThread: null,
    activePartner: null,
};

const store = new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
    strict: true
});

export default store;
