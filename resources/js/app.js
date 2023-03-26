require('./bootstrap');
import { createApp } from 'vue'
import App from './app.vue'
import router from './plugins/route.js'
import TransModel from './shared/filters/trans-model';
import i18n from './plugins/i18n/i18n';
import store from './shared/store';
import interceptor from "./shared/interceptor";
import Toaster from "@meforma/vue-toaster";
const app = createApp(App);
app.use(i18n);
app.use(router);
app.use(Toaster);
app.config.globalProperties.$filters = TransModel;
app.provide("store", store);
app.provide('toast', app.config.globalProperties.$toast);
interceptor();
app.mount('#app');