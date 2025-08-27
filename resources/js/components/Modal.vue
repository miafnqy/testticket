<template>
    <div v-show="user" class="fixed inset-0 size-full bg-black/75 flex justify-center items-center">
        <div class="rounded-lg bg-white p-9 flex flex-col justify-center items-center w-full max-w-md">
            <h1 class="text-lg mb-7">Edit User</h1>
            <form @submit.prevent="submitForm" class="flex flex-col">
                <div class="py-2 flex justify-between">
                    <label for="name" class="pr-3">Name:</label>
                    <input class="rounded-full border border-gray-500 px-5" id="name" v-model="user.name" type="text" required />
                </div>

                <div class="py-2 pb-5 flex justify-between">
                    <label for="email" class="pr-3">Email:</label>
                    <input class="rounded-full border border-gray-500 px-5" id="email" v-model="user.email" type="email" required />
                </div>

                <div class="flex ml-auto">
                    <button type="button" class="border border-gray-300 rounded-full text-xs mx-3 px-3 py-2" @click="$emit('close')">Close</button>
                    <button type="submit" class="border border-gray-300 rounded-full bg-gray-200 text-xs px-3 py-2">Update</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: {
                type: [Object, Boolean],
                default: false
            }
        },
        methods: {

        }
    }
</script>
