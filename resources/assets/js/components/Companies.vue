<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2" data-toggle="modal" data-target="#addModal" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));" @click="cleanData">Add companies</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Companies</h4>
                    </div>

                    <content-placeholders v-if="loading">
                        <content-placeholders-heading :img="true" />
                        <content-placeholders-text :lines="3" />
                    </content-placeholders>

                    <div class="card-body table-full-width table-responsive" v-if="companies.length">
                        <div class="row mb-4 ml-2">
                            <div class="col-md-12">
                                <label for="name">Search by Company Name</label>
                                <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                            </div>
                        </div>
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>City</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="company in filteredQueues" v-bind:key="company.id">
                                    <td>{{ company.id }}</td>
                                    <td>{{ company.name }}</td>
                                    <td>{{ company.address }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Option
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="javascript:void(0)"  data-toggle="modal" :data-target="`#editModal-${company.id}`">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" :data-target="`#deleteModal-${company.id}`">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3" v-if="companies.length">
                        <div class="col-6">
                            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                            <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                        </div>
                        <div class="col-6 text-right">
                            <span>{{ companies.length }} Company(s)</span>
                        </div>
                    </div>

                    <div class="card-body table-full-width table-responsive" v-if="!companies.length && !loading">
                        <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Company Address</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="company in filteredQueues" v-bind:key="company.id">
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
                <h5 class="modal-title" id="addCompanyLabel">Add companies</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="company">Company name</label>
                        <input type="text" class="form-control" placeholder="Company name" v-model="company.name" id="company">
                        <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="address">City</label>
                        <input type="text" class="form-control" placeholder="Company address" v-model="company.address" id="address">
                        <span class="error" v-if="errors.address">{{ errors.address[0] }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="addCompany(company)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div v-for="(company,c) in filteredQueues" :key="'list-' +  c" class="modal fade" :id="`editModal-${company.id}`" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Edit Company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="company.id">
                    <div class="form-group">
                        <label for="company">Company name</label>
                        <input type="text" class="form-control" placeholder="Name" v-model="company.name" id="company">
                        <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="address">City</label>
                        <input type="text" class="form-control" placeholder="Address" v-model="company.address">
                        <span class="error" v-if="errors.address">{{ errors.address[0] }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="editCompany(company)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Update</button>
                </div>
            </div>
            </div>
        </div>
        
        <!-- Delete Modal -->
        <div  v-for="(company,c) in filteredQueues" :key="c" class="modal fade" :id="`deleteModal-${company.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete company</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="company.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the company?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteCompany(company.id)" type="button" class="btn btn-primary">Delete</button>
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
            companies: [],
            company: {
                id: '',
                name: '',
                address: '',
            },
            company_id: '',
            pagination: {},
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }

    },
    watch:{
        companies: function(val, oldVal){
        }
    },
    created(){
        this.fetchCompanies();
    },
     methods: {
        cleanData(){
            this.errors = ' ';
            this.company.name = ' ';
            this.company.address= ' ';
        },
        fetchCompanies(){
            this.loading = true;
            axios.get('/companies')
                .then(response => {
                    this.companies = response.data;
                    this.loading = false;
                })
                .catch(err => console.log(err));  
        },
        deleteCompany(id){
            axios.delete(`/company/${id}`)
            .then(response => {
                this.fetchCompanies();
                $('#deleteModal-'+id).modal('hide')
            })
            .catch(error => console.log(error))
        },
        editCompany(company){
            axios.patch(`/company/${company.id}`,{
                id: company.id,
                name: company.name,
                address: company.address
            })
            .then(response => {
                this.company.name = '',
                this.company.address = '',
                this.errors = [],
                this.fetchCompanies();
                $('#editModal-'+company.id).modal('hide');
            })
            .catch(error => {
                if(error.response.data) {
                    this.errors = error.response.data.errors;
                }
            })

        },
        addCompany(company){
            axios.post('company', {
                name: this.company.name,
                address: this.company.address,
            })
            .then(response => {
                this.company.name = '',
                this.company.address = '',
                this.errors = [],
                this.companies = response.data;
                $('#addModal').modal('hide');
            })
            .catch(error => {
                if(error.response.data){
                    this.errors = error.response.data.errors;
                }
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
        filteredCompanies(){
            let self = this;
            return self.companies.filter(company => {
                return company.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredCompanies.length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredCompanies.slice(index, index + this.itemsPerPage);

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

