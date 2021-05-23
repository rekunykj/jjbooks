//import libraries
import Vue from 'vue'
import {BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
//import 'bootswatch/dist/sketchy/bootstrap.css';
import 'bootswatch/dist/superhero/bootstrap.css';
// import 'bootstrap-vue/dist/bootstrap-vue.css';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuelidate from 'vuelidate'

//add libraries to vue context
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueAxios, axios);
Vue.use(Vuelidate);
//import components from src folder
import App from './App.vue';
import router from './router'; //declared and exported in the index.js file
import GlobalMixin from "@/mixins/global-mixin";

Vue.config.productionTip = false

Vue.mixin(GlobalMixin);

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
