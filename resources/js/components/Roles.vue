<template>
    <div class="flex shadow px-28 flex-col">
        <h1 class="font-bold pb-8 mx-auto">Role List</h1>
        <ul class=" flex flex-col text-sm mr-auto px-28">
            <li v-for="role in roles" :key="role.id" class="py-1 flex justify-between">
                <span class="px-3">{{ role.name }}</span> <div class="w-5">/ {{ role.priority }}</div>
                <button
                    class="ml-auto border border-gray-300 rounded-full text-xs px-3 py-0"
                    @click="openEditModal(role)"
                >Edit</button>
                <button
                    @click="deleteRole(role.id)"
                    class="ml-5 border border-gray-300 rounded-full text-xs px-3 py-0"
                >Delete</button>
            </li>
        </ul>
        <div v-if="authenticated" class="py-12 px-28">
            <div class="border flex justify-center border-gray-500 rounded-full">
                <button @click="openCreateRoleModal()">Create New Role</button>
            </div>
        </div>
        <teleport to="body">
            <EditRole :show="editingRole != null" :role="editingRole" @close="closeModal"></EditRole>
            <CreateRole :show="creatingRole" @close="closeModal"></CreateRole>
        </teleport>
    </div>
</template>
<script>
    import EditUser from "./EditUser.vue";
    import CreateUser from "./CreateUser.vue";
    import EditRole from "./EditRole.vue";
    import {mapActions, mapGetters} from "vuex";
    import CreateRole from "./CreateRole.vue";

    export default {
        components: {CreateRole, EditRole, CreateUser, EditUser},

        data() {
            return {
                editingRole: null,
                creatingRole: false,
            }
        },

        mounted() {
            this.getRoles();
        },
        computed: {
            ...mapGetters(['authenticated', 'user', 'roles'])
        },

        methods: {
            ...mapActions(['setRoles', 'removeRole']),
            async getRoles() {
                await axios.get('/api/roles')
                    .then(response => {
                        this.setRoles(response.data.data);
                    })
                    .catch(e => console.log(e.toString()));
            },
            openEditModal(role) {
                this.editingRole = role;
            },
            async deleteRole(id) {
                if (confirm('Are you sure you want to delete this role?')) {
                    await axios.delete(`/api/roles/${id}`)
                        .then(response => {
                            this.removeRole(id);
                        });
                }
            },
            openCreateRoleModal() {
                this.creatingRole = true;
            },
            closeModal() {
                this.editingRole = null;
                this.creatingRole = false;
            }
        }
    }
</script>
