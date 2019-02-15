
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
const Lang = require('lang.js');
import translations from '../../public/js/messages.js';

const lang = new Lang();
lang.setLocale('nl');
lang.setMessages(translations);

Vue.filter('trans', (...args) => {
    return lang.get(...args);
});

import SvgIcon from 'vue-svgicon';
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('pull-down', require('./components/PullDown.vue').default);
Vue.component('task-card', require('./components/TaskCard.vue').default);
Vue.component('task-form', require('./components/TaskForm.vue').default);
Vue.component('svg-icon', require('vue-svgicon').default);
import './icons/icon-user';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
