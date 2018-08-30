<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2" @click="addUserForm">Add user</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Users</h4>   
                    </div>
                    <div class="card-body table-full-width table-responsive">
                        <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>department</th>
                                <th>Role</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="user in filteredQueues" v-bind:key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>
                                        <span v-for="(company, c) in user.companies" :key="c">
                                             {{ company.name }} <br/>
                                        </span>
                                    </td>
                                    <td>{{ user.department.name }}</td>
                                    <td>
                                        <span v-for="(role, r) in user.roles" :key="r">
                                            {{ role.name }} <br/>
                                        </span>
                                    </td>
                                    <td>
                                        <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${user.id}`">Edit</button>
                                        <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${user.id}`">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                            <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                        </div>
                        <div class="col-6 text-right">
                            <span>{{ users.length }} User(s)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div v-for="(user,u) in filteredQueues" :key="'list-' +  u" class="modal fade" :id="`editModal-${user.id}`" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editUserLabel">Edit user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Name" v-model="user.id">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" v-model="user.name">
                        <span v-if="errors.users">{{ errors.users }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email" v-model="user.email">
                        <span v-if="errors.email">{{ errors.email }}</span>
                    </div>
                    <div class="form-group">
                       <multiselect
                            v-model="selected_company"
                            :options="companies"
                            :multiple="true"
                            track-by="id"
                            :custom-label="customLabelCompany"
                            >
                        </multiselect>
                    </div>
                      <span v-if="errors.company">{{ errors.company }}</span>
                    <div class="form-group">
                       <select v-model="selected_department" class="form-control form-control-lg">
                           <option value="" disabled selected>Select department</option>
                           <option v-for="department in departments" v-bind:key="department.id" :value="department.id">
                               {{ department.name }}
                           </option>
                       </select>
                        <span v-if="errors.department">{{ errors.department }}</span>
                    </div>
                    <div class="form-group">
                       <multiselect
                            v-model="selected_role"
                            :options="roles"
                            :multiple="true"
                            track-by="id"
                            :custom-label="customLabelRole"
                            >
                        </multiselect>
                        <span v-if="errors.role">{{ errors.role }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="editUser(user,selected_company,selected_department,selected_role)" type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
            </div>
        </div>
        
        <!-- Delete Modal -->
        <div  v-for="(user,u) in filteredQueues" :key="u" class="modal fade" :id="`deleteModal-${user.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete user</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="user.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteUser(user.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import Multiselect from 'vue-multiselect'
export default {
    components: { Multiselect },
    data(){
        return{
            users: [],
            user: {
                id: '',
                name: '',
                email: '',
                password: '',
                department: '',
                companies: [],
                roles: [] 
            },
            user_id: '',
            pagination: {}, 
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
            companies: [],
            company: {
                id: '',
                company_name: ''
            },
            selected_company: '',
            departments: [],
            department: {
                id: '',
                department_name: '',
            },
            selected_department: '',
            roles: [],
            role: {
                id: '',
                name: '',
            },
            selected_role: '',

         }
    },
    created(){  
        this.fetchUsers();
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchRoles();
    },
   
    methods: {
        customLabelCompany (company) {
            return `${company.name}`
        },
        customLabelRole (role) {
            return `${role.name}`
        },
        fetchUsers(){
            axios.get('/users')
                .then(response => {
                    this.users = response.data;
                })
                .catch(error => console.log(errors));  
        },
        fetchCompanies(){
            axios.get('/companies')
                .then(response => {
                    this.companies = response.data;
                })
                .catch(err => console.log(err));  
        },
        fetchDepartments(){
            axios.get('/departments')    
            .then(response => {
                this.departments = response.data;
            });
        },
        fetchRoles(){
            axios.get('/roles')    
            .then(response => {
                this.roles = response.data;
            });
        },
        deleteUser(id){
            axios.delete(`/user/${id}`)
            .then(response => {
                this.fetchUsers();
            })
            .catch(error => console.log(error));
        },

        editUser(user,selected_company,selected_department,selected_role){
            let comapanyids = [];
            let roleids = [];
            selected_company.forEach((selected_company) => {
                    comapanyids.push(selected_company.id);
            });
            selected_role.forEach((selected_role) => {
                roleids.push(selected_role.id);
             });
            axios.patch(`/user/${user.id}`,{
                id: user.id, 
                name: user.name,
                email: user.email,
                company: comapanyids,
                department: selected_department,
                role: roleids
            })
            .then(response => {
                $('#editModal-' + user.id).hide();
                $('.modal-backdrop').remove();
                this.user.name = "",
                this.user.email = "",
                this.user.errors = []
                this.fetchUsers();
            })
            .catch(error => {
                if(error.response.data) {
                    this.errors = error.response.data.errors;
                }
            })

        },
        addUserForm(){
            window.location.href = '/add-user';
        },
        editUserData(user){
            this.edit = true;
            this.user.id = user.id;
            this.user_id = user.id;
            this.user.name = user.name;
            this.user.email = user.email;

        },
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },

        resetStartRow() {
            this.currentPage = 0;
        },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }
    }, 
    computed: {
        filteredUsers(){
            let self = this;
            return self.users.filter(user => {
                return user.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredUsers.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredUsers.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return queues_array;
        },
    }
}
</script>
