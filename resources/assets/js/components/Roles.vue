<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal" >Add role</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Roles</h4>
                    </div>
                    <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                    <div class="card-body table-full-width table-responsive">

                        <content-placeholders v-if="loading">
                            <content-placeholders-heading :img="true" />
                            <content-placeholders-text :lines="3" />
                        </content-placeholders>

                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Role name</th>
                                <th>Role description</th>
                                <th>Role level</th>
                                <!-- <th>Action</th> -->
                            </thead>
                            <tbody>
                                <tr v-for="role in filteredQueues" v-bind:key="role.id">
                                    <td>{{ role.id }}</td>
                                    <td>{{ role.name }}</td>
                                    <td>{{ role.description }}</td>
                                    <td>{{ role.level }}</td>
                                    <!-- <td>
                                        <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${role.id}`">Edit</button>
                                        <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${role.id}`">Delete</button>
                                    </td> -->
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
                            <span>{{ roles.length }} Role(s)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Modal -->
        <div  class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="addCompanyLabel">Add Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Role name" v-model="role.name">
                         <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Role description" v-model="role.description">
                         <span v-if="errors.description">{{ errors.description }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Role level" v-model="role.level">
                        <span v-if="errors.level">{{ errors.level }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="addRole(role)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Update Modal -->
        <!-- <div v-for="(role,r) in filteredQueues" :key="'list-' +  r" class="modal fade" :id="`editModal-${role.id}`" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="role.id">
                   <div class="form-group">
                        <input type="text" class="form-control" placeholder="Role name" v-model="role.name">
                        <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Role description" v-model="role.description">
                        <span v-if="errors.description">{{ errors.description }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Role level" v-model="role.level">
                        <span v-if="errors.level">{{ errors.level }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="editRole(role)" type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
            </div>
        </div> -->

        <!-- Delete Modal -->
        <!-- <div  v-for="(role, r) in filteredQueues" :key="r" class="modal fade" :id="`deleteModal-${role.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Role</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="role.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this role?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteRole(role.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div> -->
    </div>
</template>
<script>
import VueContentPlaceholders from 'vue-content-placeholders';

export default {
    components(){
        VueContentPlaceholders
    },
    data(){
        return{
            roles: [],
            role: {
                id: '',
                name: '',
                slug: '',
                description: '',
                level: ''
            },
            role_id: '',
            pagination: {},
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }
    },
    created(){
        this.fetchRoles();
    },  
    methods: {
        fetchRoles(){
            this.loading = true;
            axios.get('/roles')    
            .then(response => {
                this.roles = response.data;
                this.loading = false;
            });
        },
        addRole(role){
            axios.post('/role',{
                name: role.name,
                description: role.description,
                level: role.level
            })
            .then(response => {
                this.role.name = '';
                this.role.slug = '';
                this.role.description = '';
                this.role.level = '';
                this.errors = [],
                this.fetchRoles();
                $('#addModal').modal('hide');
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteRole(id){
            axios.delete(`/role/${id}`)
            .then(response => {
                this.fetchRoles();
                $('#deleteModal-'+id).modal('hide');
            });
        },
        // editRole(role){
        //     axios.patch(`/role/${role.id}`,{
        //         name: role.name,
        //         description: role.description,
        //         level: role.level 
        //     })
        //     .then(response => {
        //         this.role.name = '';
        //         this.role.slug = '';
        //         this.role.description = '';
        //         this.role.level = '';
        //         this.errors = [],
        //         this.fetchRoles();
        //         $('#editModal-'+role.id).modal('hide');
        //     })  
        //     .catch(error => {
        //         this.errors= error.response.data.errors;
        //     })
        // },
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
        filteredRoles(){
            let self = this;
            return self.roles.filter(role => {
                return role.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredRoles.length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredRoles.slice(index, index + this.itemsPerPage);

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
