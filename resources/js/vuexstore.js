import {createStore} from "vuex";
import axios from "axios";

const store = createStore({
    state: {
        authenticated: !!localStorage.getItem('api_token'),
        user: JSON.parse(localStorage.getItem('authenticatedUser')),
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
        },
        setUser(state, user) {
            state.user = user;
        },
        logout(state) {
            state.authenticated = false;
        }
    },
    actions: {
        login({ commit }) {
            commit('setAuthenticated', true);
        },
        setUser({ commit }, user) {
            commit('setUser', user);
        },
        logout({ commit }) {
            commit('logout');
        }
    },
    getters: {
        authenticated: (state) => state.authenticated,
        user: (state) => state.user,
    }
});

export default store;
