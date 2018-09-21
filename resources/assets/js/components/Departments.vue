<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));"  @click="cleanData" data-toggle="modal" data-target="#addModal" >Add departments</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Departments</h4>
                    </div>

                    <content-placeholders v-if="loading">
                        <content-placeholders-heading :img="true" />
                        <content-placeholders-text :lines="3" />
                    </content-placeholders>

                    <div class="card-body table-full-width table-responsive" v-if="departments.length">
                        <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Department Name</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="department in filteredQueues" v-bind:key="department.id">
                                    <td>{{ department.id }}</td>
                                    <td>{{ department.name }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Option
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="javascript:void(0)"  data-toggle="modal" :data-target="`#editModal-${department.id}`">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" :data-target="`#deleteModal-${department.id}`">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3" v-if="departments.length">
                        <div class="col-6">
                            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                            <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                        </div>
                        <div class="col-6 text-right">
                            <span>{{ departments.length }} Company(s)</span>
                        </div>
                    </div>
                    <div class="card-body table-full-width table-responsive" v-if="!departments.length && !loading">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Department Name</th>
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
                <h5 class="modal-title" id="addCompanyLabel">Add Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="department">Department name</label>
                        <input type="text" class="form-control" placeholder="Department name" v-model="department.name" id="department">
                        <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="addDepartment(department)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div v-for="(department,d) in filteredQueues" :key="'list-' +  d" class="modal fade" :id="`editModal-${department.id}`" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="department.id">
                    <div class="form-group">
                        <label for="department">Department name</label>
                        <input type="text" class="form-control" placeholder="Name" v-model="department.name" id="department">
                         <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="editDepartment(department)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Update</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div  v-for="(department,d) in filteredQueues" :key="d" class="modal fade" :id="`deleteModal-${department.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Department</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="department.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this department?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                    <button  @click="deleteDepartment(department.id)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VueContentPlaceholders from 'vue-content-placeholders';
export default {
    components:{
        VueContentPlaceholders
    },
    data(){
        return{
            departments: [],
            department: {
                id: '',
                name: '',
            },
            department_id: '',
            pagination: {},
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    watch: {
        departments: function(val, oldVal)
        {

        }
    },
    created(){
        this.fetchDepartments();
    },  
    methods: {
        cleanData(){
            this.errors = ' ';
            this.department.name = ' ';
        },
        fetchDepartments(){
            this.loading = true;
            axios.get('/departments')    
            .then(response => {
                this.departments = response.data;
                this.loading = false;
            });
        },
        addDepartment(department){
            axios.post('/department',{
                name: department.name,
            })
            .then(response => {
                this.department.name = '';
                this.errors = [];
                this.departments = response.data;
                $('#addModal').modal('hide');
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteDepartment(id){
            axios.delete(`/department/${id}`)
            .then(response => {
                this.fetchDepartments();
                $('#deleteModal-'+id).modal('hide');
            })
            .catch(error => {
                this.errors = error.response.data.error;
            });
        },
        editDepartment(deparment){
            axios.patch(`/department/${deparment.id}`,{
                id: deparment.id,
                name: deparment.name,
            })
            .then(response => {
                this.department.name = ' ';
                this.errors = [];
                this.fetchDepartments();
                $('#editModal-'+deparment.id).modal('hide');
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });

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
        filteredDepartments(){
            let self = this;
            return self.departments.filter(department => {
                return department.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredDepartments.length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredDepartments.slice(index, index + this.itemsPerPage);

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

