require('./bootstrap');
import Vue from 'vue';

Vue.component('add-component', require('./components/AddComponent.vue').default);
Vue.component('test-component', require('./components/TestComponent.vue').default);


const app = new Vue({
  el: '#app',
});