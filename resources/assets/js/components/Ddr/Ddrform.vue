<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12">
            <div class="row">
                <div class="card col-md-12">
                    <form>
                        <div class="form-group">
                            <h1 style="color: #888888; text-align: center;">ADD DDR</h1>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                 <div class="form-group row">
                                    <label for="reason" class="col-sm-3 col-form-label">Reason of distribution</label>
                                    <div class="col-sm-9">
                                        <div class="form-group mb-1">
                                            <select v-model="ddr.reason" class="form-control form-control-lg" @change="selectedReason(ddr.reason)" id="reason">
                                                <option value="" disabled selected>Select Reason</option>
                                                <option value="1">Relevant External Document (controlled copy)</option>
                                                <option value="2">Customer Request (uncontrolled copy)</option>
                                                <option value="3">Others (please specify)</option>
                                            </select>
                                            <span class="error" v-if="errors.reason">{{ errors.reason[0] }}</span>
                                        </div>
                                    </div>
                                 </div>
                                <div class="form-group row" v-if="others == 1">
                                    <label for="others" class="col-sm-3 col-form-label">Others (Please specify)</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Others (Please specify)" v-model="ddr.others">
                                        <span class="error" v-if="errors.others">{{ errors.others[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="date_needed" class="col-sm-3 col-form-label">Date needed</label>
                                    <div class="col-sm-9">
                                        <datepicker placeholder="Select Date" v-model="ddr.date_needed" id="date_needed" :disabledDates="disabledDates"></datepicker>
                                        <span class="error" v-if="errors.date_needed">{{ errors.date_needed[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="company" class="col-sm-3 col-form-label">Company</label>
                                    <div class="col-sm-9">
                                        <select v-model="company.id" class="form-control form-control-lg"  id="company">
                                            <option value="" disabled selected>Select Company</option>
                                            <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name  }}</option>
                                        </select>
                                        <span class="error" v-if="errors.company">{{ errors.company[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="effective_date" class="col-sm-3 col-form-label">Location</label>
                                    <div class="col-sm-9">
                                        <select v-model="company.location" class="form-control form-control-lg" @change="getCompanyId(company.location)"  id="company">
                                            <option value="" disabled selected>Select  Company Location</option>
                                            <option v-for="(loc, c) in selectedLocation" :value="loc.id" v-bind:key="c">{{ loc.address }}</option>
                                        </select>
                                        <span class="error" v-if="errors.company_location">{{ errors.company_location[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="department" class="col-sm-3 col-form-label">Department</label>
                                    <div class="col-sm-9">
                                        <select v-model="department.id" class="form-control form-control-lg"  @change="getDepartmentId(department.id)" id="department">
                                            <option value="" disabled selected>Select Department</option>
                                            <option v-for="(department, d) in departments" :value="department.id" v-bind:key="d">{{ department.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.department">{{ errors.department[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="approver" class="col-sm-3 col-form-label">Approver</label>
                                    <div class="col-sm-9">
                                        <select v-model="approver.id" class="form-control form-control-lg" id="approver">
                                            <option value="" disabled selected>Select Approver</option>
                                            <option v-for="(approver, a) in approvers" v-bind:key="a" :value="approver.id">{{ approver.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.approver">{{ errors.approver[0] }}</span>
                                    </div>
                                </div>
                            </div> 
                        </div>  
                        <div class="form-group">
                            <table class="table table-hover table-striped">
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
                                    <tr v-for="(ddrlist, d) in ddrlists" v-bind:key="d">
                                        <td>{{ d + 1 }}</td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Document title" v-model="ddrlist.document_title">
                                            <span class="error" v-if="errors['ddrlists.'+d+'.document_title']"> This field is required </span>  
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Control Code" v-model="ddrlist.control_code">
                                            <span class="error" v-if="errors['ddrlists.'+d+'.control_code']"> This field is required </span>  
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Rev No." v-model="ddrlist.rev_number">
                                            <span class="error" v-if="errors['ddrlists.'+d+'.rev_number']"> This field is required </span>  
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Copy No." v-model="ddrlist.copy_number">
                                            <span class="error" v-if="errors['ddrlists.'+d+'.copy_number']"> This field is required </span>  
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Copy Holder" v-model="ddrlist.copy_holder">
                                            <span class="error" v-if="errors['ddrlists.'+d+'.copy_holder']"> This field is required </span>  
                                        </td>
                                        <td>
                                            <i @click="addRow(d)" class="material-icons mr-2" style="font-size:40px;color:green;cursor:pointer">add_circle_outline</i>
                                            <i @click="deleteRow(d)" class="material-icons ml-2" style="font-size:40px;color:red;cursor:pointer">delete_forever</i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button @click="addDdr(ddr,company,approver.id,department.id, ddrlists)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
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
            ddr: { 
                id: '',
                company_id: '',
                department_id: '',
                reason: '',
                others: '',
                date_needed: '',
                control_code: '',
                rev_number: '',
                copy_number: '',
                copy_holder: '',
                date_request: '',
                efffective_date: '',
                requester_id: '',
                reviewer_id: '', 
                reviewed_date: '',
                approver_id: '',
                approver_date: '',
                disapproved_date: '',
                status: '',
                remarks: ''

            },
            others: '',
            companies: [],
            locations: [],
            company:{
                id: '',
                name: '',
                address: '',
                location:''
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
                document_title: '',
                control_code: '',
                rev_number: '',
                copy_number: '',
                copy_holder: ''
            }],
            isLoading: false,
            disabledDates: {
                to: new Date(Date.now() - 8640000)
            }
        }
    },
    created(){
        this.fetchDepartments();
        this.fetchCompanies();
        this.fetchLocations();
    },
    computed: {
        selectedLocation() {
            var findCompany = this.companies.find(item => item.id === this.company.id)
            if(findCompany) {
                return this.locations.filter(item => {
                    return item.name === findCompany.name
                })
            }
        }
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
        fetchLocations() {
            axios.get('/companyLocation')
            .then(response => {
                this.locations = response.data;
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
        addDdr(ddr,company,approver,department,ddrlists){
            this.isLoading = true;
            this.errors = [];
            axios.post('/ddr',{
                company: company.location,
                company_location: company.location,
                department: department,
                reason: ddr.reason,
                date_needed: ddr.date_needed,
                approver: approver,
                others: ddr.others,
                ddrlists: ddrlists
            })
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DDR Succesfully Added',
                    position: 'top right'
                });
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            });
        },
        addRow(d){   
            this.ddrlists.splice(d + 1, 0,{
                document_title: '',
                control_code: '',
                rev_number: '',
                copy_number: '',
                copy_holder: ''
            })
        },
         deleteRow(index) {
            this.ddrlists.length < 2 ? alert('Unable to delete all row') : this.ddrlists.splice(index,1);
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

