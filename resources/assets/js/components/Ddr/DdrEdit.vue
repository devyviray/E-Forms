<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12">
            <div class="row">
                <div class="card col-md-12">
                    <form v-if="ddrs.length">
                        <div class="form-group">
                            <h1 style="color: #888888; text-align: center;">EDIT DDR</h1>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="reason_of_distribution" class="col-sm-2 col-form-label">Reason of distribution</label>
                                    <div class="col-sm-10">
                                        <div class="form-group mb-1">
                                            <select v-model="ddrs[0].reason_of_distribution" class="form-control form-control-lg" @change="selectedReason(ddrs[0].reason_of_distribution)" id="reason_of_distribution">
                                                <option value="" disabled selected>Select Reason</option>
                                                <option value="1">Relevant External Document (controlled copy)</option>
                                                <option value="2">Customer Request (uncontrolled copy)</option>
                                                <option value="3">Others (please specify)</option>
                                            </select>
                                            <span class="error" v-if="errors.type">{{ errors.type[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="ddrs[0].reason_of_distribution == 3 || others == 1">
                                    <label for="others" class="col-sm-2 col-form-label">Others (Please specify)</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="Others (Please specify)" v-model="ddrs[0].others" id="others">
                                        <span class="error" v-if="errors.others">{{ errors.others[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">                   
                                    <label for="date_needed" class="col-sm-2 col-form-label">Date Needed</label>
                                    <div class="col-sm-10">
                                        <datepicker placeholder="Select Date Needed" v-model="ddrs[0].date_needed" id="date_needed"></datepicker>
                                        <span class="error" v-if="errors.date_needed">{{ errors.date_needed[0] }}</span>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="company" class="col-sm-2 col-form-label">Company</label>
                                    <div class="col-sm-10">
                                    <select v-model="ddrs[0].company.id" class="form-control form-control-lg" @change="getCompanyId(ddrs[0].company.id)" id="company">
                                        <option value="" disabled selected>Select Company</option>
                                        <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name + ' - ' + company.address }}</option>
                                    </select>
                                    <span class="error" v-if="errors.company">{{ errors.company[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="department" class="col-sm-2 col-form-label">Department</label>
                                    <div class="col-sm-10">
                                        <select v-model="ddrs[0].department.id" class="form-control form-control-lg"  @change="getDepartmentId(ddrs[0].department.id)" id="department">
                                            <option value="" disabled selected>Select Department</option>
                                            <option v-for="(department, d) in departments" :value="department.id" v-bind:key="d">{{ department.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.department">{{ errors.department[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <table class="table table-hover table-striped">
                                <button @click="addRow()" type="button" class="btn btn-warning btn-round btn-fill mb-2 mt-2">Add Row</button>
                                <thead>
                                    <th>ID</th>
                                    <th>Document Title</th>
                                    <th>Control Code</th>
                                    <th>Rev No.</th>
                                    <th>Copy No.</th>
                                    <th>Copy Holder</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <tr v-if="ddrs.length" v-for="(ddrlist, d) in ddrlists" v-bind:key="d">
                                        <td>{{ d + 1 }}</td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Document title" v-model="ddrlist.document_title">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Control Code" v-model="ddrlist.control_code">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Rev No." v-model="ddrlist.rev_number">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Copy No." v-model="ddrlist.copy_number">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Copy Holder" v-model="ddrlist.copy_holder">
                                        </td>
                                        <td>
                                            <button @click="deleteRow(d, ddrlist.id)" type="button" class="btn btn-danger btn-round btn-fill">Delete Row</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="approver" class="col-sm-2 col-form-label">Approver</label>
                                    <div class="col-sm-10">
                                        <select v-model="ddrs[0].approver.id" class="form-control form-control-lg" id="approver">
                                            <option value="" disabled selected>Select Approver</option>
                                            <option v-for="(approver, a) in approvers" v-bind:key="a" :value="approver.id">{{ approver.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button @click="updateDdr(ddrs[0], ddrlists)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .vdp-datepicker  input{
        background-color: #FFFFFF;
        border: 1px solid #E3E3E3;
        border-radius: 4px;
        color: #565656;
        padding: 8px 12px;
        height: 40px;
        -webkit-box-shadow: none;
        box-shadow: none;
        display: block;
        width: 100%;
        line-height: 1.5;   
    }
</style>
<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>

<script>
import Datepicker from 'vuejs-datepicker';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

export default {
    data(){
        return{
            ddrs: [],
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
            keywords: '',
            errors: '',
            ddrlists: [{
                id: '',
                document_title: '',
                control_code: '',
                rev_number: '',
                copy_number: '',
                copy_holder: ''
            }],
            ddrId: '',
            others: '',
            isLoading: false
        }
    },
    created(){
        this.fetchDrdrs();
        this.fetchDepartments();
        this.fetchCompanies();
    },
    methods: {
        fetchDrdrs(){
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];
            
            this.ddrId = id;
            
            axios.get(`/ddr-data/${id}`)
            .then(response => {
                this.ddrs = response.data;
                this.ddrlists = this.ddrs[0].ddr_lists;
                this.getCompanyId(this.ddrs[0].company.id);
                this.getDepartmentId(this.ddrs[0].department.id);
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
            })
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
        addRow(){
            this.ddrlists.push({
                document_title: '',
                control_code: '',
                rev_number: '',
                copy_number: '',
                copy_holder: ''
            })
        },
        deleteRow(index,id) {
            if(id !== undefined && this.ddrlists.length >= 2){
                axios.delete(`/ddrformslists/${id}`)
                .then(response => {
                     this.ddrlists.splice(index,1);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            }else if(this.ddrlists.length >= 2){
                this.ddrlists.splice(index,1);
            }else{
                alert('Unable to delete  all row');
            }
        },
        updateDdr(ddr, ddrlists){
            this.isLoading = true;
            axios.patch(`/ddr/${this.ddrId}`,{
                reason: ddr.reason_of_distribution,
                date_needed: ddr.date_needed,
                company: ddr.company.id,
                approver: ddr.approver.id,
                department: ddr.department.id,
                others: ddr.others,
                ddrlists: ddrlists
            })
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DDR Succesfully Edited',
                    position: 'top right'
                });
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            })
        },
        selectedReason(id){
           id == 3 ? this.others = 1 : this.others = 2; 
        }
    },
     components: {
         Datepicker,
         SpinnerLoading
    }
}
</script>

