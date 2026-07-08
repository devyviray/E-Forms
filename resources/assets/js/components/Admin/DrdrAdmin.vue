<template>
    <div>
        <div class="col-md-12 col-lg-12">
            <div class="container-fluid bg-white rounded-3 shadow-sm p-4" style="border: 1px solid #e9ecef;">
                <!-- <h5 class="text-xs font-weight-bold gradient-text text-uppercase text-shadow-hover text-decoration-none mb-4">
                    Filters
                </h5> -->
                
                <div class="row mb-3">
                    <div class="col-8">
                        <label for="name">Search</label>
                        <input type="text" class="form-control form-control-sm rounded-2" placeholder="Search by Document Title, Company" v-model="keywords" id="name">
                    </div> 
                    <div class="col-4" style="margin-top: 26px">
                        <button @click="generateByDate" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Search</button>
                    </div>
                    <div class="col-4 mt-2">
                        <label for="date1" class="mb-1">From Date</label>    
                        <input type="date" class="form-control form-control-sm rounded-2" v-model="startDate" id="date1">
                        <span class="error" v-if="errors.startDate">{{ errors.startDate[0] }}</span>
                    </div>
                    <div class="col-4 mt-2">
                        <label for="date2" class="mb-1">To Date</label>
                        <input type="date" class="form-control form-control-sm rounded-2" v-model="endDate" id="date2">
                        <span class="error" v-if="errors.endDate">{{ errors.endDate[0] }}</span>
                    </div>
                    <div class="col-4 mt-1">
                        <label for="status">Filter by Status</label>
                        <select v-model="status" class="form-control rounded-2" style="min-height: 40px;" @change="filterDrdrs">
                            <option value="" selected>Status Filter</option>
                            <option value="4">Not Yet Verified</option>
                            <option value="14">Verified</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <download-excel :data="filteredQueues" :fields="json_fields" worksheet="DRDR Forms" name="drdrs_export.xls" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Export
                    </download-excel>
                </div>
                <table class="table align-items-center table-flush">
                    <thead class = "thead-light">
                        <tr>
                            <th scope="col" class="small">ID</th>
                            <th scope="col" class="small">Document Title</th>
                            <th scope="col" class="small">Company</th>
                            <th scope="col" class="small">Rev.</th>
                            <th scope="col" class="small">Reviewer</th>
                            <th scope="col" class="small">Approver</th>
                            <th scope="col" class="small">Status</th>
                            <th scope="col" class="small">Option</th>
                        </tr>
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
                            <td class="small">{{ drdr.id }}</td>
                            <td class="small">{{ drdr.document_title }}</td>
                            <td class="small">{{ drdr.company.name }}</td>
                            <td class="small">{{ drdr.rev_number !== null ? drdr.rev_number : '-' }}</td>
                            <td class="small">
                                {{ drdr.reviewer.name }}
                                <br>
                                <span :style="{ color: drdr.status === 2 ? 'red' : drdr.status === 5 ? 'red' : 'green' }">
                                    {{ drdr.status === 2 ? 'NOT YET APPROVED' : drdr.status === 5 ? 'DISAPPROVED' : 'APPROVED' }}
                                </span>
                            </td>
                            <td class="small">
                                <template v-if="drdr.approver">
                                    {{ drdr.approver.name }}
                                    <br>
                                    <span :style="{ color: drdr.status === 3 ? 'red' : drdr.status === 6 ? 'red' : 'green' }">
                                        {{ drdr.status === 3 ? 'NOT YET APPROVED' : drdr.status === 6 ? 'DISAPPROVED' : 'APPROVED' }}
                                    </span>
                                </template>
                                <template v-else>-</template>
                            </td>
                            <td class="small">
                                <span v-if="drdr.status === 4" style="color: red">NOT YET VERIFIED</span>
                                <span v-else-if="drdr.status === 14" style="color: green">VERIFIED</span>
                                <span v-else>-</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Option
                                    </button>
                                    <div class="dropdown-menu">
                                        <a target="_blank" :href="viewDrdrDetails + drdr.id" class="dropdown-item">View</a>
                                        <a v-if="drdr.status === 4" target="_blank" :href="verifyLink + drdr.id" class="dropdown-item">Mark as verify</a>
                                        <a v-if="roleId.includes(3) && drdr.status === 3 && drdr.approver_id === userId" target="_blank" :href="approvalLink + drdr.id" class="dropdown-item">Approve</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

