require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import BootstrapVue from 'bootstrap-vue';
import router from './router';
import Toasted from 'vue-toasted';
import VueSwal from 'vue-swal';
import Vuetable from 'vuetable-2';
import {ServerTable, ClientTable, Event} from 'vue-tables-2';

Vue.use(VueSwal);  
Vue.config.productionTip = false
Vue.use(Toasted, {
  duration: 1000
})

Vue.use(VueAxios, axios);
Vue.component('App', require('./App.vue'));
Vue.use(BootstrapVue);
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';

Vue.router = router;
Vue.use(require('websanova/vue-auth'), {
   notFoundRedirect: { path: '/dashboard' },
   auth: require('websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
Vue.use(ServerTable);

App.router = Vue.router
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: {
    App,
    Vuetable
  }
})