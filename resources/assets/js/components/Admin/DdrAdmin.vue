<template>
    <div>
        <div class="col-md-12 col-lg-12">
            <div class="container-fluid bg-white rounded-3 shadow-sm p-4" style="border: 1px solid #e9ecef;">
                
                <div class="row mb-3">
                    <div class="col-8">
                        <label for="name">Search</label>
                        <input type="text" class="form-control form-control-sm rounded-2" placeholder="Search by ID, Requester or Approver" v-model="keywords" id="name">
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
                        <select v-model="status" class="form-control rounded-2" style="min-height: 40px;" @change="filterDdrs">
                            <option value="" selected>Status Filter</option>
                            <option value="4">Not Yet Distributed</option>
                            <option value="14">Distributed</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <button type="button" class="btn btn-success btn-sm" @click="exportDdrs">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="small">ID</th>
                            <th scope="col" class="small">Requester</th>
                            <th scope="col" class="small">Reason</th>
                            <th scope="col" class="small">Date Requested</th>
                            <th scope="col" class="small">Date Needed</th>
                            <th scope="col" class="small">Date Approved</th>
                            <th scope="col" class="small">Date Distributed</th>
                            <th scope="col" class="small">Approver</th>
                            <th scope="col" class="small">Status</th>
                            <th scope="col" class="small">Option</th>
                        </tr>
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
                        <tr v-if="!ddrs.length && !loading">
                            <td colspan="7" class="text-center">No data available in the table</td>
                        </tr>
                        <tr v-for="ddr in ddrs" v-bind:key="ddr.id">
                            <td class="small">{{ ddr.id }}</td>
                            <td class="small">{{ ddr.requester ? ddr.requester.name : '-' }}</td>
                            <td class="small">
                                <span v-if="ddr.reason_of_distribution == 1">Relevant external doc. (controlled copy)</span>
                                <span v-else-if="ddr.reason_of_distribution == 2">Customer request (uncontrolled copy)</span>
                                <span v-else-if="ddr.reason_of_distribution == 3">Others</span>
                                <span v-else>-</span>
                            </td>
                            <td class="small">{{ moment(ddr.date_request).format('LL') }}</td>
                            <td class="small">{{ moment(ddr.date_needed).format('LL') }}</td>
                            <td class="small">
                                <span v-if="ddr.approved_date">
                                    {{ moment(ddr.approved_date).format('LL') }}
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="small">
                                <span v-if="ddr.distributed_date">
                                    {{ moment(ddr.distributed_date).format('LL') }}
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="small">
                                {{ ddr.approver.name }}
                                <br>
                                <span :style="{ color: ddr.status === 2 ? 'red' : ddr.status === 6 ? 'red' : 'green' }">
                                    {{ ddr.status === 2 ? 'NOT YET APPROVED' : ddr.status === 6 ? 'DISAPPROVED' : 'APPROVED' }}
                                </span>
                            </td>
                            <td class="small">
                                <span v-if="ddr.status === 4" style="color: red">NOT YET DISTRIBUTED</span>
                                <span v-else-if="ddr.status === 14" style="color: green">DISTRIBUTED</span>
                                <span v-else>-</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Option
                                    </button>
                                    <div class="dropdown-menu">
                                        <a target="_blank" :href="viewDrdrDetails + ddr.id" class="dropdown-item">View</a>
                                        <a v-if="ddr.status === 4" @click="getDdr(ddr.id)" class="dropdown-item" data-toggle="modal" data-target="#editDdrModal" href="javascript:void(0)">Edit Document</a>
                                        <a v-if="ddr.status === 4" @click="getDdrId(ddr.id)" class="dropdown-item" data-toggle="modal" data-target="#distributedDdrModal" href="javascript:void(0)">Mark as distributed</a>
                                        <a v-if="roleId.includes(3) && ddr.status === 2 && ddr.approver_id === userId" target="_blank" :href="approvalLink + ddr.id" class="dropdown-item">Approve</a>
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
                        <span>{{ pagination.total || 0 }} DDR form(s)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit document Modal -->
        <div class="modal fade bd-example-modal-lg" id="editDdrModal" tabindex="-1" role="dialog" aria-labelledby="editDdrLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDdrLabel">Edit Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                        <div class="form-group">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="small">ID</th>
                                        <th scope="col" class="small">Document Title</th>
                                        <th scope="col" class="small">Control Code</th>
                                        <th scope="col" class="small">Rev No.</th>
                                        <th scope="col" class="small">Copy No.</th>
                                        <th scope="col" class="small">Copy Holder</th>
                                    </tr>
                                </thead>
                                <tbody v-if="ddrlists.length > 0">
                                    <tr v-for="(ddrlist, d) in ddrlists" v-bind:key="d">
                                        <td class="small">{{ d + 1 }}</td>
                                        <td class="small">
                                            <input type="text" class="form-control form-control-sm rounded-2" placeholder="Document title" v-model="ddrlist.document_title">
                                            <span v-if="errors.ddrlists" class="error">{{ 'sample error' }}</span>
                                        </td>
                                        <td class="small">
                                            <input type="text" class="form-control form-control-sm rounded-2" placeholder="Control Code" v-model="ddrlist.control_code">
                                        </td>
                                        <td class="small">
                                            <input type="text" class="form-control form-control-sm rounded-2" placeholder="Rev No." v-model="ddrlist.rev_number">
                                        </td>
                                        <td class="small">
                                            <input type="text" class="form-control form-control-sm rounded-2" placeholder="Copy No." v-model="ddrlist.copy_number">
                                        </td>
                                        <td class="small">
                                            <input type="text" class="form-control form-control-sm rounded-2" placeholder="Copy Holder" v-model="ddrlist.copy_holder">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                        <button @click="updateDdr(selected_id, ddrlists)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mark as distributed Modal -->
        <div  class="modal fade" id="distributedDdrModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Mark as distributed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                    <div class="form-group">
                        <span> Are you sure to mark this document as distributed?</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="distributeDdr(selected_id)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
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
            ddrs: [],
            startDate: '',
            endDate: '',
            keywords: '',
            errors: '',
            selected_id: '',
            ddrlists: [],
            currentPage: 1,
            itemsPerPage: 10,
            loading: false,
            isLoading: false,
            status: '',
            pagination: {},
        }
    },
    created(){
        this.fetchDdrs();
    },
    methods:{
        moment,
        filterDdrs(){
            this.fetchDdrs(1);
        },
        fetchDdrs(page = 1)
        {
            this.loading = true;
            axios.get('/admin/ddrs-all', {
                params: {
                    page: page,
                    search: this.keywords,
                    start_date: this.startDate,
                    end_date: this.endDate,
                    status: this.status
                }
            })
            .then(response => {
                this.ddrs = response.data.data;
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
        generateByDate(){
            this.fetchDdrs(1);
        },
        exportDdrs(){
            this.loading = true;
            axios.get('/admin/ddrs-export', {
                params: {
                    search: this.keywords,
                    start_date: this.startDate,
                    end_date: this.endDate,
                    status: this.status
                },
                responseType: 'blob'
            })
            .then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data], { type: 'text/csv;charset=utf-8;' }));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'ddr_export.csv');
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
        getDdrId(id)
        {
            this.selected_id = id;
        },
        getDdr(id){
            this.selected_id = id;

            axios.get(`/ddr-data/${ this.selected_id}`)
            .then(response => {
                this.ddrlists = response.data[0].ddr_lists;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });

        },
        updateDdr(id,ddrlists)
        {
            $('#editDdrModal').modal('hide');
            this.isLoading = true;
            axios.patch(`/admin/ddr/${id}`,{
                ddrlists: ddrlists
            })
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Document Succesfully Updated',
                    position: 'top right'
                });
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        distributeDdr(id){
            $('#distributedDdrModal').modal('hide');
            this.isLoading = true;
            axios.post('/admin/ddr-distributed', { 
                'id': id
            })
            .then(response=> {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DRDR Succesfully Distributed',
                    position: 'top right'
                });

                this.selected_id = '';
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false; 
                this.errors = response.data.errors;
            })
        },
        setPage(pageNumber) {
            this.fetchDdrs(pageNumber);
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
        approvalLnik(){
            return window.location.origin+'/ddr-approve/';
        },
        viewDrdrDetails(){
            return window.location.origin+'/admin/ddr-details/';
        },
    }
}
</script>

