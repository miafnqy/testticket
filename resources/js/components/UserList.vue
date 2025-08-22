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
    </div>
</template>

<script>
// import axios from 'axios';

import {mapActions, mapGetters} from "vuex";

export default {
    data() {
        return {
            //
        };
    },
    mounted() {

    },
    computed: {
        ...mapGetters(['authenticated', 'user', 'users'])
    },
    async created() {
        await axios.get('/api/users')
            .then(response => {
                this.setUsers(response.data.data);
            })
            .catch(e => console.log(e.toString()));
    },
    methods: {
        ...mapActions(['setUsers', 'removeUser', 'logout']),
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

            if (user.id === this.user.id) {
                return true;
            }

            return false;
        },
    },
};
</script>
