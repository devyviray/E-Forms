<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2" @click="addDdrForm" >Add Ddr</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Ddr Forms</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pending-tab" data-toggle="tab" @click="selected=1" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="approve-tab" data-toggle="tab"  @click="selected=2" href="#approve" role="tab" aria-controls="approve" aria-selected="false">Approve Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="submitted-tab" data-toggle="tab" @click="selected=3" href="#submitted" role="tab" aria-controls="submitted" aria-selected="false">Submitted Forms</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            
                            <ddr-pending-list v-if="selected==1"></ddr-pending-list>

                        </div>
                        <div class="tab-pane fade" id="approve" role="tabpanel" aria-labelledby="approve-tab">

                            <ddr-approved-list v-if="selected==2"></ddr-approved-list>

                        </div>
                        <div class="tab-pane fade" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">
                            
                            <ddr-submitted  v-if="selected==3"></ddr-submitted>

                        </div>
                    </div>
                </div>
            </div>
        </div>
          <!-- Update Modal
        <div v-for="(ddr,d) in filteredQueues" :key="'list-' +  d" class="modal fade" :id="`editModal-${ddr.id}`" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit Ddr</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                       <select v-model="company.id" class="form-control form-control-lg" @change="getCompanyId(company.id)">
                           <option value="" disabled selected>Select Company</option>
                           <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name + ' - ' + company.address }}</option>
                       </select>
                        <span v-if="errors.company">{{ errors.company }}</span>
                    </div>
                    <div class="form-group">
                        <select v-model="department.id" class="form-control form-control-lg" @change="getDepartmentId(department.id)">
                            <option value="" disabled selected>Select Department</option>
                            <option v-for="(department, d) in departments" :value="department.id" v-bind:key="d">{{ department.name }}</option>
                        </select>
                        <span v-if="errors.department">{{ errors.department }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="ddr.reason" class="form-control form-control-lg">
                           <option value="" disabled selected>Reason of distribution</option>
                           <option value="1">Relevant external doc (controll copy)</option>
                           <option value="2">Customer request (uncontroll copy)</option>
                           <option value="3">For others. Please specify</option>
                       </select>
                        <span v-if="errors.type">{{ errors.type }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Date needed" v-model="ddr.date_needed">
                        <span v-if="errors.date_needed">{{ errors.date_needed }}</span>
                    </div>
                    <div class="form-group">
                        <select v-model="approver.id" class="form-control form-control-lg">
                            <option value="" disabled selected>Select Approver</option>
                            <option v-for="(approver, a) in approvers" v-bind:key="a" :value="approver.id">{{ approver.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span>Requester: <br>
                                Sample requester <br>
                                Sample position
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="editDdr(ddr,company,approver,department)" type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
            </div>
        </div> -->

        <!-- Delete Modal
        <div  v-for="(ddr, d) in filteredQueues" :key="d" class="modal fade" :id="`deleteModal-${ddr.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete DDR</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ddr.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this DDR?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteDdr(ddr.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import DdrPendingList from './DdrPendingList';
import DdrApprovedList from './DdrApprovedList';
import DdrSubmitted from './DdrSubmitted';

export default {
     components: {
         Datepicker,
         DdrPendingList,
         DdrApprovedList,
         DdrSubmitted
    },
    data(){
        return{
            selected: 1,
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
            errors: '',
            currentPage: 0,
           itemsPerPage: 10,
        }
    },
    created(){
        this.fetchDepartments();
        this.fetchCompanies();
    },
    methods: {
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
        addDdrForm()
        {
            window.location.href = ('/add-ddr');
        },
        editDdr(ddr,company, approver, department){
            axios.post(`/ddr/${ddr.id}`,{

            })
            .then(response => {
                this.resetData();
                this.fetchDdrs();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteDdr(id){
            axios.delete(`/ddr/${id}`)
            .then(response => {
                this.fetchDdrs();
            })
            .catch(error => {
               this.errors = error.response.data.errors; 
            });
        }
    }
}
</script>

