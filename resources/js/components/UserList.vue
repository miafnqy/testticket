<template>
    <div class="flex shadow px-28 flex-col">
        <h1 class="font-bold pb-8 mx-auto">User List</h1>
        <ul class=" flex flex-col text-sm mr-auto px-28">
            <li v-for="user in users" :key="user.id" class="py-1 flex">
                <span class="px-3">{{ user.name }}</span> - <i class="px-3">{{ user.email }}</i>
                <button
                    :disabled="!canUpdate(user)"
                    :class="{'border-red-500': !canUpdate(user)}"
                    class="ml-auto border border-gray-300 rounded-full text-xs px-3 py-0"
                    @click="openEditModal(user)"
                >Edit</button>
                <button
                    :disabled="!canUpdate(user)"
                    @click="deleteUser(user.id)"
                    :class="{'border-red-500': !canUpdate(user)}"
                    class="ml-5 border border-gray-300 rounded-full text-xs px-3 py-0"
                >Delete</button>
            </li>
        </ul>
        <div v-if="authenticated && ['manager', 'admin'].includes(this.user.role.name)" class="py-12 px-28">
            <div class="border flex justify-center w-1/2 border-gray-500 rounded-full">
                <router-link to="/users/create" class="">Create New User</router-link>
            </div>
        </div>
        <Paginate :links="links" v-if="users"></Paginate>
        <teleport to="body">
            <Modal :user="editingUser" @close="closeModal"></Modal>
        </teleport>
    </div>
</template>

<script>
// import axios from 'axios';
import Paginate from "./Paginate.vue";
import {mapActions, mapGetters} from "vuex";
import Modal from "./Modal.vue";

export default {
    components: {Modal, Paginate},
    data() {
        return {
            links: [],
            editingUser: null,
        };
    },
    mounted() {

    },
    computed: {
        ...mapGetters(['authenticated', 'user', 'users'])
    },
    async created() {

    },
    watch: {
        '$route.query.page': {
            immediate: true,
            handler(newPage) {
                if (this.authenticated) {
                    this.getUsers();
                }
            }
        }
    },
    methods: {
        ...mapActions(['setUsers', 'removeUser', 'logout']),
        async getUsers() {
            await axios.get('/api/users?' + new URLSearchParams(this.$route.query).toString())
                .then(response => {
                    this.setUsers(response.data.data);
                    this.links = response.data.links;
                })
                .catch(e => console.log(e.toString()));
        },
        async deleteUser(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                await axios.delete(`/api/users/${id}`)
                    .then(response => {
                        this.removeUser(id);
                        if (this.user.id === id) {
                            this.logout();
                            localStorage.clear();
                        }
                    });
            }
        },
        canUpdate(user) {
            if (this.user.role.id === 1) {
                return true;
            }

            return user.id === this.user.id;
        },
        openEditModal(user) {
            this.editingUser = user;
        },
        closeModal() {
            this.editingUser = null;
        }
    },
};
</script>
