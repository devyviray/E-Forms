<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <form>
                        <div class="form-group">
                            <h1 style="color: #888888; text-align: center;">ADD USER</h1>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Name" v-model="user.name" id="name">
                                        <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-form-label">Email address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Email" v-model="user.email" id="email">
                                        <span class="error" v-if="errors.email">{{ errors.email[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="password" class="col-sm-3 col-form-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control"  v-model="user.password" id="password">
                                        <span class="error" v-if="errors.password">{{ errors.password[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="position" class="col-sm-3 col-form-label">Position</label>
                                    <div class="col-sm-9">
                                        <input type="position" class="form-control" placeholder="Position" v-model="user.position" id="position">
                                        <span class="error" v-if="errors.position">{{ errors.position[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="selected_company" class="col-sm-3 col-form-label">Company</label>
                                    <div class="col-sm-9">
                                        <multiselect
                                            v-model="selected_company"
                                            :options="companies"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabelCompany"
                                            placeholder="Select Company"
                                            id="selected_company"
                                            >
                                        </multiselect>
                                        <span class="error" v-if="errors.company">{{ errors.company[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="selected_department" class="col-sm-3 col-form-label">Department</label>
                                    <div class="col-sm-9">
                                        <select v-model="selected_department" class="form-control form-control-lg" id="selected_department">
                                            <option value="" disabled selected>Select department</option>
                                            <option v-for="department in departments" v-bind:key="department.id" :value="department.id">
                                                {{ department.name }}
                                            </option>
                                        </select>
                                        <span class="error" v-if="errors.department">{{ errors.department[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="selected_role" class="col-sm-3 col-form-label">Role</label>
                                    <div class="col-sm-9">
                                        <multiselect
                                            v-model="selected_role"
                                            :options="roles"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabelRole"
                                            placeholder="Select Role"
                                            id="selected_role"
                                            >
                                        </multiselect>
                                        <span class="error" v-if="errors.role">{{ errors.role[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    
                                </div>
                            </div>
                        </div>
                        <button @click="addUser(user,selected_company,selected_department,selected_role)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>

<script>
import Multiselect from 'vue-multiselect';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);
export default {
    components: {
        Multiselect,
        SpinnerLoading
    },
    data(){
        return{
            user: {
                id: ' ',
                name: ' ',
                email: ' ',
                password: '',
                position: ' ',
                department: ' ',
            },
            user_id: '',
            pagination: {}, 
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
            companies: [],
            company: {
                id: '',
                company_name: ''
            },
            selected_company: '',
            departments: [],
            department: {
                id: '',
                department_name: '',
            },
            selected_department: '',
            roles: [],
            role: {
                id: '',
                name: '',
            },
            selected_role: '',
            isLoading: false

         }
    },
    created(){  
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchRoles();
    },
    methods: {
        customLabelCompany (company) {
            return `${company.name + ' - ' + company.address }`
        },
        customLabelRole (role) {
            return `${role.name}`
        },
        addUser(user,selected_company,selected_department,selected_role){
            this.isLoading = true;
            let comapanyids = [];
            let roleids = [];
            if(selected_company)
            {
                selected_company.forEach((selected_company) => {
                comapanyids.push(selected_company.id);
                });
            }
            if(selected_role)
            {
                selected_role.forEach((selected_role) => {
                roleids.push(selected_role.id);
                });
            }
            axios.post('/user',{
                name: user.name,
                email: user.email,
                password: user.password,
                position: user.position,
                company: comapanyids,
                department: selected_department,
                role: roleids
            })
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'User Succesfully Added',
                    position: 'top right'
                });
                this.user.name = " ",
                this.user.email = " ",
                this.user.password = " ",
                this.errors = [],
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                if(error.response.data) {
                    this.isLoading = false;
                    this.errors = error.response.data.errors;
                }
            });
        },
        fetchCompanies(){
            axios.get('/companyLocation')
            .then(response => {
                this.companies = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });  
        },
        fetchDepartments(){
            axios.get('/departments')    
            .then(response => {
                this.departments = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        fetchRoles(){
            axios.get('/roles')    
            .then(response => {
                this.roles = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
    }
}
</script>
