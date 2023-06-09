/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuetify = require('vuetify');

Vue.use(Vuetify);

const opts = {
    icons: {
        iconfont: 'fa', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
    },
};

export default new Vuetify(opts);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('role-ability-assign', require('./components/containers/RoleAbilitySelectContainer.vue').default);
Vue.component('user-client-role-ability-assign', require('./components/containers/UserClientRoleAbilitySelectContainer.vue').default);
Vue.component('push-notifications', require('./components/containers/PushNotificationsContainer.vue').default);

Vue.component('text-with-file-upload', require('./components/cms/UploadFileToURL.vue').default);
Vue.component('image-scheduler', require('./components/cms/ImageScheduler.vue').default);
//Vue.component('checkbox-grid', require('./components/presenters/CheckboxGridComponent.vue').default);
Vue.component('cms-visitor-activity', require('./components/containers/VisitorActivityDataComponent.vue').default);
Vue.component('list-widget', require('./components/presenters/listWidgetComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    el: '#app',
    data() {
        return {
            themeColor: ''
        };
    }
});
