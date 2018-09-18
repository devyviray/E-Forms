<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header ">
                <h4 class="card-title">Customer Complaint Inforamtion Report</h4>
            </div>
            <div class="row mb-3">
                <div class="form-group" style="margin-left: 15px">
                    <datepicker v-model="startDate" placeholder="Select Start Date"></datepicker>
                    <span v-if="errors.startDate">{{ errors.startDate }}</span>
                </div>
                <div class="form-group">
                    <datepicker v-model="endDate" placeholder="Select End Date"></datepicker>
                    <span v-if="errors.endDate">{{ errors.endDate }}</span>
                    </div>
                <button @click="generateByDate" class="btn btn-primary">Generate</button>
            </div>
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
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
                    <tr v-if="loading">
                        <td colspan="7">
                            <content-placeholders>
                                <content-placeholders-heading :img="true" />
                                <content-placeholders-text :lines="3" />
                            </content-placeholders>
                        </td>
                    </tr>
                    <tr v-for="ccir in filteredQueues" v-bind:key="ccir.id">
                        <td>{{ ccir.id }}</td>
                        <td>{{ ccir.requester.name }}</td>
                        <td>{{ ccir.brand_name }}</td>
                        <td>
                            <span  v-if="ccir.nature_of_complaint == 1"> Wet/Lumpy </span>
                            <span  v-else-if="ccir.nature_of_complaint == 2"> Busted bag </span>
                            <span  v-else-if="ccir.nature_of_complaint == 3"> Under/Over weight </span>
                            <span  v-else-if="ccir.nature_of_complaint == 4"> Interested </span>
                            <span  v-else-if="ccir.nature_of_complaint == 5"> Dirty packaging </span>
                            <span  v-else> Others </span>
                        </td>
                        <td>{{ moment(ccir.delivery_date).format('LL') }} </td>
                        <td>
                            <span  v-if="ccir.status == 2"> PENDING </span>
                            <span  v-else-if="ccir.status == 9"> {{ ccir.car_number }}</span>
                            <span  v-else> INVALID </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a @click="viewCcirDetails(ccir.id)" class="dropdown-item" href="javascript:void(0)">View</a>
                                    <a v-if="ccir.status != 9 && ccir.status != 10" @click="getCcirId(ccir.id)" class="dropdown-item" data-toggle="modal" data-target="#validateCcirModal" href="javascript:void(0)">Validate</a>
                                    <a @click="getCcirId(ccir.id)" class="dropdown-item" data-toggle="modal" data-target="#trashCcirModal" href="javascript:void(0)">Move to trash</a>
                                    <a @click="getCcirId(ccir.id)" class="dropdown-item" data-toggle="modal" data-target="#archieveCcirModal" href="javascript:void(0)">Mark as archive</a>
                                    <a @click="getCcirId(ccir.id)" class="dropdown-item" data-toggle="modal" data-target="#cancelCcirModal" href="javascript:void(0)">Cancel document</a>
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
                <span>{{ filteredQueues.length }} Ccir form(s)</span>
            </div>
        </div>

         <!-- Validate Modal -->
        <div  class="modal fade" id="validateCcirModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Validate CCIR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                      <select v-model="selected_status" class="form-control form-control-lg" @change="selectedStatus">
                        <option value="" disabled selected>Option</option>
                        <option value="1">Approved</option>
                        <option value="2">Disapproved</option>
                    </select>
                    </div>
                    <div class="form-group" v-if="show">
                        <input type="text" class="form-control" placeholder="Car No." v-model="car_number">
                        <span v-if="errors.name">{{ errors.car_number }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="validateCcir(selected_id, selected_status , car_number)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Move to trash Modal -->
        <div  class="modal fade" id="trashCcirModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Move to trash</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" placeholder="Id" v-model="selected_id">
                    <div class="form-group" v-if="show">
                        <span> Are you sure to move this document to trash?</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="submitStatus(selected_status , car_number)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Move to trash Modal -->
        <div  class="modal fade" id="archieveCcirModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Move to archive</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" placeholder="Id" v-model="selected_id">
                    <div class="form-group" v-if="show">
                        <span> Are you sure to move this document to archive?</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="submitStatus(selected_status , car_number)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Move to trash Modal -->
        <div  class="modal fade" id="cancelCcirModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Cancel Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" placeholder="Id" v-model="selected_id">
                    <div class="form-group" v-if="show">
                        <span> Are you sure to cancel this document?</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="submitStatus(selected_status , car_number)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>
        
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import SpinnerLoading from '../SpinnerLoading';

export default {
    components:{
      Datepicker,
      VueContentPlaceholders,
      SpinnerLoading
    },
    data(){
        return{
            ccirs: [],
            keywords: '',
            errors: '',
            startDate: '',
            endDate: '',
            selected_id: '',
            selected_option: '', 
            selected_status: '',
            car_number: '',
            show: false,
            currentPage: 0,
            itemsPerPage: 10,
            loading: false,
            isLoading: false
        }
    },
    created(){
        this.fetchCcirs();
    },
    methods:{
        moment,
        fetchCcirs(){
            this.loading = true;
            axios.get('/admin/ccirs-all')
            .then(response => {
                this.ccirs = response.data;
                this.loading = false;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        selectedStatus()
        {
            this.selected_status == 1 ? this.show = true : this.show = false;
        },
        getCcirId(id)
        {
            this.selected_id = id;
        },
        validateCcir(id, status,car_number)
        {
            axios.post('/admin/ccir-validate', {
                'id': id,
                'status': status,
                'car_number': car_number  
            })
            .then(response => {
                $('#validateCcirModal').modal('hide');
                this.selected_id = ' ';
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        viewCcirDetails(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/admin/ccir-details/${id}`;
            
        },
        generateByDate(){

            this.isLoading = true;
            var startDate  =  this.startDate ? moment(this.startDate).format() : '';
            var endDate = this.endDate ? moment(this.endDate).format() : '';

            axios.post('/ccirs-generate',{
                'startDate': startDate,
                'endDate': endDate
            })
            .then(response => {
                this.isLoading = false;
                this.ccirs = response.data;
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
            return self.ccirs.filter(ccir => {
                return ccir.complainant.toLowerCase().includes(this.keywords.toLowerCase())
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

