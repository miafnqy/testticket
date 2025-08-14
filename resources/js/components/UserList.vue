<template>
    <div class="flex shadow px-28 flex-col">
        <h1 class="font-bold pb-8 mx-auto">User List</h1>
        <ul class=" flex flex-col text-sm mr-auto p-28">
            <li v-for="user in users" :key="user.id" class="px-3 py-1 flex">
                <span class="px-2">{{ user.name }}</span> - <i class="px-3">{{ user.email }}</i>
                <router-link :to="`/users/${user.id}/edit`" class="ml-auto border border-gray-300 rounded-full text-xs px-3 py-0">Edit</router-link>
                <button @click="deleteUser(user.id)" class="ml-5 border border-gray-300 rounded-full text-xs px-3 py-0">Delete</button>
            </li>
        </ul>
        <div class="px-5 py-28">
            <router-link to="/users/create" class="">Create New User</router-link>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            users: [
                {
                    id: 1,
                    name: 'Nate',
                    email: 'some@mail.com'
                },
                {
                    id: 2,
                    name: 'Josh',
                    email: 'anotherone@sample.com'
                }
            ],
        };
    },
    mounted() {

    },
    async created() {
        const response = await axios.get('/api/users');
        this.users = response.data.data;
    },
    methods: {
        async deleteUser(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                await axios.delete(`/api/users/${id}`);
                this.users = this.users.filter(user => user.id !== id);
            }
        },
    },
};
</script>
