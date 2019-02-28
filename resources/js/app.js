
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// translation
const Lang = require('lang.js');
import translations from '../../public/js/messages.js';

const lang = new Lang();
lang.setLocale('nl');
lang.setMessages(translations);

Vue.filter('trans', (...args) => {
    return lang.get(...args);
});

// portal-vue
import PortalVue from 'portal-vue';
Vue.use(PortalVue);


Vue.component('action-card', require('./components/ActionCard.vue').default);
Vue.component('group-invite', require('./components/GroupInvite.vue').default);
Vue.component('pull-down', require('./components/PullDown.vue').default);
Vue.component('tabbed-cards', require('./components/TabbedCards.vue').default);
Vue.component('task-card', require('./components/TaskCard.vue').default);
Vue.component('task-form', require('./components/TaskForm.vue').default);

// icons
import SvgIcon from 'vue-svgicon';
Vue.component('svg-icon', SvgIcon);
import './icons/icon-user';

const app = new Vue({
    el: '#app'
});
