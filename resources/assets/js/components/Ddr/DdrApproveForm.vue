<template>
      <div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <span>Department</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="ddrs.length">{{ ddrs[0].department.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Reason of distribution</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="ddrs.length">{{ ddrs[0].reason_of_distribution }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Date of request</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="ddrs.length">{{ ddrs[0].date_requested }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Date Needed</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="ddrs.length">{{ ddrs[0].date_needed }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Request by</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="ddrs.length">{{ ddrs[0].requester.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Position</span>
                    </div>
                    <div class="col-md-">
                        <span v-if="ddrs.length">{{ ddrs[0].position }}</span>
                    </div>
                </div>
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
                    <div class="form-group">
                       <select v-model="ddr.status" class="form-control form-control-lg">
                           <option value="" disabled selected>Select Status</option>
                           <option value="1">Approved</option>
                           <option value="2">Disapproved</option>
                       </select>
                        <span v-if="errors.status">{{ errors.status }}</span>
                    </div>
                    <div class="form-group">
                        <label for="reason_request">Remarks</label>
                        <textarea class="form-control" v-model="ddr.remarks" id="remarks" cols="30" rows="10"></textarea>
                        <span v-if="errors.remarks">{{ errors.remarks }}</span>
                    </div>
                    <button @click="approvedDdr(ddrs[0].id, ddr)" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            ddrs: [],
            ddr:{
                status: '',
                remarks: ''
            },
            ddrlists: [],
            errors:''
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
            axios.post('/ddr-approved', {
                id : id,
                status: ddr.status,
                remarks: ddr.remarks
            })  
            .then(response => {

                var redirect = response.data.redirect;
                window.location.href = redirect;

            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        }
    },
}
</script>
