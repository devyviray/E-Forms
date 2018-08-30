<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal" >Add permissions</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Permissions</h4>
                    </div>
                    <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                    <div class="card-body table-full-width table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Permissions name</th>
                                <th>Permissions slug</th>
                                <th>Permissions description</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="permission in filteredQueues" v-bind:key="permission.id">
                                    <td>{{ permission.id }}</td>
                                    <td>{{ permission.name }}</td>
                                    <td>{{ permission.slug }}</td>
                                    <td>{{ permission.description }}</td>
                                    <td>{{ permission.level }}</td>
                                    <td>
                                        <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${permission.id}`">Edit</button>
                                        <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${permission.id}`">Delete</button>
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
                            <span>{{ permissions.length }} Permission(s)</span>
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
                <h5 class="modal-title" id="addCompanyLabel">Add permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Permission name" v-model="permission.name">
                        <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Permission slug" v-model="permission.slug">
                         <span v-if="errors.slug">{{ errors.slug }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Permission description" v-model="permission.description">
                         <span v-if="errors.description">{{ errors.description }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="addPermission(permission)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div v-for="(permission,p) in filteredQueues" :key="'list-' +  p" class="modal fade" :id="`editModal-${permission.id}`" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="permission.id">
                   <div class="form-group">
                        <input type="text" class="form-control" placeholder="Permission name" v-model="permission.name">
                        <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Permission slug" v-model="permission.slug">
                        <span v-if="errors.slug">{{ errors.slug }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Permission description" v-model="permission.description">
                         <span v-if="errors.description">{{ errors.description }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="editPermission(permission)" type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div  v-for="(permission, p) in filteredQueues" :key="p" class="modal fade" :id="`deleteModal-${permission.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Permission</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="permission.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this permission?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deletePermission(permission.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            permissions: [],
            permission: {
                id: '',
                name: '',
                slug: '',
                description: ''
            },
            permission_id: '',
            pagination: {},
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 5,
        }
    },
    created(){
        this.fetchPermissions();
    },  
    methods: {
        fetchPermissions(){
            axios.get('/permissions')    
            .then(response => {
                this.permissions = response.data;
            });
        },
        addPermission(permission){
            axios.post('/permission',{
                name: permission.name,
                slug: permission.slug,
                description: permission.description
            })
            .then(response => {
                this.permission.name = '';
                this.permission.slug = '';
                this.permission.description = '';
                this.errors = [],
                this.fetchPermissions();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deletePermission(id){
            axios.delete(`/permission/${id}`)
            .then(response => {
                this.fetchPermissions();
            });
        },
        editPermission(permission){
            axios.patch(`/permission/${permission.id}`,{
                name: permission.name,
                slug: permission.slug,
                description: permission.description,
                level: permission.level 
            })
            .then(response => {
                this.permission.name = '';
                this.permission.slug = '';
                this.permission.description = '';
                this.errors = [],
                this.fetchPermissions();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
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
        filteredPermissions(){
            let self = this;
            return self.permissions.filter(permission => {
                return permission.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredPermissions.length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredPermissions.slice(index, index + this.itemsPerPage);

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
