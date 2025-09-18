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
        addUser(state, user) {
            state.users.push(user);
        },
        setUsers(state, users) {
            state.users = users;
        },
        updateUser(state, updatedUser) {
            const index = state.users.findIndex(user => user.id === updatedUser.id);

            state.users.splice(index, 1, {
                ...state.users[index],
                ...updatedUser
            });
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
        addUser({ commit }, user) {
            commit('addUser', user);
        },
        setUsers({ commit }, users) {
            commit('setUsers', users);
        },
        updateUser({ commit }, userData) {
            commit('updateUser', userData);
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
