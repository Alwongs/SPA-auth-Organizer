require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router';
import router from './routes.js';
import App from './components/App';
import Api from './api.js';
import Auth from './auth.js';
import Vuelidate from 'vuelidate'

Vue.use(Vuelidate)

window.api = new Api();
window.auth = new Auth();

Vue.use(VueRouter);

window.Event = new Vue;

const app = new Vue({
    el: '#app',
    components: {App},
    router
});
