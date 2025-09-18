<template>
    <Modal>
        <h1 class="text-lg mb-7">Edit User</h1>
        <form @submit.prevent="updateRole" class="flex flex-col">
            <div class="py-2 flex justify-between">
                <label for="name" class="pr-3">Name:</label>
                <input class="rounded-full border border-gray-500 px-5" id="name" v-model="form.name" type="text" required />
            </div>
            <div class="py-2 flex justify-between">
                <label for="priority" class="pr-3">Priority:</label>
                <input class="rounded-full border border-gray-500 px-5" id="priority" v-model="form.priority" type="text" required />
            </div>
            <div class="flex ml-auto">
                <button type="button" class="border border-gray-300 rounded-full text-xs mx-3 px-3 py-2" @click="$emit('close')">Close</button>
                <button type="submit" class="border border-gray-300 rounded-full bg-gray-200 text-xs px-3 py-2">Update</button>
            </div>
        </form>
    </Modal>
</template>
<script>
    import Modal from "./Modal.vue";
    import {mapActions} from "vuex";

    export default {
        components: {Modal},
        props: {
            role: {
                type: Object,
                default: null
            }
        },
        data() {
            return {
                form: {
                    name: null,
                    priority: null,
                }
            }
        },
        watch: {
            role: {
                handler(newVal, oldVal) {
                    if (newVal) {
                        this.form.name = this.role.name;
                        this.form.priority = this.role.priority;
                    }
                }
            }
        },
        methods: {
            ...mapActions({
                updateRoleInStore: 'updateRole'
            }),
            async updateRole() {
                if (!this.role) {
                    return;
                }

                await axios.put('/api/roles/' + this.role.id, {
                    name: this.form.name,
                    priority: this.form.priority,
                })
                    .then(response => {
                        this.updateRoleInStore(response.data.data);
                        this.close();
                    });
            },
            close() {
                this.$emit('close');
            }
        }
    }
</script>
