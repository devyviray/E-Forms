
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
Vue.component('ncn-admin', require('./components/Admin/NcnAdmin.vue'));
Vue.component('ccir-admin', require('./components/Admin/CcirAdmin.vue'));
Vue.component('drdr-details-page-admin', require('./components/Admin/DrdrDetailsPageAdmin.vue'));
Vue.component('ddr-details-page-admin', require('./components/Admin/DdrDetailsPageAdmin.vue'));
Vue.component('ncn-details-page-admin', require('./components/Admin/NcnDetailsPageAdmin.vue'));
Vue.component('ccir-details-page-admin', require('./components/Admin/CcirDetailsPageAdmin.vue'));

Vue.component('users', require('./components/Users.vue'));
Vue.component('companies', require('./components/Companies.vue'));
Vue.component('departments', require('./components/Departments.vue'));
Vue.component('roles', require('./components/Roles.vue'));
Vue.component('permissions', require('./components/Permissions.vue'));


Vue.component('drdr', require('./components/Drdr/Drdr.vue'));
Vue.component('drdrform', require('./components/Drdr/Drdrform.vue'));
Vue.component('drdr-edit', require('./components/Drdr/DrdrEdit.vue'));
Vue.component('drdr-review-form', require('./components/Drdr/DrdrReviewForm.vue'));
Vue.component('drdr-details-approved', require('./components/Drdr/DrdrDetailsApproved.vue'));

Vue.component('drdrapprove', require('./components/Drdrapprove.vue'));

Vue.component('ddr', require('./components/Ddr/Ddr.vue'));
Vue.component('ddrform', require('./components/Ddr/Ddrform.vue'));
Vue.component('ddr-edit', require('./components/Ddr/DdrEdit.vue'));
Vue.component('ddr-details-approved', require('./components/Ddr/DdrDetailsApproved.vue'));
Vue.component('ddr-approve-form', require('./components/Ddr/DdrApproveForm.vue'));


Vue.component('ncn', require('./components/Ncn/Ncn.vue'));
Vue.component('ncnform', require('./components/Ncn/Ncnform.vue'));

Vue.component('ccir', require('./components/Ccir/Ccir.vue'));
Vue.component('userform', require('./components/Userform.vue'));

Vue.component('ccirform', require('./components/Ccirform.vue'));




const app = new Vue({
    el: '#app'
});
