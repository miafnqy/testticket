<<template>
    <Modal>
        <h1>Create User</h1>
        <form @submit.prevent="createUser" class="flex flex-col">
            <div class="py-2 flex justify-between">
                <label for="name" class="pr-3">Name:</label>
                <input v-model="form.name" class="rounded-full border border-gray-500 px-5" id="name" type="text" required />
            </div>

            <div class="py-2 flex justify-between">
                <label for="email" class="pr-3">Email:</label>
                <input v-model="form.email" class="rounded-full border border-gray-500 px-5" id="email" type="email" required />
            </div>

            <div class="py-2 pb-5 flex justify-between">
                <label for="role" class="pr-3">Role:</label>
                <select v-model="form.role" class="border border-gray-500 rounded-full px-5 py-0.5 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" id="role">
                    <option v-for="role in roles" :key="role.id" :value="role.id" v-text="role.name"></option>
                </select>
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
                    email: null,
                    role: null,
                },
                roles: [],
            }
        },

        mounted() {
            this.getRoles();
        },

        methods: {
            ...mapActions({
                addUserToStore: 'addUser'
            }),
            async createUser() {
                await axios.post('/api/users', {
                    name: this.form.name,
                    email: this.form.email,
                    role_id: this.form.role,
                })
                    .then(response => {
                        this.addUserToStore(response.data.data);
                        this.form = {
                            name: null,
                            email: null,
                            role: null,
                        };
                        this.close();
                    });
            },
            async getRoles() {
                await axios.get('/api/roles')
                    .then(response => {
                        this.roles = response.data.data;
                    })
                    .catch(e => console.log(e.toString()));
            },
            close() {
                this.$emit('close');
            }
        }
    }

</script>
