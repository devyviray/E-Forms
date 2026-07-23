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
                        <input type="text" class="form-control form-control-sm rounded-2" placeholder="Search by ID, Document Title" v-model="keywords" id="name">
                    </div> 
                    <div class="col-4" style="margin-top: 26px">
                        <button @click="fetchDrdrs" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Search</button>
                    </div>
                    <div class="col-3 mt-2">
                        <label for="date1" class="mb-1">From Date</label>    
                        <input type="date" class="form-control form-control-sm rounded-2" v-model="startDate" id="date1">
                        <span class="error" v-if="errors.startDate">{{ errors.startDate[0] }}</span>
                    </div>
                    <div class="col-3 mt-2">
                        <label for="date2" class="mb-1">To Date</label>
                        <input type="date" class="form-control form-control-sm rounded-2" v-model="endDate" id="date2">
                        <span class="error" v-if="errors.endDate">{{ errors.endDate[0] }}</span>
                    </div>
                    <div class="col-3 mt-1">
                        <label for="status">Filter by Status</label>
                        <select v-model="status" class="form-control rounded-2" style="min-height: 40px;" @change="filterDrdrs">
                            <option value="" selected>Status Filter</option>
                            <option value="4">Not Yet Verified</option>
                            <option value="14">Verified</option>
                        </select>
                    </div>
                    <div class="col-3 mt-1">
                        <label for="company">Filter by Company</label>
                        <select v-model="selectedCompany" class="form-control rounded-2" style="min-height: 40px;" @change="filterDrdrs">
                            <option value="">All Companies</option>
                            <option v-for="company in companies" :key="company.id" :value="company.id">
                                {{ company.name }} - {{ company.address }}
                            </option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <button type="button" class="btn btn-success btn-sm" @click="exportDrdrs">
                        <i class="fas fa-download"></i> Export
                    </button>
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
                        <!-- Loading state: Show multiple placeholder rows -->
                        <tr v-if="loading" v-for="n in 5" :key="'loading-' + n">
                            <td colspan="8">
                                <content-placeholders>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="3" />
                                </content-placeholders>
                            </td>
                        </tr>

                        <!-- Empty state: No data -->
                        <tr v-if="!drdrs.length && !loading">
                            <td colspan="8" class="text-center">No data available in the table</td>
                        </tr>

                        <!-- Data rows -->
                        <tr v-for="drdr in drdrs" v-bind:key="drdr.id">
                            <td class="small">{{ drdr.id }}</td>
                            <td class="small">{{ drdr.document_title }}</td>
                            <td class="small">{{ drdr.company.name }} - {{ drdr.company.address }}</td>
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
                        <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)">Previous</button>
                        <span class="text-dark">Page {{ currentPage }} of {{ totalPages }}</span>
                        <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)">Next</button>
                    </div>
                    <div class="col-6 text-right">
                        <span>{{ pagination.total || 0 }} DRDR form(s)</span>
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
        SpinnerLoading,
    },
    data(){
        return{
            drdrs: [],
            companies: [],
            selectedCompany: '',
            startDate: '',
            endDate: '',
            selected_id: '',
            keywords: '',
            errors: '',
            currentPage: 1,
            itemsPerPage: 10,
            loading: false,
            isLoading: false,
            status: '',
            pagination: {},
        }
    },
    created(){
        this.fetchCompanies();
        this.fetchDrdrs();
    },
    methods:{
        moment,
        filterDrdrs(){
            this.fetchDrdrs(1);
        },
        fetchDrdrs(page = 1)
        {
            this.loading = true;
            axios.get('/admin/drdrs-all', {
                params: {
                    page: page,
                    search: this.keywords,
                    start_date: this.startDate,
                    end_date: this.endDate,
                    status: this.status,
                    company: this.selectedCompany
                }
            })
            .then(response => {
                this.drdrs = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    total: response.data.total,
                    per_page: response.data.per_page,
                    from: response.data.from,
                    to: response.data.to
                };
                this.currentPage = response.data.current_page;
                this.loading = false;
            })
            .catch(error =>{
                this.loading = false;
                this.errors = error.response && error.response.data.errors ? error.response.data.errors : {};
            });
        },
        fetchCompanies(){
            this.loading = true;
            axios.get('/companies')
            .then(response => {
                this.companies = response.data;
                this.loading = false;
            })
            .catch(error =>{
                this.loading = false;
                this.errors = error.response && error.response.data.errors ? error.response.data.errors : {};
            });
        },
        exportDrdrs(){
            this.loading = true;
            axios.get('/admin/drdrs-export', {
                params: {
                    search: this.keywords,
                    start_date: this.startDate,
                    end_date: this.endDate,
                    status: this.status,
                    company: this.selectedCompany
                },
                responseType: 'blob'
            })
            .then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data], { type: 'text/csv;charset=utf-8;' }));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'drdr_export.csv');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                this.errors = error.response && error.response.data.errors ? error.response.data.errors : {};
            })
            .then(() => {
                this.loading = false;
            });
        },
        setPage(pageNumber) {
            this.fetchDrdrs(pageNumber);
        },

        resetStartRow() {
            this.currentPage = 1;
        },

        showPreviousLink() {
            return this.currentPage > 1;
        },

        showNextLink() {
            return this.currentPage < this.totalPages;
        }
    },
    computed: {
        totalPages() {
            return this.pagination.last_page || 0;
        },
        verifyLink(){
            return window.location.origin+'/admin/drdr-verify/';
        },
        approvalLink(){
            return window.location.origin+'/drdr-approve/';
        },
        viewDrdrDetails()
        {
            return window.location.origin+'/admin/drdr-details/';
        },
    }
}
</script>
