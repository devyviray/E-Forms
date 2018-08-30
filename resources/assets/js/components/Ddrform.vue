<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                       <select v-model="company.id" class="form-control form-control-lg" @change="getCompanyId(company.id)">
                           <option value="" disabled selected>Select Company</option>
                           <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name + ' - ' + company.address }}</option>
                       </select>
                        <span v-if="errors.company">{{ errors.company }}</span>
                    </div>
                    <div class="form-group">
                        <select v-model="department.id" class="form-control form-control-lg"  @change="getDepartmentId(department.id)">
                            <option value="" disabled selected>Select Department</option>
                            <option v-for="(department, d) in departments" :value="department.id" v-bind:key="d">{{ department.name }}</option>
                        </select>
                            <span v-if="errors.department">{{ errors.department }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="ddr.reason" class="form-control form-control-lg">
                           <option value="" disabled selected>Reason of distribution</option>
                           <option value="1">Relevant External Document (controlled copy)</option>
                           <option value="2">Customer Request (uncontroll copy)</option>
                           <option value="3">Others (please specify)</option>
                       </select>
                        <span v-if="errors.type">{{ errors.type }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker placeholder="Select Date" v-model="ddr.date_needed"></datepicker>
                        <span v-if="errors.date_needed">{{ errors.date_needed }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Document title" v-model="ddr.document_title">
                        <span v-if="errors.document_title">{{ errors.document_title }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Control code" v-model="ddr.control_code">
                        <span v-if="errors.control_code">{{ errors.control_code }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Rev code" v-model="ddr.rev_number">
                        <span v-if="errors.rev_number">{{ errors.rev_number }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Copy number" v-model="ddr.copy_number">
                        <span v-if="errors.copy_number">{{ errors.copy_number }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Copy holder" v-model="ddr.copy_holder">
                        <span v-if="errors.copy_holder">{{ errors.copy_holder }}</span>
                    </div>
                    <div class="form-group">
                        <select v-model="approver.id" class="form-control form-control-lg">
                            <option value="" disabled selected>Select Approver</option>
                            <option v-for="(approver, a) in approvers" v-bind:key="a" :value="approver.id">{{ approver.name }}</option>
                        </select>
                    </div>
                    <button @click="addDdr(ddr,company.id,approver.id,department.id)" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';

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
        addDdr(ddr,company,approver,department){
            axios.post('/ddr',{
                company_id: company,
                department_id: department,
                reason: ddr.reason,
                date_needed: ddr.date_needed,
                document_title: ddr.document_title,
                control_code: ddr.control_code,
                rev_number: ddr.rev_number,
                copy_number: ddr.copy_number,
                copy_holder: ddr.copy_holder,
                approver_id: approver
            })
            .then(response => {
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    },
     components: {
         Datepicker
    }
}
</script>

