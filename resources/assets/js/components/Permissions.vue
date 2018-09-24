<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2" data-toggle="modal" data-target="#addModal"  style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));" @click="cleanData" >Add permissions</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Permissions</h4>
                    </div>

                    <content-placeholders v-if="loading">
                        <content-placeholders-heading :img="true" />
                        <content-placeholders-text :lines="3" />
                    </content-placeholders>

                    <div class="card-body table-full-width table-responsive" v-if="permissions.length">
                        <div class="row mb-4 ml-2">
                            <div class="col-md-12">
                                <label for="name">Search by Permission Name</label>
                                <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                            </div>
                        </div>

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
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Option
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="javascript:void(0)" @click="copyObject(permission)" data-toggle="modal" :data-target="`#editModal-${permission.id}`">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" :data-target="`#deleteModal-${permission.id}`">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3" v-if="permissions.length">
                        <div class="col-6">
                            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                            <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                        </div>
                        <div class="col-6 text-right">
                            <span>{{ permissions.length }} Permission(s)</span>
                        </div>
                    </div>

                    <div class="card-body table-full-width table-responsive" v-if="!permissions.length && !loading">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Permissions name</th>
                                <th>Permissions slug</th>
                                <th>Permissions description</th>
                                <th>Action</th>
                            </thead>    
                            <tbody>
                                <tr>
                                    <td>No data available in the table</td>
                                </tr>
                            </tbody>
                        </table>
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
                        <label for="permission_name">Permission name</label>
                        <input type="text" class="form-control" placeholder="Permission name" v-model="permission.name" id="permission_name">
                        <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="permission_slug">Permission slug</label>
                        <input type="text" class="form-control" placeholder="Permission slug" v-model="permission.slug" id="permission_slug">
                        <span class="error" v-if="errors.slug">{{ errors.slug[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="permission_description">Permission description</label>
                        <input type="text" class="form-control" placeholder="Permission description" v-model="permission.description" id="permission_description">
                        <span class="error" v-if="errors.description">{{ errors.description[0] }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="addPermission(permission)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
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
                    <input type="hidden" class="form-control" placeholder="Id" v-model="copiedObject.id">
                   <div class="form-group">
                        <label for="permission_name">Permission name</label>
                        <input type="text" class="form-control" placeholder="Permission name" v-model="copiedObject.name" id="permission_name">
                        <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="permission_slug">Permission name</label>
                        <input type="text" class="form-control" placeholder="Permission slug" v-model="copiedObject.slug" id="permission_slug">
                        <span class="error" v-if="errors.slug">{{ errors.slug[0] }}</span>
                    </div>
                    <div class="form-group">
                         <label for="permission_description">Permission description</label>
                        <input type="text" class="form-control" placeholder="Permission description" v-model="copiedObject.description" id="permission_description">
                         <span class="error" v-if="errors.description">{{ errors.description[0] }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="editPermission(copiedObject)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Update</button>
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
                    <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                    <button  @click="deletePermission(permission.id)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>
<script>
import VueContentPlaceholders from 'vue-content-placeholders';
import CxltToastr from 'cxlt-vue2-toastr';
import SpinnerLoading from './SpinnerLoading';

Vue.use(CxltToastr);
export default {
    components:{
        VueContentPlaceholders,
        SpinnerLoading
    },
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
            itemsPerPage: 10,
            loading: false,
            copiedObject: [],
            isLoading: false
        }
    },
    watch:{
        permissions: function(val, oldVal){

        }
    },
    created(){
        this.fetchPermissions();
    },  
    methods: {
        copyObject(permission){
            this.copiedObject = Object.assign({}, permission)
        },
        cleanData(){
            this.errors = ' ';
            this.permission.name = ' ';
            this.permission.slug = ' ';
            this.permission.description = ' ';
        },
        fetchPermissions(){
            this.loading = true;
            axios.get('/permissions')    
            .then(response => {
                this.permissions = response.data;
                this.loading = false;
            });
        },
        addPermission(permission){
            axios.post('/permission',{
                name: permission.name,
                slug: permission.slug,
                description: permission.description
            })
            .then(response => {
                $('#addModal').modal('hide');
                this.permission.name = '';
                this.permission.slug = '';
                this.permission.description = '';
                this.errors = [],
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Permission Succesfully Added',
                    position: 'top right'
                });
                this.permissions = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deletePermission(id){
            let permissionIndex = this.permissions.findIndex(item => item.id == id);
            $('#deleteModal-'+id).modal('hide');
            axios.delete(`/permission/${id}`)
            .then(response => {
                this.permissions.splice(permissionIndex, 1);
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Permission Succesfully Deleted',
                    position: 'top right'
                });
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        editPermission(permission){
            let permissionIndex = this.permissions.findIndex(item => item.id == permission.id);
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
                this.permissions.splice(permissionIndex, 1, response.data);
                $('#editModal-'+permission.id).modal('hide');
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Permission Succesfully Edited',
                    position: 'top right'
                });

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
