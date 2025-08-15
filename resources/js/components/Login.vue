<template>
    <div class="w-1/2 shadow p-9">
        <form @submit.prevent="handleLogin" v-if="!authenticated" class="flex flex-col">
            <input v-model="email" type="email" placeholder="Email" required class="py-1 px-5 my-3 rounded-full border border-gray-500" />
            <input v-model="password" type="password" placeholder="Password" required class="py-1 px-5 my-3 rounded-full border border-gray-500" />
            <button type="submit" class="border border-gray-500 rounded-full lg:w-1/6 md:w-1/5 sm:w-1/3 w-1/2 hover:cursor-pointer">Login</button>
            <p v-if="error">{{ error }}</p>
            <p v-if="message">{{ message }}</p>
        </form>
        <div v-if="authenticated">
            You are logged in!
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {mapActions, mapGetters} from "vuex";

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
    computed: {
        ...mapGetters(['authenticated']),
    },
    methods: {
        async handleLogin() {
            try {
                await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                })
                    .then(response => {
                        this.token = response.data.data.token;
                        localStorage.setItem('api_token', this.token);
                        this.login();
                        this.message = response.data.message;
                        axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;
                        this.setAuthenticatedUser();
                });
            } catch (e) {
                this.error = 'Login failed. Please check your credentials.';
            }
        },

        ...mapActions(['login', 'logout', 'setUser']),

        setAuthenticatedUser() {
            axios.get('/api/user').then(response => {
                const userData = response.data.data;
                localStorage.setItem('authenticatedUser', JSON.stringify(userData));
                this.setUser(userData);
            });
        },
    },
};
</script>
