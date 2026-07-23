<template>
    <div>
        <div class="col-md-12 col-lg-12">
            <div class="container-fluid bg-white rounded-3 shadow-sm p-4" style="border: 1px solid #e9ecef;">
                <div class="row mb-3">
                    <div class="col-8">
                        <label for="name">Search</label>
                        <input type="text" class="form-control form-control-sm rounded-2" placeholder="Search by ID, Notification, Requester" v-model="keywords" id="name">
                    </div>
                    <div class="col-4" style="margin-top: 26px">
                        <button @click="fetchNcns(1)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Search</button>
                    </div>
                    <div class="col-2 mt-2">
                        <label for="date1" class="mb-1">From Date</label>
                        <input type="date" class="form-control form-control-sm rounded-2" v-model="startDate" id="date1">
                        <span class="error" v-if="errors.startDate">{{ errors.startDate[0] }}</span>
                    </div>
                    <div class="col-2 mt-2">
                        <label for="date2" class="mb-1">To Date</label>
                        <input type="date" class="form-control form-control-sm rounded-2" v-model="endDate" id="date2">
                        <span class="error" v-if="errors.endDate">{{ errors.endDate[0] }}</span>
                    </div>
                    <div class="col-3 mt-1">
                        <label for="status">Filter by Status</label>
                        <select v-model="status" class="form-control rounded-2" style="min-height: 40px;" @change="filterNcns">
                            <option value="">All Status</option>
                            <option value="2">Not Yet Approved</option>
                            <option value="4">Approved</option>
                            <option value="6">Disapproved</option>
                        </select>
                    </div>
                    <div class="col-5 mt-1">
                        <label for="company">Filter by Company</label>
                        <select v-model="selectedCompany" class="form-control rounded-2" style="min-height: 40px;" @change="filterNcns">
                            <option value="">All Companies</option>
                            <option v-for="company in companies" :key="company.id" :value="company.id">
                                {{ company.name }} - {{ company.address }}
                            </option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <button type="button" class="btn btn-success btn-sm" @click="exportNcns">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="small">ID</th>
                            <th scope="col" class="small">Requester</th>
                            <th scope="col" class="small">Position</th>
                            <th scope="col" class="small">Notification</th>
                            <th scope="col" class="small">Date of Issuance</th>
                            <th scope="col" class="small">Approver</th>
                            <th scope="col" class="small">Status</th>
                            <th scope="col" class="small">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading" v-for="n in 5" :key="'loading-' + n">
                            <td colspan="8">
                                <content-placeholders>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="3" />
                                </content-placeholders>
                            </td>
                        </tr>
                        <tr v-if="!ncns.length && !loading">
                            <td colspan="8" class="text-center">No data available in the table</td>
                        </tr>
                        <tr v-for="ncn in ncns" v-bind:key="ncn.id">
                            <td class="small">{{ ncn.id }}</td>
                            <td class="small">{{ ncn.requester ? ncn.requester.name : '-' }}</td>
                            <td class="small">{{ ncn.requester ? ncn.requester.position : '-' }}</td>
                            <td class="small">{{ ncn.notification_number }}</td>
                            <td class="small">{{ moment(ncn.issuance_date).format('LL') }}</td>
                            <td class="small">{{ ncn.approver ? ncn.approver.name : '-' }}</td>
                            <td class="small">
                                <span style="color: red" v-if="ncn.status == 2">NOT YET APPROVED</span>
                                <span style="color: red" v-else-if="ncn.status == 6">DISAPPROVED</span>
                                <span style="color: green" v-else-if="ncn.status == 4">APPROVED</span>
                                <span v-else>-</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Option
                                    </button>
                                    <div class="dropdown-menu">
                                        <a target="_blank" :href="viewNcnDetails + ncn.id" class="dropdown-item">View</a>
                                        <a v-if="roleId.includes(3) && ncn.status == 2 && ncn.approver_id == userId" class="dropdown-item" :href="approvalLink + ncn.id">Approve</a>
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
                        <span>{{ pagination.total || 0 }} NCN form(s)</span>
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

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import SpinnerLoading from '../SpinnerLoading';

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
            ncns: [],
            companies: [],
            selectedCompany: '',
            startDate: '',
            endDate: '',
            keywords: '',
            currentPage: 1,
            itemsPerPage: 10,
            errors: {},
            loading: false,
            isLoading: false,
            status: '',
            pagination: {},
        }
    },
    created(){
        this.fetchCompanies();
        this.fetchNcns();
    },
    methods:{
        moment,
        filterNcns(){
            this.fetchNcns(1);
        },
        fetchNcns(page = 1)
        {
            this.loading = true;
            axios.get('/admin/ncns-all', {
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
                this.ncns = response.data.data;
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
        exportNcns(){
            this.loading = true;
            axios.get('/admin/ncns-export', {
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
                link.setAttribute('download', 'ncn_export.csv');
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
            this.fetchNcns(pageNumber);
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
        approvalLink(){
            return window.location.origin+'/ncn-approve/';
        },
        viewNcnDetails()
        {
            return window.location.origin+'/admin/ncn-details/';
        },
    }
}
</script>
