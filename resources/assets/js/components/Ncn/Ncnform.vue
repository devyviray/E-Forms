<template>
  <div>
      <div class="row">
          <div class="col-md-12">
              <form>
                <div class="form-group">
                    <label for="company">Company</label>
                    <select v-model="company.id" class="form-control form-control-lg" @change="getCompanyId(company.id)" id="company">
                        <option value="" disabled selected>Select Company</option>
                        <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name + ' - ' + company.address }}</option>
                    </select>
                    <span v-if="errors.company">{{ errors.company }}</span>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <select v-model="department.id" class="form-control form-control-lg" @change="getDepartmentId(department.id)" id="department">
                        <option value="" disabled selected>Select Department</option>
                        <option v-for="(department, d) in departments" :value="department.id" v-bind:key="d">{{ department.name }}</option>
                    </select>
                    <span v-if="errors.department">{{ errors.department }}</span>
                </div>
                <div class="form-group">
                    <label for="non_conformity_types">Type of Non Conformity</label>
                    <select v-model="ncn.non_conformity_types" class="form-control form-control-lg" id="non_conformity_types">
                        <option value="" disabled selected>Select type</option>
                        <option value="1">Customer - Returns</option>
                        <option value="2">Objective not Met</option>
                        <option value="3">Project Related</option>
                        <option value="4">Vendor</option>
                        <option value="5">Contracted - Service</option>
                        <option value="6">Others</option>
                    </select>
                    <span v-if="errors.non_conformity_types">{{ errors.non_conformity_types }}</span>
                </div>
                <div class="form-group">
       Contracted - Service             <label for="notification_number">Notification number</label>
                    <input type="text" class="form-control" v-model="ncn.notification_number" id="notification_number">
                    <span v-if="errors.notification_number">{{ errors.notification_number }}</span>
                </div>
                <div class="form-group">
                    <label for="recurrence_number">Reccurrence number</label>
                    <input type="text" class="form-control" v-model="ncn.recurrence_number" id="recurrence_number">
                    <span v-if="errors.recurrence_number">{{ errors.recurrence_number }}</span>
                </div>
                <div class="form-group">
                    <label for="issuance_date">Issuance Date</label>
                    <datepicker placeholder="Select Issuance Date" v-model="ncn.issuance_date" id="issuance_date"></datepicker>
                    <span v-if="errors.issuance_date">{{ errors.issuance_date }}</span>
                </div>
                <div class="form-group">
                    <label for="approver">Approver</label>
                    <select v-model="approver.id" class="form-control form-control-lg" id="approver">
                        <option value="" disabled selected>Select Approver</option>
                        <option v-for="(approver, a) in approvers" :value="approver.id" v-bind:key="a">{{ approver.name }}</option>
                    </select>
                    <span v-if="errors.approver">{{ errors.approver }}</span>
                </div>
                <div class="form-group">
                    <label for="non_conformity_details">Details of Non-Conformity</label>
                    <textarea class="form-control" v-model="ncn.non_conformity_details" id="non_conformity_details"></textarea>
                    <span v-if="errors.non_conformity_details">{{ errors.non_conformity_details }}</span>
                </div>
                <div class="form-group">
                   <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange">
                </div>
                <button @click="addNcn(ncn, company, department, approver)" type="button" class="btn btn-primary">Submit</button>
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
            attachments: [],
            formData: new FormData(),
            selected_company: ' ',
            selected_department: ' ',
            keywords: '',
            errors: '',
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
        prepareFields(){
            if(this.attachments.length > 0){
                for(var i = 0; i < this.attachments.length; i++){
                    let attachment = this.attachments[i];
                    this.formData.append('attachments[]', attachment);
                }
            } 
        },
        uploadFileChange(e){
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
                this.attachments.push(files[i]);
            }
        },
        resetData(){
          this.formData = new FormData();
          this.attachments = [];  
        },
        addNcn(ncn , company, department, approver){
            this.prepareFields();
            this.formData.append('company_id', company.id);
            this.formData.append('department_id', department.id);
            this.formData.append('non_conformity_types', ncn.non_conformity_types);
            this.formData.append('notification_number', ncn.notification_number);
            this.formData.append('recurrence_number', ncn.recurrence_number);
            this.formData.append('approver_id', approver.id);
            this.formData.append('non_conformity_details', ncn.non_conformity_details);
            this.formData.append('issuance_date', ncn.issuance_date);
            
            axios.post('/ncn', this.formData)
            .then(response => {
                this.resetData();
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

