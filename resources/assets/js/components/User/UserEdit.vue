<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12">
            <div class="row"> 
                <div class="col-md-12">
                    <form v-if="users.length">
                        <div class="form-group">
                            <h1 style="color: #888888; text-align: center;">EDIT USER</h1>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Name"  v-model="users[0].name" id="name">
                                        <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>  
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Email address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Email" v-model="users[0].email" id="email">
                                        <span class="error" v-if="errors.email">{{ errors.email[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Position</label>
                                    <div class="col-sm-9">
                                        <input type="position" class="form-control" placeholder="Position" v-model="users[0].position" id="position">
                                        <span class="error" v-if="errors.position">{{ errors.position[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Company</label>
                                    <div class="col-sm-9">
                                        <multiselect
                                            v-model="users[0].companies"
                                            :options="companies"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabelCompany"
                                            id="company"
                                            >
                                        </multiselect>
                                        <span class="error" v-if="errors.company">{{ errors.company[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Department</label>
                                    <div class="col-sm-9">
                                        <select v-model="users[0].department.id" class="form-control form-control-lg" id="department">
                                            <option value="" disabled selected>Select department</option>
                                            <option v-for="department in departments" v-bind:key="department.id" :value="department.id">
                                                {{ department.name }}
                                            </option>
                                        </select>
                                        <span class="error" v-if="errors.department">{{ errors.department[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Role</label>
                                    <div class="col-sm-9">
                                        <multiselect
                                            v-model="users[0].roles"
                                            :options="roles"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabelRole"
                                            id= role
                                            >
                                        </multiselect>
                                        <span class="error" v-if="errors.roles">{{ errors.roles[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button @click="editUser(users[0])" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Update</button>
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
import CxltToastr from 'cxlt-vue2-toastr';
import SpinnerLoading from '../SpinnerLoading';

Vue.use(CxltToastr);
export default {
    components: { 
        Multiselect,
        SpinnerLoading
    },
    data(){
        return{
            users: [],
            user_id: '',
            pagination: {}, 
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
            companies: [],
            departments: [],
            roles: [],
            isLoading: false
         }
    },
    created(){  
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchRoles();
        this.fetchUser();
    },
    methods: {
        customLabelCompany (company) {
            return `${company.name}`
        },
        customLabelRole (role) {
            return `${role.name}`
        },
        fetchUser(){
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];
            
            axios.get(`/user/${id}`)
            .then(response => {
                this.users = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        fetchCompanies(){
            axios.get('/companies')
                .then(response => {
                    this.companies = response.data;
                })
                .catch(err => console.log(err));  
        },
        fetchDepartments(){
            axios.get('/departments')    
            .then(response => {
                this.departments = response.data;
            });
        },
        fetchRoles(){
            axios.get('/roles')    
            .then(response => {
                this.roles = response.data;
            });
        },
        editUser(user){
            this.isLoading = true;
            let comapanyids = [];
            let roleids = [];
            user.companies.forEach((company) => {
                    comapanyids.push(company.id);
            });
            user.roles.forEach((role) => {
                roleids.push(role.id);
             });
            axios.patch(`/user/${user.id}`,{
                id: user.id, 
                name: user.name,
                email: user.email,
                company: comapanyids,
                department: user.department.id,
                role: roleids
            })
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'User Succesfully Updated',
                    position: 'top right'
                });

                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false;
                if(error.response.data) {
                    this.errors = error.response.data.errors;
                }
            })

        },
    }
}
</script>
