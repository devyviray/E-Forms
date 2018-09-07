<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form v-if="users.length">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" placeholder="Name"  v-model="users[0].name" id="name">
                        <span v-if="errors.name">{{ errors.name }}</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="text" class="form-control" placeholder="Email" v-model="users[0].email" id="email">
                        <span v-if="errors.email">{{ errors.email }}</span>
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="position" class="form-control" placeholder="Position" v-model="users[0].position" id="position">
                        <span v-if="errors.position">{{ errors.position }}</span>
                    </div>
                    <div class="form-group">
                        <multiselect
                            v-model="selected_company"
                            :options="companies"
                            :multiple="true"
                            track-by="id"
                            :custom-label="customLabelCompany"
                            >
                        </multiselect>
                        <span v-if="errors.company">{{ errors.company }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="users[0].department.id" class="form-control form-control-lg">
                           <option value="" disabled selected>Select department</option>
                           <option v-for="department in departments" v-bind:key="department.id" :value="department.id">
                               {{ department.name }}
                           </option>
                       </select>
                        <span v-if="errors.department">{{ errors.department }}</span>
                    </div>
                    <div class="form-group">
                        <multiselect
                            v-model="selected_role"
                            :options="roles"
                            :multiple="true"
                            track-by="id"
                            :custom-label="customLabelRole"
                            >
                        </multiselect>
                        <span v-if="errors.roles">{{ errors.roles }}</span>
                    </div>
                    <button @click="editUser(users[0],selected_company,selected_role)" type="button" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import Multiselect from 'vue-multiselect'
export default {
    components: { Multiselect },
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
            selected_company: '',
            departments: [],
            roles: [],
            selected_role: '',
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
        editUser(user,selected_company,selected_role){

            let comapanyids = [];
            let roleids = [];
            selected_company.forEach((selected_company) => {
                    comapanyids.push(selected_company.id);
            });
            selected_role.forEach((selected_role) => {
                roleids.push(selected_role.id);
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
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                if(error.response.data) {
                    this.errors = error.response.data.errors;
                }
            })

        },
    }
}
</script>
