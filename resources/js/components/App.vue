<template>
    <div class="container mx-auto">
        <nav class="flex w-full py-8">
            <router-link class="mx-1" to="/" active-class="font-bold">Home</router-link>
            <router-link class="mx-1" to="/users" active-class="font-bold">Users</router-link>
            <router-link v-if="!this.authenticated" class="mx-1 ml-auto" to="/login" active-class="font-bold">Login</router-link>
            <button v-if="this.authenticated" @click="handleLogout" class="mx-1 ml-auto cursor-pointer" to="/logout">Logout</button>

        </nav>
        <div class="primary flex justify-center p-5">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: 'App',
    computed: {
        ...mapGetters(['authenticated', 'user'])
    },
    methods: {
        async handleLogout() {
            await axios.post('/api/logout')
                .then(response => {
                    if (response.status === 200) {
                        this.logout();
                        localStorage.clear();
                    }
                });
        },
        ...mapActions(['logout']),
    }
};
</script>
