<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <form>
                            <div class="form-group">
                                <h1 style="color: #888888; text-align: center;">CHANGE PASSWORD</h1>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 col-form-label">New Passsword</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" placeholder="New Password" v-model="user.new_password" id="new_password">
                                            <span class="error" v-if="errors.new_password">{{ errors.new_password[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="new_password_confirmation" class="col-sm-3 col-form-label">Confirm New Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" placeholder="Confirm Password" v-model="user.new_password_confirmation" id="new_password_confirmation">
                                            <span class="error" v-if="errors.new_password_confirmation">{{ errors.new_password_confirmation[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                            <button @click="changePassword(user)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div> 
            </div>    
    </div>
</template>
<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>

<script>
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);
export default {
    components: {
        SpinnerLoading
    },
    data(){
        return{
            user: {
                new_password: '',
                new_password_confirmation:'',
            },
            errors: ' ',
            isLoading: false
         }
    },
    methods: {
        resetData(){
            this.user.new_password = '';
            this.errors =  [];
            this.user.new_password_confirmation = '';
        },
        changePassword(user){
            this.isLoading = true;
            axios.post('/user/change-password',{
                new_password: user.new_password,
                new_password_confirmation: user.new_password_confirmation
            })
            .then(response => { 
                this.isLoading = false;
                this.resetData();
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Password Successfully Changed',
                    position: 'top right'
                });
            })
            .catch(error => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>
