require('./bootstrap');
import Vue from 'vue';
import Notifications from 'vue-notification';

Vue.use(Notifications);

Vue.component('add-component', require('./components/AddComponent.vue').default);
Vue.component('test-component', require('./components/TestComponent.vue').default);

Vue.notify('test');

const app = new Vue({
  el: '#app',
});