<template>
      <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header mb-3">
                <h4 class="card-title">Document Review & Distribution Request</h4>   
            </div>
            <div class="row mb-4">
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
                    <th>Company</th>
                    <th>Rev.</th>
                    <th>Reviewer</th>
                    <th>Approver</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-if="loading">
                        <td colspan="7">
                        <content-placeholders>
                            <content-placeholders-heading :img="true" />
                            <content-placeholders-text :lines="3" />
                        </content-placeholders>
                        </td>
                    </tr>
                    <tr v-for="drdr in filteredQueues" v-bind:key="drdr.id">
                        <td>{{ drdr.id }}</td>
                        <td>{{ drdr.document_title }}</td>
                        <td>{{ drdr.company.name  }}</td>
                        <td v-if="drdr.rev_number !== 'null'">{{ drdr.rev_number }}</td>
                        <td v-else style="padding-left:30px"> - </td>
                        <td>
                            {{ drdr.reviewer.name }}<br>
                            <span style="color: red" v-if="drdr.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdr.status == 5"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td v-if="drdr.approver">
                            {{ drdr.approver.name }}<br>
                            <span style="color: red" v-if="drdr.status == 3"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdr.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td style="padding-left: 30px" v-else>{{ ' - '  }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a  @click="viewDrdrDetails(drdr.id)" class="dropdown-item"  href="javascript:void(0)">View</a>
                                    <a v-if="drdr.status == 4" class="dropdown-item"  :href="verifyLink+drdr.id">Mark as verify</a>
                                    <a  v-if="roleId.includes(3) && drdr.status == 3 && drdr.approver_id == userId" class="dropdown-item" :href="approvalLnik+drdr.id">Approve</a>
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
                <span>{{ filteredQueues.length }} DRDR form(s)</span>
            </div>
        </div>

        <!-- Mark as distributed Modal -->
        <div  class="modal fade" id="distributedDrdrModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                    <div class="form-group">
                        <span> Are you sure to verify this document ?</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="distributeDrdr(selected_id)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                </div>
            </div>
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
<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

export default {
    props: [
        'roleId',
        'userId'
        ],
    components:{
      Datepicker,
      VueContentPlaceholders,
      SpinnerLoading
    },
    data(){
        return{
            drdrs: [],
            startDate: '',
            endDate: '',
            selected_id: '',
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false,
            isLoading: false
        }
    },
    watch: {
        drdrs: function (val, oldVal) {
       
        }
    },
    created(){
        this.fetchDrdrs();
    },
    methods:{
        moment,
        fetchDrdrs()
        {
            this.loading = true;
            axios.get('/admin/drdrs-all')
            .then(response => {
                this.drdrs = response.data;
                this.loading = false;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        viewDrdrDetails(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/admin/drdr-details/${id}`;
            
        },
        generateByDate(){
           this.isLoading = true;
           var startDate  =  this.startDate ? moment(this.startDate).format() : '';
           var endDate = this.endDate ? moment(this.endDate).format() : '';
           
            axios.post('/drdrs-generate', {
                'startDate': startDate,
                'endDate': endDate
            })
            .then(response => { 
                this.isLoading = false;
                this.drdrs = response.data;
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
    computed:
    {
        filteredDrdrs(){
            let self = this;
            return self.drdrs.filter(drdr => {
                return drdr.document_title.toLowerCase().includes(this.keywords.toLowerCase()) || 
                       drdr.company.name.toLowerCase().includes(this.keywords.toLowerCase()) || 
                       drdr.rev_number.toLowerCase().includes(this.keywords.toLowerCase()) || 
                       drdr.reviewer.name.toLowerCase().includes(this.keywords.toLowerCase())
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
        },
        verifyLink(){
            return window.location.origin+'/admin/drdr-verify/';
        },
        approvalLnik(){
            return window.location.origin+'/drdr-approve/';
        }
    }
}
</script>

