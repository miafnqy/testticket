<template>
    <div class="container mx-auto">
        <nav class="flex w-full py-8">
            <router-link class="mx-1" to="/">Home</router-link>
            <router-link class="mx-1" to="/users">Users</router-link>
            <router-link class="mx-1 ml-auto" to="/login">Login</router-link>
        </nav>
        <div class="primary flex justify-center p-5">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: 'App',
};
</script>
