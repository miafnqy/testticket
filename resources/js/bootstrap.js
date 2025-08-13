import axios from 'axios';
import { createApp } from 'vue';
import App from './components/App.vue';
import UserList from "./components/UserList.vue";
import router from './router';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp(App);
app.use(router);
app.component('user-list', UserList);
app.mount('#app');
