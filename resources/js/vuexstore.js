import {createStore} from "vuex";
import axios from "axios";

const store = createStore({
    state: {
        authenticated: !!localStorage.getItem('api_token'),
        user: JSON.parse(localStorage.getItem('authenticatedUser')),
        users: null,
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
        },
        setUser(state, user) {
            state.user = user;
        },
        setUsers(state, users) {
            state.users = users;
        },
        removeUser(state, userId) {
            state.users = state.users.filter(user => user.id !== userId);
        },
        logout(state) {
            state.authenticated = false;
            state.user = null;
            state.users = null;
        }
    },
    actions: {
        login({ commit }) {
            commit('setAuthenticated', true);
        },
        setUser({ commit }, user) {
            commit('setUser', user);
        },
        setUsers({ commit }, users) {
            commit('setUsers', users);
        },
        removeUser({ commit }, userId) {
            commit('removeUser', userId);
        },
        logout({ commit }) {
            commit('logout');
        }
    },
    getters: {
        authenticated: (state) => state.authenticated,
        user: (state) => state.user,
        users: (state) => state.users,
    }
});

export default store;
