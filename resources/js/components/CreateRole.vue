<template>
    <Modal>
        <h1>Create User</h1>
        <form @submit.prevent="createRole" class="flex flex-col">
            <div class="py-2 flex justify-between">
                <label for="name" class="pr-3">Name:</label>
                <div class="flex flex-col">
                    <input v-model="form.name" class="rounded-full border border-gray-500 px-5" id="name" type="text" required />
                    <span v-if="errors && errors.name" class="text-red-500 text-xs">
                        {{ errors.name[0] }}
                    </span>
                </div>
            </div>

            <div class="py-2 flex justify-between">
                <label for="email" class="pr-3">Priority:</label>
                <div class="flex flex-col">
                    <input v-model="form.priority" class="rounded-full border border-gray-500 px-5" id="priority" type="text" required />
                    <span v-if="errors && errors.priority" class="text-red-500 text-xs">
                        {{ errors.priority[0] }}
                    </span>
                </div>
            </div>

            <div class="flex ml-auto">
                <button type="button" class="border border-gray-300 rounded-full text-xs mx-3 px-3 py-2" @click="close">Close</button>
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
                errors: [],
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
                        this.clearForm();
                        this.close();
                    })
                    .catch(e => {
                        if (e.response.status === 422 && e.response.data.errors) {
                            this.errors = e.response.data.errors;
                        }
                    });
            },
            clearForm() {
                this.errors = [];
                this.form = {
                    name: null,
                    priority: null,
                };
            },
            close() {
                this.clearForm();
                this.$emit('close');
            }
        }
    }
</script>
