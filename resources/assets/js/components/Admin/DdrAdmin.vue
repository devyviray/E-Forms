<template>
   <div>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header ">
                <h4 class="card-title">Document Distribution Request</h4>   
            </div>
            <div class="row mb-3">
                <div class="row">
                    <div class="form-group">
                        <datepicker v-model="startDate" placeholder="Select Start Date"></datepicker>
                        <span v-if="errors.startDate">{{ errors.startDate }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker v-model="endDate" placeholder="Select End Date"></datepicker>
                        <span v-if="errors.endDate">{{ errors.endDate }}</span>
                    </div>
                    <button @click="generateByDate" class="btn btn-primary">Generate</button>
                </div>
            </div>
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Company</th>
                    <th>Reason</th>
                    <th>Date request</th>
                    <th>Approver</th>
                    <th>Status</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="ddr in filteredQueues" v-bind:key="ddr.id">
                        <td @click="viewDdrDetails(ddr.id)">{{ ddr.id }}</td>
                        <td>{{ ddr.requester.name }}</td>
                        <td>{{ ddr.company.name }}</td>
                        <td>{{ ddr.reason_of_distribution }}</td>
                        <td>{{ moment(ddr.date_request).format('LL') }}</td>
                        <td>{{ ddr.approver.name }}</td>
                        <td>
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
                                    <a v-if="ddr.status == 4" @click="getDdr(ddr.id)"  class="dropdown-item" data-toggle="modal" data-target="#editDdrModal" href="javascript:void(0)">Edit Document</a>
                                    <a v-if="ddr.status == 4" @click="getDdrId(ddr.id)"  class="dropdown-item" data-toggle="modal" data-target="#distributedDdrModal" href="javascript:void(0)">Mark as distributed</a>
                                    <a class="dropdown-item" href="#">Move to trash</a>
                                    <a class="dropdown-item" href="#">Mark as archive</a>
                                    <a class="dropdown-item" href="#">Cancel document</a>
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
                <span>{{ ddrs.length }} Ddr form(s)</span>
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
                    <input type="text" class="form-control" placeholder="Id" v-model="selected_id">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="updateDdr(selected_id,ddrlists)" type="button" class="btn btn-primary">Save</button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="distributeDdr(selected_id)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>
   </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';

export default {
    components:{
      Datepicker,
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
        }
    },
    created(){
        this.fetchDdrs();
    },
    methods:{
        moment,
        fetchDdrs()
        {
            axios.get('/admin/ddrs-all')
            .then(response => {
                this.ddrs = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        viewDdrDetails(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/admin/ddr-details/${id}`;
            
        },
        generateByDate(){
            var startDate  =  this.startDate ? moment(this.startDate).format() : '';
            var endDate = this.endDate ? moment(this.endDate).format() : '';

            axios.post('/ddrs-generate',{
                'startDate': startDate,
                'endDate': endDate
            })
            .then(response => { 
                this.ddrs = response.data;
            })
            .catch(error => {
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
            axios.patch(`/admin/ddr/${id}`,{
                ddrlists: ddrlists
            })
            .then(response => {
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        distributeDdr(id){
            axios.post('/admin/ddr-distributed', { 
                'id': id
            })
            .then(response=> {
                $('#distributedDdrModal').modal('hide');
                this.selected_id = '';
                window.location.href = response.data.redirect;
            })
            .catch(error => { 
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
                return ddr.requester.name.toLowerCase().includes(this.keywords.toLowerCase())   
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
    }
}
</script>

