import {createStore} from "vuex";

const store = createStore({
    state: {
        authenticated: !!localStorage.getItem('api_token'),
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
        },
        logout(state) {
            state.authenticated = false;
        }
    },
    actions: {
        login({ commit }) {
            commit('setAuthenticated', true);
        },
        logout({ commit }) {
            commit('logout');
        }
    },
    getters: {
        authenticated: (state) => state.authenticated,
    }
});

export default store;
