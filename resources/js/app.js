require('./bootstrap');
import Vue from 'vue';

Vue.component('add-component', require('./components/AddComponent.vue').default);
Vue.component('edit-component', require('./components/EditComponent.vue').default);


const app = new Vue({
  el: '#app',
});