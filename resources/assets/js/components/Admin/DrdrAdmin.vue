<template>
      <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header mb-3">
                <h4 class="card-title">Document Review & Distribution Request</h4>   
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="name">Search</label>
                    <input type="text" class="form-control" placeholder="Search by Document Title, Company" v-model="keywords" id="name">
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
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="date"> Filter By Status </label>
                    <select v-model="status" class="form-control form-control-lg" @change="filterDrdrs">
                        <option value="" selected>Reset Filter</option>
                        <option value="4">Not Yet Verified</option>
                        <option value="14">Verified</option>
                    </select>
                </div>
                 <div class="col-md-3" v-if="drdrs.length > 0">
                    <download-excel
                        :data   = "drdrs"
                        :fields = "json_fields"
                        class   = "hidden-xs btn btn-new btn-wd btn-neutral btn-round"
                        style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33)); margin-top: 29px"
                        name    = "DRDR.xls">
                        EXPORT TO EXCEL
                    </download-excel>
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
                    <tr v-for="drdr in filteredQueues" v-bind:key="drdr.id">
                        <td>{{ drdr.id }}</td>
                        <td>{{ drdr.document_title }}</td>
                        <td>{{ drdr.company.name  }}</td>
                        <td v-if="drdr.rev_number !== null">{{ drdr.rev_number }}</td>
                        <td style="padding-left: 30px" v-else> - </td>
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
                            <span style="color: red" v-if="drdr.status == 4"> NOT YET VERIFIED </span>
                            <span style="color: green" v-else-if="drdr.status == 14"> VERIFIED </span>
                            <span style="padding-left: 15px" v-else>{{ ' - '  }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button  class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a target="_blank" :href="viewDrdrDetails+drdr.id" class="dropdown-item">View</a>
                                    <a target="_blank" v-if="drdr.status == 4" class="dropdown-item"  :href="verifyLink+drdr.id">Mark as verify</a>
                                    <a target="_blank" v-if="roleId.includes(3) && drdr.status == 3 && drdr.approver_id == userId" class="dropdown-item" :href="approvalLnik+drdr.id">Approve</a>
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
import JsonExcel from 'vue-json-excel';
Vue.use(CxltToastr);

export default {
    props: [
        'roleId',
        'userId'
        ],
    components:{
        Datepicker,
        VueContentPlaceholders,
        SpinnerLoading,
        downloadExcel: JsonExcel
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
            isLoading: false,
            status: '',
            default_drdrs: [],
            json_fields: {
                'ID': {
                    callback: (value) => {
                        return value.id;
                    }
                },
                'REQUEST TYPE': {
                     callback: (value) => {
                        return value.request_type == 1 ? 'Proposal (For proposed)' : (value.request_type == 2 ? 'Revision (For existing document)' : 'Cancellation');
                    }
                },
                'DATE REQUEST': {
                     callback: (value) => {
                        return value.date_request;
                    }
                },
                'EFFECTIVE DATE': {
                     callback: (value) => {
                        return value.effective_date;
                    }
                },
                'DOCUMENT TITLE': {
                     callback: (value) => {
                        return value.document_title;
                    }
                },
                'COMPANY': {
                     callback: (value) => {
                        return value.company.name;
                    }
                },
                'REV.': {
                    callback: (value) => {
                        return value.rev_number ? value.rev_number : '';
                    }
                },
                'REVIEWER': {
                    callback: (value) => {
                        return value.reviewer ? value.reviewer.name : '';
                    }
                },
                'REVIEWER STATUS': {
                    callback: (value) => {
                        return value.status == 2 ? 'NOT YET APPROVED' :  (value.status == 5 ? 'DISAPPROVED' : 'APPROVED');
                    }
                },
                'REVIEW DATE': {
                    callback: (value) => {
                        return value.reviewed_date;
                    }
                },
                'APPROVER': {
                    callback: (value) => {
                        return value.approver ?  value.approver.name : '';
                    }
                },
                'APPROVER STATUS': {
                    callback: (value) => {
                        return value.status == 3 ? 'NOT YET APPROVED' :  (value.status == 6 ? 'DISAPPROVED' : 'APPROVED');
                    }
                },
                'APPROVED DATE': {
                    callback: (value) => {
                        return value.approved_date;
                    }
                },
                'STATUS': {
                    callback: (value) => {
                        return value.status == 4 ? 'NOT YET VERIFIED' :  (value.status == 14 ? 'VERIFIED' : '');
                    }
                }
            }
        }
    },
    created(){
        this.fetchDrdrs();
    },
    methods:{
        moment,
        filterDrdrs(){
             switch(this.status) {
                case "4":
                case "14":
                    this.drdrs = this.default_drdrs.filter(drdr => {
                        return drdr.status == this.status;
                    });
                    break;
                default:
                    this.drdrs = this.default_drdrs;
            }
        },
        fetchDrdrs()
        {
            this.loading = true;
            axios.get('/admin/drdrs-all')
            .then(response => {
                this.drdrs = response.data;
                this.default_drdrs = response.data;
                this.loading = false;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
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
                       drdr.company.name.toLowerCase().includes(this.keywords.toLowerCase())
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
        },
        viewDrdrDetails()
        {
            return window.location.origin+'/admin/drdr-details/';
        },
    }
}
</script>

