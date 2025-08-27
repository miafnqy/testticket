<template>
    <div class="flex shadow px-28 flex-col">
        <h1 class="font-bold pb-8 mx-auto">User List</h1>
        <ul class=" flex flex-col text-sm mr-auto px-28">
            <li v-for="user in users" :key="user.id" class="py-1 flex">
                <span>{{ user.name }}</span> - <i class="px-3">{{ user.email }}</i>
                <button
                    :disabled="!canUpdate(user)"
                    :class="{'border-red-500': !canUpdate(user)}"
                    class="ml-auto border border-gray-300 rounded-full text-xs px-3 py-0"
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
            <div class="fixed inset-0 size-full bg-black/75 flex justify-center items-center">
                <div class="rounded-lg bg-white p-9 flex flex-col justify-center items-center">
                    <h1 class="pb-3">Hello, World!</h1>
                    <form @submit.prevent="submitForm" class="flex flex-col">
                        <div class="py-2 flex justify-between">
                            <label for="name" class="pr-3">Name:</label>
                            <input class="rounded-full border border-gray-500 px-5" id="name" v-model="user.name" type="text" required />
                        </div>

                        <div class="py-2 pb-5 flex justify-between">
                            <label for="email" class="pr-3">Email:</label>
                            <input class="rounded-full border border-gray-500 px-5" id="email" v-model="user.email" type="email" required />
                        </div>

                        <button type="submit" class="border border-gray-300 rounded-full text-xs px-3 py-2">Update</button>
                    </form>
                </div>
            </div>
        </teleport>
    </div>
</template>

<script>
// import axios from 'axios';
import Paginate from "./Paginate.vue";
import {mapActions, mapGetters} from "vuex";

export default {
    components: {Paginate},
    data() {
        return {
            links: []
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
    },
};
</script>
