import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import Login from './components/Login.vue';
import UserList from './components/UserList.vue';
import NotFound from "./components/NotFound.vue";

const routes = [
    { path: '/', component: Home, name: 'home' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/users', component: UserList, name: 'user-list' },
    { path: '/:pathMatch(.*)', component: NotFound, name: 'not-found' }
];

const router = createRouter({
    history: createWebHistory(), // Use HTML5 History Mode for clean URLs
    routes,
    linkActiveClass: 'font-bold',
});

export default router;
