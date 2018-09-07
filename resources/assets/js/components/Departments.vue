<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal" >Add departments</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Departments</h4>
                    </div>
                    <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                    <div class="card-body table-full-width table-responsive">
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
                                        <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${department.id}`">Edit</button>
                                        <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${department.id}`">Delete</button>
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
                            <span>{{ departments.length }} Company(s)</span>
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
                <h5 class="modal-title" id="addCompanyLabel">Add Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Department name" v-model="department.name">
                        <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="addDepartment(department)" type="button" class="btn btn-primary">Save</button>
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
                        <input type="text" class="form-control" placeholder="Name" v-model="department.name">
                         <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="editDepartment(department)" type="button" class="btn btn-primary">Update</button>
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
                    Are you sure you want to delete the department?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteDepartment(department.id)" type="button" class="btn btn-primary">Delete</button>
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
    created(){
        this.fetchDepartments();
    },  
    methods: {
        fetchDepartments(){
            axios.get('/departments')    
            .then(response => {
                this.departments = response.data;
            });
        },
        addDepartment(department){
            axios.post('/department',{
                name: department.name,
            })
            .then(response => {
                this.department.name = '';
                this.errors = [];
                this.fetchDepartments();
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

