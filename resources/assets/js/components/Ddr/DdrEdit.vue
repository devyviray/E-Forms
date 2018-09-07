<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form v-if="ddrs.length">
                    <div class="form-group">
                       <select v-model="ddrs[0].company.id" class="form-control form-control-lg" @change="getCompanyId(ddrs[0].company.id)">
                           <option value="" disabled selected>Select Company</option>
                           <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name + ' - ' + company.address }}</option>
                       </select>
                        <span v-if="errors.company">{{ errors.company }}</span>
                    </div>
                    <div class="form-group">
                        <select v-model="ddrs[0].department.id" class="form-control form-control-lg"  @change="getDepartmentId(ddrs[0].department.id)">
                            <option value="" disabled selected>Select Department</option>
                            <option v-for="(department, d) in departments" :value="department.id" v-bind:key="d">{{ department.name }}</option>
                        </select>
                            <span v-if="errors.department">{{ errors.department }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="ddrs[0].reason_of_distribution" class="form-control form-control-lg" @change="selectedReason(ddrs[0].reason_of_distribution)">
                           <option value="" disabled selected>Reason of distribution</option>
                           <option value="1">Relevant External Document (controlled copy)</option>
                           <option value="2">Customer Request (uncontrolled copy)</option>
                           <option value="3">Others (please specify)</option>
                       </select>
                        <span v-if="errors.type">{{ errors.type }}</span>
                    </div>
                    <div class="form-group" v-if="ddrs[0].reason_of_distribution == 3 || others == 1">
                        <input type="text" class="form-control" placeholder="Others (Please specify)" v-model="ddrs[0].others">
                        <span v-if="errors.type">{{ errors.type }}</span>
                    </div>
                    <div class="form-group">
                        <table class="table table-hover table-striped">
                            <button @click="addRow()" type="button" class="btn btn-primary">Add Row</button>
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
                                        <button @click="deleteRow(d, ddrlist.id)" type="button" class="btn btn-danger">Delete Row</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <datepicker placeholder="Select Date Needed" v-model="ddrs[0].date_needed"></datepicker>
                        <span v-if="errors.date_needed">{{ errors.date_needed }}</span>
                    </div>
                    <div class="form-group">
                        <select v-model="ddrs[0].approver.id" class="form-control form-control-lg">
                            <option value="" disabled selected>Select Approver</option>
                            <option v-for="(approver, a) in approvers" v-bind:key="a" :value="approver.id">{{ approver.name }}</option>
                        </select>
                    </div>
                    <button @click="updateDdr(ddrs[0], ddrlists)" type="button" class="btn btn-primary">Submit</button>
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
            others: ''
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
                alert('Unable to delete');
            }
        },
        updateDdr(ddr, ddrlists){
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
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        selectedReason(id){
           id == 3 ? this.others = 1 : this.others = 2; 
        }
    },
     components: {
         Datepicker
    }
}
</script>

