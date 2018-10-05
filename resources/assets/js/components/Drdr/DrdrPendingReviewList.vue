<template>
    <div>
        <content-placeholders v-if="loading">
            <content-placeholders-heading :img="true" />
            <content-placeholders-text :lines="3" />
        </content-placeholders>

        <spinner-loading v-if="isLoading"></spinner-loading>

        <div class="card-body table-full-width table-responsive" v-if="drdrsPendingReviews.length">
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
                    <th>Document title</th>
                    <th>Reason of request</th>
                    <th>Rev.</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="drdrsPendingReview in filteredQueues" v-bind:key="drdrsPendingReview.id">
                        <td>{{ drdrsPendingReview.id }}</td>
                        <td>{{ drdrsPendingReview.document_title }}</td>
                        <td>{{ drdrsPendingReview.reason_request  }}</td>
                        <td v-if="drdrsPendingReview.rev_number !='null'">{{ drdrsPendingReview.rev_number }}</td>
                        <td v-else style="padding-left: 30px"> - </td>
                        <td>
                            <button  class="btn btn-warning btn-round btn-fill" @click="viewDrdr(drdrsPendingReview.id)">Review</button>
                            <!-- <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${drdrsPendingReview.id}`">Delete</button> -->
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3" v-if="drdrsPendingReviews.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ drdrsPendingReviews.length }} DRDR form(s)</span>
            </div>
        </div>

        <div class="card-body table-full-width table-responsive" v-if="!drdrsPendingReviews.length && !loading">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Document title</th>
                    <th>Reason of request</th>
                    <th>Rev.</th>
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
import VueContentPlaceholders from 'vue-content-placeholders';
import Datepicker from 'vuejs-datepicker';
import SpinnerLoading from '../SpinnerLoading';
import moment from 'moment';
export default {
    components:{
        VueContentPlaceholders,
        SpinnerLoading,
        Datepicker
    },
    data(){
        return{
            drdrsPendingReviews:[],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false,
            startDate: '',
            endDate: '',
            isLoading: false
        }
    },
    created(){
        console.log('global variable: ', this.$http)
        this.fetchDrdrsPendingReviews();
    },
    methods:{
        moment,
        viewDrdr($id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+'/drdr-review/'+$id;
        },
        fetchDrdrsPendingReviews(){
            this.loading = true;
            axios.get('/drdrs-pending-reviews')
            .then(response => {
                this.drdrsPendingReviews = response.data;
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
           
            axios.post('/drdrs-pending-reviews-generate', {
                'startDate': startDate,
                'endDate': endDate
            })
            .then(response => { 
                this.isLoading = false;
                this.drdrsPendingReviews = response.data;
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
    computed:{
        filteredDrdrs(){
            let self = this;
            return self.drdrsPendingReviews.filter(drdrsPendingReview => {
                return drdrsPendingReview.document_title.toLowerCase().includes(this.keywords.toLowerCase()) ||
                       drdrsPendingReview.reason_request.toLowerCase().includes(this.keywords.toLowerCase()) || 
                       drdrsPendingReview.rev_number.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredDrdrs.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredDrdrs.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return queues_array;
        }
    },
}
</script>

