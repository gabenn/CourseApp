require('./bootstrap');
import Vue from 'vue';

Vue.component('main-component', require('./components/MainComponent.vue').default);


const app = new Vue({
  el: '#app',
});