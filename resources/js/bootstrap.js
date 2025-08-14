import axios from 'axios';
import { createApp } from 'vue';
import App from './components/App.vue';
import UserList from "./components/UserList.vue";
import router from './router';
import store from './vuexstore.js';
window.axios = axios;

axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

const app = createApp(App);
app.use(router);
app.use(store);
app.component('user-list', UserList);
app.mount('#app');
