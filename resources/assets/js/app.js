
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('dashboard', require('./components/Admin/Dashboard.vue'));
Vue.component('drdr-admin', require('./components/Admin/DrdrAdmin.vue'));
Vue.component('ddr-admin', require('./components/Admin/DdrAdmin.vue'));

Vue.component('users', require('./components/Users.vue'));
Vue.component('companies', require('./components/Companies.vue'));
Vue.component('departments', require('./components/Departments.vue'));
Vue.component('roles', require('./components/Roles.vue'));
Vue.component('permissions', require('./components/Permissions.vue'));


Vue.component('drdr', require('./components/Drdr/Drdr.vue'));
Vue.component('drdr-review-form', require('./components/Drdr/DrdrReviewForm.vue'));
Vue.component('drdr-details-approved', require('./components/Drdr/DrdrDetailsApproved.vue'));

Vue.component('ddr', require('./components/Ddr/Ddr.vue'));
Vue.component('ccir', require('./components/Ccir/Ccir.vue'));
Vue.component('ncn', require('./components/Ncn/Ncn.vue'));
Vue.component('userform', require('./components/Userform.vue'));
Vue.component('drdrform', require('./components/Drdrform.vue'));
Vue.component('drdredit', require('./components/Drdredit.vue'));

Vue.component('drdrapprove', require('./components/Drdrapprove.vue'));
Vue.component('ddrform', require('./components/Ddrform.vue'));
Vue.component('ccirform', require('./components/Ccirform.vue'));
Vue.component('ncnform', require('./components/Ncnform.vue'));




const app = new Vue({
    el: '#app'
});
