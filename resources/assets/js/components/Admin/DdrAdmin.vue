<template>
   <div>
       <spinner-loading v-if="isLoading"></spinner-loading>

        <div class="card-body table-full-width table-responsive">
            <div class="card-header mb-3">
                <h4 class="card-title">Document Distribution Request</h4>   
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
                    <button @click="generateByDate" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Generate</button>
                </div>
            </div>
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Reason</th>
                    <th>Date Requested</th>
                    <th>Approver Status</th>
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
                    <tr v-if="!ddrs.length && !loading">
                        <td>No data available in the table</td>
                    </tr>
                    <tr v-for="ddr in filteredQueues" v-bind:key="ddr.id">
                        <td>{{ ddr.id }}</td>
                        <td>{{ ddr.requester.name }}</td>
                       	<td v-if="ddr.reason_of_distribution == 1"> Relevant external doc. (controlled copy) </td>
						<td v-if="ddr.reason_of_distribution == 2"> Customer request (uncontrolled copy) </td>
						<td v-if="ddr.reason_of_distribution == 3"> Others: </td>
                        <td>{{ moment(ddr.date_request).format('LL') }}</td>
                        <td>{{ ddr.approver.name }}<br>
                            <span style="color: red" v-if="ddr.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ddr.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a target="_blank" class="dropdown-item" :href="viewDrdrDetails+ddr.id">View</a>
                                    <a v-if="ddr.status == 4" @click="getDdr(ddr.id)"  class="dropdown-item" data-toggle="modal" data-target="#editDdrModal" href="javascript:void(0)">Edit Document</a>
                                    <a v-if="ddr.status == 4" @click="getDdrId(ddr.id)"  class="dropdown-item" data-toggle="modal" data-target="#distributedDdrModal" href="javascript:void(0)">Mark as distributed</a>
                                    <a  v-if="roleId.includes(3) && ddr.status == 2 && ddr.approver_id == userId" class="dropdown-item" :href="approvalLnik+ddr.id">Approve</a>
                                </div>
                            </div>
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3" v-if="ddrs.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ filteredQueues.length }} DDR form(s)</span>
            </div>
        </div>


        <!-- Edit document Modal -->
        <div  class="modal fade bd-example-modal-lg" id="editDdrModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Edit document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                    <div class="form-group">
                         <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Document Title</th>
                                <th>Control Code</th>
                                <th>Rev No.</th>
                                <th>Copy No.</th>
                                <th>Copy Holder</th>
                            </thead>
                            <tbody>
                                <tr v-if="ddrs.length" v-for="(ddrlist, d) in ddrlists" v-bind:key="d">
                                    <td>{{ d + 1 }}</td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Document title" v-model="ddrlist.document_title">
                                         <span v-if="errors.ddrlists">{{ 'sample error' }}</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Control Code" v-model="ddrlist.control_code">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Rev No." v-model="ddrlist.rev_number">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Copy No." v-model="ddrlist.copy_number">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Copy Holder" v-model="ddrlist.copy_holder">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="updateDdr(selected_id,ddrlists)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
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
      SpinnerLoading
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
            currentPage: 0,
            itemsPerPage: 10,
            loading: false,
            isLoading: false
        }
    },
    created(){
        this.fetchDdrs();
    },
    methods:{
        moment,
        fetchDdrs()
        {
            this.loading = true;
            axios.get('/admin/ddrs-all')
            .then(response => {
                this.ddrs = response.data;
                this.loading = false;
                console.log(this.ddrs.filter(item => item.requester == null));
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        generateByDate(){
            this.isLoading = true;
            var startDate  =  this.startDate ? moment(this.startDate).format() : '';
            var endDate = this.endDate ? moment(this.endDate).format() : '';

            axios.post('/ddrs-generate',{
                'startDate': startDate,
                'endDate': endDate
            })
            .then(response => {
                this.isLoading = false;
                this.ddrs = response.data;
            })
            .catch(error => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            })
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
        filteredDdrs(){
            let self = this;
            return self.ddrs.filter(ddr => {
                return ddr.requester.name.toLowerCase().includes(this.keywords.toLowerCase())  ||
                       ddr.approver.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredDdrs.length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredDdrs.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return queues_array;
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

