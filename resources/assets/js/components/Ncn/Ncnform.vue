<template>
  <div>
      <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12">
            <div class="row">
                <div class="card col-md-12">
                    <form>
                        <div class="form-group">
                            <h1 style="color: #888888; text-align: center;">ADD NCN</h1>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="company" class="col-sm-3 col-form-label">Company</label>
                                    <div class="col-sm-9">
                                        <select v-model="company.id" class="form-control form-control-lg" id="company">
                                            <option value="" disabled selected>Select Company</option>
                                            <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.company">{{ errors.company[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="companyLocation" class="col-sm-3 col-form-label">Location</label>
                                    <div class="col-sm-9">
                                        <select v-model="company.location" class="form-control form-control-lg" @change="getCompanyId(company.location)" id="companyLocation">
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
                                        <select v-model="department.id" class="form-control form-control-lg" @change="getDepartmentId(department.id)" id="department">
                                            <option value="" disabled selected>Select Department</option>
                                            <option v-for="(department, d) in departments" :value="department.id" v-bind:key="d">{{ department.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.department">{{ errors.department[0] }}</span>
                                     </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="non_conformity_types" class="col-sm-3 col-form-label">Type of Non Conformity</label>
                                    <div class="col-sm-9">
                                        <select v-model="ncn.non_conformity_types" class="form-control form-control-lg" id="non_conformity_types" @change="selectedReason(ncn.non_conformity_types)">
                                            <option value="" disabled selected>Select type</option>
                                            <option value="1">Customer - Returns</option>
                                            <option value="2">Objective not Met</option>
                                            <option value="3">Process Related</option>
                                            <option value="4">Vendor</option>
                                            <option value="5">Contracted - Service</option>
                                            <option value="6">Others</option>
                                        </select>
                                        <span class="error" v-if="errors.non_conformity_types">{{ errors.non_conformity_types[0] }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group row" v-if="others == 1">
                                    <label for="others" class="col-sm-3 col-form-label">Others (Please specify)</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Others (Please specify)" v-model="ncn.others">
                                        <span class="error" v-if="errors.others">{{ errors.others[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="notification_number" class="col-sm-3 col-form-label">Notification number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" v-model="ncn.notification_number" id="notification_number">
                                        <span class="error" v-if="errors.notification_number">{{ errors.notification_number[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="recurrence_number" class="col-sm-3 col-form-label">Recurrence number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" v-model="ncn.recurrence_number" id="recurrence_number">
                                        <span class="error" v-if="errors.recurrence_number">{{ errors.recurrence_number[0] }}</span>
                                    </div>   
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="issuance_date" class="col-sm-3 col-form-label">Issuance Date</label>
                                    <div class="col-sm-9">
                                        <datepicker placeholder="Select Issuance Date" v-model="ncn.issuance_date" id="issuance_date" :disabledDates="disabledDates"></datepicker>
                                        <span class="error" v-if="errors.issuance_date">{{ errors.issuance_date[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="approver" class="col-sm-3 col-form-label">Approver</label>
                                    <div class="col-sm-9">
                                        <select v-model="approver.id" class="form-control form-control-lg" id="approver">
                                            <option value="" disabled selected>Select Approver</option>
                                            <option v-for="(approver, a) in approvers" :value="approver.id" v-bind:key="a">{{ approver.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.approver">{{ errors.approver[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="non_conformity_details" class="col-sm-3 col-form-label">Details of Non-Conformity</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" v-model="ncn.non_conformity_details" id="non_conformity_details"></textarea>
                                            <span class="error" v-if="errors.non_conformity_details">{{ errors.non_conformity_details[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="attachments" class="col-sm-3 col-form-label">Attach File</label>
                                        <div class="col-sm-9">
                                            <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                            <span class="error" v-if="errors.attachments">{{ errors.attachments[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <button @click="addNcn(ncn, company, department, approver)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
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
                non_conformity_details: '',
                attached_files: '', 
                status: '',
                remarks: '',
                others: '',
            },
            others: ' ',
            locations: [],
            companies: [],
            company:{
                id: '',
                name: '',
                address: '',
                location: ''
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
            isLoading: false,
            disabledDates: {
                to: new Date(Date.now() - 8640000)
            },
            fileSize: 0,
            maximumSize: 5000000,
        }
    },
    created(){
        this.fetchCompanies();
        this.fetchDepartments();
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
        prepareFields(){
            if(this.attachments.length > 0){
                for(var i = 0; i < this.attachments.length; i++){
                    let attachment = this.attachments[i];
                    this.formData.append('attachments[]', attachment);
                }
            } 
        },
        uploadFileChange(e){
            this.attachments = [];
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
                this.attachments.push(files[i]);
                this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
            }
            if(this.fileSize > 5){
                alert('File size exceeds 5 MB');
                document.getElementById('attachments').value = "";
                this.attachments = [];
                this.fileSize = 0;
            }
        },
        resetData(){
          this.formData = new FormData();
          this.attachments = [];  
        },
        addNcn(ncn , company, department, approver){
            this.isLoading = true;
            this.errors = [];
            this.prepareFields();
            this.formData.append('non_conformity_details', ncn.non_conformity_details);
            this.formData.append('company', company.location);
            this.formData.append('company_location', company.location);
            this.formData.append('department', department.id);
            this.formData.append('non_conformity_types', ncn.non_conformity_types);
            this.formData.append('notification_number', ncn.notification_number);
            this.formData.append('recurrence_number', ncn.recurrence_number);
            this.formData.append('approver', approver.id);
            this.formData.append('issuance_date', ncn.issuance_date);
            this.formData.append('others', ncn.others);
            
            axios.post('/ncn', this.formData)
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'NCN Succesfully Added',
                    position: 'top right'
                });
                this.resetData();
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false;
                this.attachments = [];
                this.errors = error.response.data.errors;
            });
        },
        selectedReason(id){
           id == 6 ? this.others = 1 : this.others = 2; 
        }
    },
     components: {
         Datepicker,
         SpinnerLoading
    }
}
</script>

