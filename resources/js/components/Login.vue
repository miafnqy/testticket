<template>
    <div class="w-1/2 shadow p-9">
        <form @submit.prevent="handleLogin" class="flex flex-col">
            <input v-model="email" type="email" placeholder="Email" required class="py-1 px-5 my-3 rounded-full border border-gray-500" />
            <input v-model="password" type="password" placeholder="Password" required class="py-1 px-5 my-3 rounded-full border border-gray-500" />
            <button type="submit" class="border border-gray-500 rounded-full lg:w-1/6 md:w-1/5 sm:w-1/3 w-1/2 hover:cursor-pointer">Login</button>
            <p v-if="error">{{ error }}</p>
            <p v-if="message">{{ message }}</p>
        </form>
    </div>
</template>

<script>
import axios from "axios";
// import api from '../api';

export default {
    data() {
        return {
            email: '',
            password: '',
            message: null,
            token: null,
            error: null,
        };
    },
    methods: {
        async handleLogin() {
            try {
                (await axios.get('/sanctum/csrf-cookie'));
                await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                })
                    .then(response => {
                        this.token = response.data.data.token;
                        localStorage.setItem('api_token', this.token);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                        this.message = response.data.message
                });
            } catch (e) {
                this.error = 'Login failed. Please check your credentials.';
            }
        },
    },
};
</script>
