<template>
      <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="row">
            <div class="col-md-12" v-if="ddrs.length">
                <div class="form-group">
                    <h1 style="color: #888888; text-align: center;">APPROVE DDR</h1>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td> <strong> Department: </strong> </td>
                        <td> {{ ddrs[0].department.name }} </td>
                    </tr>
                    <tr>
                        <td> <strong> Reason of Distribution: </strong> </td>
						<td v-if="ddrs[0].reason_of_distribution == 1">
							 Relevant external doc. (controll copy)
						</td>
						<td v-if="ddrs[0].reason_of_distribution == 2">
							 Customer request (uncontrolled copy)			
						</td>
						<td v-if="ddrs[0].reason_of_distribution == 3"> Others: </td>
		
                    </tr>
                    <tr>
                        <td> <strong> Date of Request </strong> </td>
                        <td> {{ ddrs[0].date_request }} </td>
                    </tr>
                    <tr>
                        <td> <strong> Date Needed </strong> </td>
                        <td> {{ ddrs[0].date_needed }} </td>
                    </tr>
                    <tr>
                        <td> <strong> Request By </strong> </td>
                        <td>{{ ddrs[0].requester.name }} </td>
                    </tr>
                    <tr>
                        <td> <strong>   Position </strong> </td>
                        <td>{{ ddrs[0].requester.position }} </td>
                    </tr>
                </table>
                <div  class="row">
                     <div class="col-md-12">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Document Title</th>
                                <th>Control Code</th>
                                <th>Rev No.</th>
                                <th>Copy No.</th>
                                <th>Copy Holder</th>
                            </thead>
                            <tbody>
                                <tr v-if="ddrs.length" v-for="(ddrlist, d) in ddrlists" v-bind:key="d">
                                    <td> {{ d + 1 }} </td>
                                    <td> {{ ddrlist.document_title }} </td> 
                                    <td> {{ ddrlist.control_code }} </td>
                                    <td> {{ ddrlist.rev_number }} </td>
                                    <td> {{ ddrlist.copy_number }} </td>
                                    <td> {{ddrlist.copy_holder }} </td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
                </div>
                <form>
                    <input type="hidden" class="form-control" placeholder="Name" v-if="ddrs.length" v-model="ddrs[0].id">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select v-model="ddr.status" class="form-control form-control-lg" id=status>
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Disapproved</option>
                                    </select>
                                    <span class="error" v-if="errors.status">{{ errors.status[0] }}</span>
                                </div>   
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="remarks" class="col-sm-2 col-form-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" v-model="ddr.remarks" id="remarks" cols="30" rows="10"></textarea>
                                    <span class="error" v-if="errors.remarks">{{ errors.remarks[0] }}</span>
                                </div>   
                            </div>
                        </div>
                    </div>
                    <button @click="approvedDdr(ddrs[0].id, ddr)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>
<script>
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

export default {
    components:{
        SpinnerLoading
    },
    data(){
        return{
            ddrs: [],
            ddr:{
                status: '',
                remarks: ''
            },
            ddrlists: [],
            errors:'',
            isLoading: false
        }
    },
    created(){
        this.fetchDdr();
    },
    methods:{
       fetchDdr(){
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];
            
            this.ddrId = id;
            
            axios.get(`/ddr-data/${id}`)
            .then(response => {
                this.ddrs = response.data;
                this.ddrlists = this.ddrs[0].ddr_lists;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        approvedDdr(id,ddr)
        {
            this.isLoading = true;
            axios.post('/ddr-approved', {
                id : id,
                status: ddr.status,
                remarks: ddr.remarks
            })  
            .then(response => {
                var message = ddr.status == 1 ? 'Approved' :  'Disapproved';
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DDR Succesfully '+message,
                    position: 'top right'
                });
                window.location.href = response.data.redirect;

            })
            .catch(error => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            })
        }
    },
}
</script>
