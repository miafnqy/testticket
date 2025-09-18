<template>
    <Modal>
        <h1>Create User</h1>
        <form @submit.prevent="createRole" class="flex flex-col">
            <div class="py-2 flex justify-between">
                <label for="name" class="pr-3">Name:</label>
                <input v-model="form.name" class="rounded-full border border-gray-500 px-5" id="name" type="text" required />
            </div>

            <div class="py-2 flex justify-between">
                <label for="email" class="pr-3">Priority:</label>
                <input v-model="form.priority" class="rounded-full border border-gray-500 px-5" id="priority" type="text" required />
            </div>

            <div class="flex ml-auto">
                <button type="button" class="border border-gray-300 rounded-full text-xs mx-3 px-3 py-2" @click="$emit('close')">Close</button>
                <button type="submit" class="border border-gray-300 rounded-full bg-gray-200 text-xs px-3 py-2">Create</button>
            </div>
        </form>
    </Modal>
</template>

<script>
    import Modal from "./Modal.vue";
    import {mapActions} from "vuex";

    export default {
        components: {Modal},

        data() {
            return {
                form: {
                    name: null,
                    priority: null,
                },
            }
        },
        methods: {
            ...mapActions({
                addRoleToStore: 'addRole'
            }),
            async createRole() {
                await axios.post('/api/roles', {
                    name: this.form.name,
                    priority: this.form.priority,
                })
                    .then(response => {
                        this.addRoleToStore(response.data.data);
                        this.form = {
                            name: null,
                            priority: null,
                        };
                        this.close();
                    });
            },
            close() {
                this.$emit('close');
            }
        }
    }
</script>
