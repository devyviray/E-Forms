<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header mb-3">
                <h4 class="card-title">Non-conformance Notification</h4>   
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="name"> Search </label>
                    <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                </div>
                <div class="col-md-3">
                    <label for="date"> Search by date </label>
                    <datepicker v-model="startDate" placeholder="Select Start Date" id="date"></datepicker>
                    <span class="error" v-if="errors.startDate">{{ errors.startDate[0] }}</span>
                </div>
                <div class="col-md-3" style="margin-top: 29px">
                    <datepicker v-model="endDate" placeholder="Select End Date"></datepicker>
                    <span class="error" v-if="errors.endDate">{{ errors.endDate[0] }}</span>
                </div>
                <div class="col-md-2" style="margin-top: 29px">
                    <button @click="generateByDate" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Generate</button>
                </div>
            </div>
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
                    <tr v-if="loading">
                        <td colspan="8">
                            <content-placeholders>
                                <content-placeholders-heading :img="true" />
                                <content-placeholders-text :lines="3" />
                            </content-placeholders>
                        </td>
                    </tr>
                    <tr v-if="!ncns.length && !loading">
                        <td>No data available in the table</td>
                    </tr>

                    <tr v-for="ncn in filteredQueues" v-bind:key="ncn.id">
                        <td>{{ ncn.id }}</td>
                        <td>{{ ncn.requester.name }}</td>
                        <td>{{ ncn.requester.position }}</td>
                        <td>{{ ncn.notification_number }}</td>
                        <td>{{ moment(ncn.issuance_date).format('LL') }}</td>   
                        <td>{{ ncn.approver.name }}</td>
                        <td>
                            <span style="color: red" v-if="ncn.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ncn.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a target="_blank" :href="viewNcnDetails+ncn.id" class="dropdown-item">View</a>
                                    <a  v-if="roleId.includes(3) && ncn.status == 2 && ncn.approver_id == userId" class="dropdown-item" :href="approvalLnik+ncn.id">Approve</a>
                                </div>
                            </div>
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
                <span>{{ filteredQueues.length }} NCN form(s)</span>
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

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import SpinnerLoading from '../SpinnerLoading';

export default {
    props: [
        'roleId',
        'userId'
    ],
    components:{
      Datepicker,
      SpinnerLoading  
    },
    data(){
        return{
            ncns: [],
            startDate: '',
            endDate: '',
            keywords: '',
            currentPage: 0,
            itemsPerPage: 10,
            errors: '',
            loading: false,
            isLoading: false
        }
    },
    created(){
        this.fetchNcns();
    },
    methods:{
        moment,
        fetchNcns()
        {   
            this.loading = true;
            axios.get('/admin/ncns-all')
            .then(response => {
                this.ncns = response.data;
                this.loading = false    ;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        generateByDate(){

            this.isLoading = true;
            var startDate  =  this.startDate ? moment(this.startDate).format() : '';
            var endDate = this.endDate ? moment(this.endDate).format() : '';

            axios.post('/ncns-generate',{
                'startDate': startDate,
                'endDate': endDate
            })
            .then(response => {
                this.isLoading = false;
                this.ncns = response.data;
            })
            .catch(error => {
                this.isLoading = false;
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
                return ncn.requester.name.toLowerCase().includes(this.keywords.toLowerCase()) ||
                       ncn.requester.position.toLowerCase().includes(this.keywords.toLowerCase()) ||
                       ncn.notification_number.toLowerCase().includes(this.keywords.toLowerCase()) ||
                       ncn.approver.name.toLowerCase().includes(this.keywords.toLowerCase())
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
        approvalLnik(){
            return window.location.origin+'/ncn-approve/';
        },
         viewNcnDetails()
        {
            return window.location.origin+'/admin/ncn-details/';
        },
    }
}
</script>

