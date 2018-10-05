<template>
    <div>
        <content-placeholders v-if="loading">
            <content-placeholders-heading :img="true" />
            <content-placeholders-text :lines="3" />
        </content-placeholders>

        <spinner-loading v-if="isLoading"></spinner-loading>

        <div class="card-body table-full-width table-responsive" v-if="ccirSubmitteds.length">
            <div class="row mb-4 ml-2">
                <div class="col-md-4">
                    <label for="name">Search</label>
                    <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                </div>
                <div class="col-md-3">
                    <label for="date">Search by date</label>    
                    <datepicker v-model="startDate" placeholder="Select Start Date" id="date"></datepicker>
                    <span class="error" v-if="errors.startDate">{{ errors.startDate[0] }}</span>
                </div>
                <div class="col-md-3" style="margin-top: 29px">
                    <datepicker v-model="endDate" placeholder="Select End Date"></datepicker>
                    <span class="error" v-if="errors.endDate">{{ errors.endDate[0] }}</span>
                </div>
                <div class="col-md-2" style="margin-top: 29px">
                    <button @click="generateByDate" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Generate</button>
                </div>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Commodity</th>
                    <th>Nature of Complaint</th>
                    <th>Date of Issuance</th>
                    <th>Validity</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="ccirSubmitted in filteredQueues" v-bind:key="ccirSubmitted.id">
                        <td>{{ ccirSubmitted.id }}</td>
                        <td>{{ ccirSubmitted.requester.name }}</td>
                        <td>{{ ccirSubmitted.commodity }}</td>
                        <td>
                            <span  v-if="ccirSubmitted.nature_of_complaint == 1"> Wet/Lumpy </span>
                            <span  v-else-if="ccirSubmitted.nature_of_complaint == 2"> Busted bag </span>
                            <span  v-else-if="ccirSubmitted.nature_of_complaint == 3"> Under/Over weight </span>
                            <span  v-else-if="ccirSubmitted.nature_of_complaint == 4"> Infestation </span>
                            <span  v-else-if="ccirSubmitted.nature_of_complaint == 5"> Dirty packaging </span>
                            <span  v-else> Others </span>
                        </td>
                        <td>{{ moment(ccirSubmitted.delivery_date).format('LL') }}</td>
                        <td>
                            <span  v-if="ccirSubmitted.status == 2"> PENDING </span>
                            <span  v-else-if="ccirSubmitted.status == 9"> {{ ccirSubmitted.car_number }}</span>
                            <span  v-else> INVALID </span>
                        </td>
                        <td style="display:inline-grid">
                            <button  class="btn btn-primary btn-round btn-fill mb-1" @click="viewSubmittedCcir(ccirSubmitted.id)">View</button>
                            <!-- <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ccirSubmitted.id}`">Edit</button> -->
                            <!-- <button  class="btn btn-danger btn-round btn-fill mb-1" data-toggle="modal" :data-target="`#deleteModal-${ccirSubmitted.id}`">Delete</button> -->
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3" v-if="ccirSubmitteds.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ ccirSubmitteds.length }} CCIR form(s)</span>
            </div>
        </div>

        <div class="card-body table-full-width table-responsive" v-if="!ccirSubmitteds.length && !loading">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Brand Name</th>
                    <th>Nature of Complaint</th>
                    <th>Date of Issuance</th>
                    <th>Validity</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr>
                        <td>No data available in the table</td>
                    </tr>    
                </tbody>
            </table>
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
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import SpinnerLoading from '../SpinnerLoading';
import Datepicker from 'vuejs-datepicker';
export default {
    components:{
        VueContentPlaceholders,
        SpinnerLoading,
        Datepicker
    },
    data(){
        return{
            ccirSubmitteds: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false,
            isLoading: false,
            startDate: '',
            endDate: ''
        }
    },
    created(){
        this.fetchCcirSubmitted();
    },
    methods:{
        moment,
        viewSubmittedCcir(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/ccir-view/${id}`;
        },
        fetchCcirSubmitted(){
            this.loading = true;
            axios.get('/ccirs-submitted')
            .then(response => {
                this.ccirSubmitteds = response.data;
                this.loading = false;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        generateByDate(){
           this.isLoading = true;
           var startDate  =  this.startDate ? moment(this.startDate).format() : '';
           var endDate = this.endDate ? moment(this.endDate).format() : '';
           
            axios.post('/ccirs-submitted-generate', {
                'startDate': startDate,
                'endDate': endDate
            })
            .then(response => { 
                this.isLoading = false;
                this.ccirSubmitteds = response.data;
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
        filteredCcirs(){
            let self = this;
            return self.ccirSubmitteds.filter(ccirSubmitted => {
                return ccirSubmitted.complainant.toLowerCase().includes(this.keywords.toLowerCase()) || 
                       ccirSubmitted.commodity.toLowerCase().includes(this.keywords.toLowerCase()) || 
                       ccirSubmitted.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredCcirs.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredCcirs.slice(index, index + this.itemsPerPage);

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

