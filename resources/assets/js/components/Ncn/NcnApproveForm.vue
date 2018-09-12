<template>
      <div v-if="ncns.length">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <span>Requester</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].requester.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Position</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].requester.position }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Company</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].company.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Department</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].department.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Type of Non-conformity:</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].non_conformity_types }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Notification Number:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ ncns[0].notification_number }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span> Recurrence No:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ ncns[0].recurrence_number }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Date of issuance:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ ncns[0].issuance_date }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span> Details of non-conformity:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ ncns[0].non_conformity_details }}</span>
                    </div>
                </div>
                <form>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ncns[0].id">
                    <div class="form-group">
                       <select v-model="ncn.status" class="form-control form-control-lg" @change="selectedStatus">
                           <option value="" disabled selected>Select Status</option>
                           <option value="1">Approved</option>
                           <option value="2">Disapproved</option>
                       </select>
                        <span v-if="errors.status">{{ errors.status }}</span>
                    </div>
                    <div class="form-group">
                       <select v-if="show" v-model="ncn.notified" class="form-control form-control-lg">
                           <option v-for="(notified, n) in notifieds"  v-bind:key="n" :value="notified.id">{{ notified.name }}</option>
                       </select>
                        <span v-if="errors.status">{{ errors.status }}</span>
                    </div>
                    <div class="form-group">
                        <label for="reason_request">Remarks</label>
                        <textarea class="form-control" v-model="ncn.remarks" id="remarks" cols="30" rows="10"></textarea>
                        <span v-if="errors.remarks">{{ errors.remarks }}</span>
                    </div>
                    <button @click="approvedNcn(ncns[0].id, ncn)" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            ncns: [],
            ncn:{
                status: '',
                remarks: ''
            },
            show: false,
            notifieds: [],
            ncnId: '',
            errors:''
        }
    },
    created(){
        this.fetchNcn();
    },
    methods:{
       fetchNcn(){
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];
            
            this.ncnId = id;
            
            axios.get(`/ncn-data/${id}`)
            .then(response => {
                this.ncns = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        approvedNcn(id,ncn)
        {
            axios.post('/ncn-approved', {
                id : id,
                status: ncn.status,
                notified: ncn.notified,
                remarks: ncn.remarks,
            })  
            .then(response => {
                var redirect = response.data.redirect;
                window.location.href = redirect;

            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        fetchNotified(id,department)
        {
            axios.get(`/ncns-notified/${id}/${department}`)
            .then(response => {
                this.notifieds = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        selectedStatus(){
            if(this.ncn.status == 1)
            {
                this.show = true;
                this.fetchNotified(this.ncns[0].company_id, this.ncns[0].department_id);
            }else{
                this.show = false;
            }
        }
    },
}
</script>
