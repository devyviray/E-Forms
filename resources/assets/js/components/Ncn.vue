<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2"  @click="addNcnForm">Add Ncn</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Ncn Forms</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="approve-tab" data-toggle="tab" href="#approve" role="tab" aria-controls="approve" aria-selected="false">Approved Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="submitted-tab" data-toggle="tab" href="#submitted" role="tab" aria-controls="submitted" aria-selected="false">Submitted Forms</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <select v-model="selectedStatus" @change="fetchNcnBystatus">
                            <option value="" disabled selected>Select By</option>
                                <option value="all">ALL</option>
                                <option value="pending">PENDING</option>
                                <option value="approved">APPROVED</option>
                            </select>
                            <div class="card-body table-full-width table-responsive">
                                <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Requester</th>
                                        <th>Position</th>
                                        <th>Notification</th>
                                        <th>Date of Issuance</th>
                                        <th>Status</th>
                                        <th>Option</th>
                                    </thead>    
                                    <tbody>
                                        <tr v-for="ncn in filteredQueues" v-bind:key="ncn.id">
                                            <td>{{ ncn.id }}</td>
                                            <td>{{ ncn.requester.name }}</td>
                                            <td>{{ ncn.requester.position }}</td>
                                            <!-- <td>{{ ncn.attached_files }}</td>
                                            <td>{{ ncn.non_conformity_details }}</td> -->
                                            <td>{{ ncn.notification_number }}</td>
                                            <td>{{ ncn.issuance_date }}</td>
                                            <td>{{ ncn.status }}</td>
                                            <td>
                                                <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ncn.id}`">Edit</button>
                                                <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ncn.id}`">Delete</button>
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
                                    <span>{{ ncn.length }} Ncn form(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="approve" role="tabpanel" aria-labelledby="approve-tab">
                            <h1>sample 2</h1>
                        </div>
                        <div class="tab-pane fade" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">
                            <div class="card-body table-full-width table-responsive">
                                <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Requester</th>
                                        <th>Position</th>
                                        <th>Notification</th>
                                        <th>Date of Issuance</th>
                                        <th>Status</th>
                                        <th>Option</th>
                                    </thead>    
                                    <tbody>
                                        <tr v-for="ncnSubmitted in ncnSubmitteds" v-bind:key="ncnSubmitted.id">
                                            <td>{{ ncnSubmitted.id }}</td>
                                            <td>{{ ncnSubmitted.requester.name }}</td>
                                            <td>{{ ncnSubmitted.requester.position }}</td>
                                            <!-- <td>{{ ncn.attached_files }}</td>
                                            <td>{{ ncn.non_conformity_details }}</td> -->
                                            <td>{{ ncnSubmitted.notification_number }}</td>
                                            <td>{{ ncnSubmitted.issuance_date }}</td>
                                            <td>{{ ncnSubmitted.status }}</td>
                                            <td>
                                                <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ncnSubmitted.id}`">Edit</button>
                                                <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ncnSubmitted.id}`">Delete</button>
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
                                    <span>{{ ncn.length }} Ncn form(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div  v-for="(ncn, n) in filteredQueues" :key="n" class="modal fade" :id="`deleteModal-${ncn.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete NCN</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ncn.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this NCN?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteNcn(ncn.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    data(){
        return{
            ncns: [],
            ncn: { 
                id: '',
                requester_id: '',
                company_id: '',
                department_id: '',
                approver_id: '',
                non_conformity_types: '',
                notification_number: '',
                recurrence_number: '',
                issuance_date: '',
                request_date: '',
                approved_date: '',
                disapproved_date: '',
                non_confirmity_details: '',
                attached_files: '', 
                status: '',
                remarks: ''
            },
            ncnSubmitteds: [],
            ncnSubmitted: { 
                id: '',
                requester_id: '',
                company_id: '',
                department_id: '',
                approver_id: '',
                non_conformity_types: '',
                notification_number: '',
                recurrence_number: '',
                issuance_date: '',
                request_date: '',
                approved_date: '',
                disapproved_date: '',
                non_confirmity_details: '',
                attached_files: '', 
                status: '',
                remarks: ''
            },
            companies: [],
            company:{
                id: '',
                name: '',
                address: ''
            },
            departments: [],
            department:{
                id: '',
                name: '',
            },
            approvers: [],
            approver: {
                id: '',
                name: '',
                email: '',
                department_id: ''
            },
            selected_company: ' ',
            selected_department: ' ',
            selectedStatus: ' ',
            keywords: '',
            errors: '',
            currentPage: 0,
           itemsPerPage: 10,
        }
    },
    created(){
        this.fetchNcns();
        this.fetchNcnSubmitteds();
        this.fetchCompanies();
        this.fetchDepartments();  
    },
    methods: {
        fetchNcnBystatus()
        {
            axios.get(`/ncns-by/${this.selectedStatus}`)
            .then(response => {
                this.ncns = response.data;
            })
            .catch(error => {
                this.errors = error.responde.data.errors;
            })
        },
        fetchNcns(){
            axios.get('/ncns')
            .then(response => {
                this.ncns = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        fetchNcnSubmitteds()
        {
            axios.get('/ncns-submitted')
            .then(response => {
                this.ncnSubmitteds = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        fetchDepartments(){
            axios.get('/departments')
            .then(response => {
                this.departments = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        fetchCompanies(){
            axios.get('/companies')
            .then(response => {
                this.companies = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        getCompanyId(company){
            this.selected_company = company;
            if(this.selected_company != ' ' &&  this.selected_department !=  ' '){
                axios.get(`/getNcnApprovers/${company}/${this.selected_department}`)
                .then(response => {
                    this.approvers = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.rrors;
                })
            }
        },
        getDepartmentId(department){
            this.selected_department = department;
            if(this.selected_company != ' ' &&  this.selected_department !=  ' '){
                axios.get(`/getNcnApprovers/${this.selected_company}/${this.selected_department}`)
                .then(response => {
                    this.approvers = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            }
        },
        addNcnForm(){
            window.location.href = '/add-ncn';
        },
        editNcn(ncn){
            axios.post(`/ncn/${ncn.id}`,{

            })
            .then(response => {
                this.fetchNcns();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteNcn(id){
            axios.delete(`/ncn/${id}`)
            .then(response => {
                this.fetchNcns();
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
        filteredNcns(){
            let self = this;
            return self.ncns.filter(ncn => {
                return ncn.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredNcns.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredNcns.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return queues_array;
        },
    },
     components: {
         Datepicker
    }
}
</script>

