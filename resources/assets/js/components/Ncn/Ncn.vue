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
                            <a class="nav-link active" id="pending-tab" data-toggle="tab" @click="selected=1" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="approve-tab" data-toggle="tab" @click="selected=2" href="#approve" role="tab" aria-controls="approve" aria-selected="false">Approved Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="submitted-tab" data-toggle="tab" @click="selected=3" href="#submitted" role="tab" aria-controls="submitted" aria-selected="false">Submitted Forms</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">

                            <ncn-pending-approval-list v-if="selected==1"></ncn-pending-approval-list>

                        </div>
                        <div class="tab-pane fade" id="approve" role="tabpanel" aria-labelledby="approve-tab">

                            <ncn-approved-list v-if="selected==2"></ncn-approved-list>

                        </div>
                        <div class="tab-pane fade" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">
                            
                            <ncn-submitted v-if="selected==3"></ncn-submitted>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal
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
        </div> -->
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import NcnPendingApprovalList from './NcnPendingApprovalList';
import NcnApprovedList from './NcnApprovedList';
import NcnSubmitted from './NcnSubmitted';

export default {
    components: {
        Datepicker,
        NcnPendingApprovalList,
        NcnApprovedList,
        NcnSubmitted
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
        }
    },
    created(){
        this.fetchCompanies();
        this.fetchDepartments();  
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
    }
}
</script>

