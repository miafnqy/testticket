import {createStore} from "vuex";
import axios from "axios";

const store = createStore({
    state: {
        authenticated: !!localStorage.getItem('api_token'),
        user: JSON.parse(localStorage.getItem('authenticatedUser')),
        users: null,
        roles: null,
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
        addRole(state, role) {
            state.roles.push(role);
        },
        setUsers(state, users) {
            state.users = users;
        },
        setRoles(state, roles) {
            state.roles = roles;
        },
        updateUser(state, updatedUser) {
            const index = state.users.findIndex(user => user.id === updatedUser.id);

            state.users.splice(index, 1, {
                ...state.users[index],
                ...updatedUser
            });
        },
        updateRole(state, updatedRole) {
            const index = state.roles.findIndex(role => role.id === updatedRole.id);

            state.roles.splice(index, 1, {
                ...state.roles[index],
                ...updatedRole
            });
        },
        removeUser(state, userId) {
            state.users = state.users.filter(user => user.id !== userId);
        },
        removeRole(state, roleId) {
            state.roles = state.roles.filter(role => role.id !== roleId);
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
        addRole({ commit }, role) {
            commit('addRole', role);
        },
        setUsers({ commit }, users) {
            commit('setUsers', users);
        },
        setRoles({ commit }, roles) {
            commit('setRoles', roles);
        },
        updateUser({ commit }, userData) {
            commit('updateUser', userData);
        },
        updateRole({ commit }, roleData) {
            commit('updateRole', roleData);
        },
        removeUser({ commit }, userId) {
            commit('removeUser', userId);
        },
        removeRole({ commit }, roleId) {
            commit('removeRole', roleId);
        },
        logout({ commit }) {
            commit('logout');
        }
    },
    getters: {
        authenticated: (state) => state.authenticated,
        user: (state) => state.user,
        users: (state) => state.users,
        roles: (state) => state.roles,
    }
});

export default store;
