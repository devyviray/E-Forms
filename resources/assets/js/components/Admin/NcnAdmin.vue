<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header ">
                <h4 class="card-title">Non-conformance Notification</h4>   
            </div>
            <div class="row mb-3">
                <div class="row">
                    <datepicker v-model="startDate" placeholder="Select Start Date"></datepicker>
                    <datepicker v-model="endDate" placeholder="Select End Date"></datepicker>
                    <button @click="generateByDate" class="btn btn-primary">Generate</button>
                </div>
            </div>
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Position</th>
                    <th>Notification</th>
                    <th>Date of Issuance</th>
                    <th>Approver</th>
                    <th>Status</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="ncn in filteredQueues" v-bind:key="ncn.id">
                        <td>{{ ncn.id }}</td>
                        <td>{{ ncn.requester.name }}</td>
                        <td>{{ ncn.requester.position }}</td>
                        <!-- <td>{{ ncn.attached_files }}</td>
                        <td>{{ ncn.non_conformity_details }}</td> -->
                        <td>{{ ncn.notification_number }}</td>
                        <td>{{ moment(ncn.issuance_date).format('LL') }}</td>   
                        <td>{{ ncn.approver.name }}</td>
                        <td>
                            <span style="color: red" v-if="ncn.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ncn.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>
                            <button @click="viewNcnDetails(ncn.id)" class="btn btn-warning">View</button>
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ ncns.length }} Ncn form(s)</span>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';

export default {
    components:{
      Datepicker  
    },
    data(){
        return{
            ncns: [],
            startDate: '',
            endDate: '',
            keywords: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchNcns();
    },
    methods:{
        moment,
        fetchNcns()
        {
            axios.get('/admin/ncns-all')
            .then(response => {
                this.ncns = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        viewNcnDetails(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/admin/ncn-details/${id}`;
            
        },
        generateByDate(){
            axios.get('/ncns-generate/'+ this.startDate + '/' + this.endDate)
            .then(response => { 
                this.ncns = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },

        resetStartRow() {
            this.currentPage = 0;
        },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }
    },
    computed: {
        filteredNcns(){
            let self = this;
            return self.ncns.filter(ncn => {
                return ncn.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredNcns.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredNcns.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }   

            return queues_array;
        },
    }
}
</script>

