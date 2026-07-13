<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12 col-lg-12">
            <div class="container-fluid bg-white rounded-3 shadow-sm p-4" style="border: 1px solid #e9ecef;">
                
                <div class="row mb-3">
                    <div class="col-8">
                        <label for="name">Search</label>
                        <input type="text" class="form-control form-control-sm rounded-2" placeholder="Search by Customer Name, Commodity, Requestor" v-model="keywords" id="name">
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
                        <label for="validity">Filter by Validity</label>
                        <select v-model="validity_status" class="form-control rounded-2" style="min-height: 40px;" @change="filterCcirs">
                            <option value="" selected>Validity Filter</option>
                            <option value="2">Pending</option>
                            <option value="9">Valid</option>
                            <option value="0">Invalid</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <download-excel :data="filteredCcirs" :fields="json_fields" worksheet="CCIR Forms" name="ccir_export.xls" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Export
                    </download-excel>
                </div>
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="small">ID</th>
                            <th scope="col" class="small">Customer</th>
                            <th scope="col" class="small">Company</th>
                            <th scope="col" class="small">Commodity</th>
                            <th scope="col" class="small">Nature of Complaint</th>
                            <th scope="col" class="small">Date of Issuance</th>
                            <th scope="col" class="small">Validity</th>
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
                        <tr v-if="!filteredQueues.length && !loading">
                            <td colspan="8" class="text-center">No data available in the table</td>
                        </tr>
                        <tr v-for="ccir in filteredQueues" v-bind:key="ccir.id">
                            <td class="small">{{ ccir.id }}</td>
                            <td class="small">{{ ccir.complainant }}</td>
                            <td class="small">{{ ccir.company.name + ' - ' + ccir.company.address }}</td>
                            <td class="small">{{ ccir.commodity }}</td>
                            <td class="small">
                                <span v-if="ccir.nature_of_complaint == 1">Wet/Lumpy</span>
                                <span v-else-if="ccir.nature_of_complaint == 2">Busted bag</span>
                                <span v-else-if="ccir.nature_of_complaint == 3">Under/Over weight</span>
                                <span v-else-if="ccir.nature_of_complaint == 4">Infestation</span>
                                <span v-else-if="ccir.nature_of_complaint == 5">Dirty packaging</span>
                                <span v-else>{{ ccir.others }}</span>
                            </td>
                            <td class="small">{{ moment(ccir.date_request).format('LL') }}</td>
                            <td class="small">
                                <span v-if="ccir.status == 2" style="color: orange">PENDING</span>
                                <span v-else-if="ccir.status == 9" style="color: green">{{ ccir.car_number }}</span>
                                <span v-else style="color: red">INVALID</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Option
                                    </button>
                                    <div class="dropdown-menu">
                                        <a target="_blank" :href="viewCcirDetails + ccir.id" class="dropdown-item">View</a>
                                        <a v-if="ccir.status != 9 && ccir.status != 10" @click="getCcirId(ccir.id)" class="dropdown-item" data-toggle="modal" data-target="#validateCcirModal" href="javascript:void(0)">Validate</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row mb-3">
                    <div class="col-6">
                        <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)">Previous</button>
                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                        <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)">Next</button>
                    </div>
                    <div class="col-6 text-right">
                        <span>{{ filteredQueues.length }} CCIR form(s)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validate Modal -->
        <div class="modal fade" id="validateCcirModal" tabindex="-1" role="dialog" aria-labelledby="validateCcirLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="validateCcirLabel">Validate CCIR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectedStatus">Status</label>
                            <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                            <select v-model="selected_status" class="form-control rounded-2" style="min-height: 40px;" @change="selectedStatus" id="selectedStatus">
                                <option value="" disabled selected>Select status</option>
                                <option value="1">Valid</option>
                                <option value="2">Invalid</option>
                            </select>
                            <span class="error" v-if="errors.status">{{ errors.status[0] }}</span>
                        </div>
                        <div class="form-group" v-if="show">
                            <label for="car_number">Car No.</label>
                            <input type="text" class="form-control form-control-sm rounded-2" placeholder="Car No." v-model="car_number" id="car_number">
                            <span class="error" v-if="errors.car_number">{{ errors.car_number[0] }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                        <button @click="validateCcir(selected_id, selected_status, car_number)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Move to Trash Modal -->
        <div class="modal fade" id="trashCcirModal" tabindex="-1" role="dialog" aria-labelledby="trashCcirLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="trashCcirLabel">Move to Trash</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                        <div class="form-group">
                            <span>Are you sure you want to move this document to trash?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                        <button @click="submitStatus(selected_status, car_number)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Move to Archive Modal -->
        <div class="modal fade" id="archieveCcirModal" tabindex="-1" role="dialog" aria-labelledby="archiveCcirLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="archiveCcirLabel">Move to Archive</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                        <div class="form-group">
                            <span>Are you sure you want to move this document to archive?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                        <button @click="submitStatus(selected_status, car_number)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Document Modal -->
        <div class="modal fade" id="cancelCcirModal" tabindex="-1" role="dialog" aria-labelledby="cancelCcirLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelCcirLabel">Cancel Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                        <div class="form-group">
                            <span>Are you sure you want to cancel this document?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                        <button @click="submitStatus(selected_status, car_number)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style="background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
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
import JsonExcel from 'vue-json-excel';
import VueContentPlaceholders from 'vue-content-placeholders';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

export default {
    components:{
      Datepicker,
      VueContentPlaceholders,
      SpinnerLoading,
      downloadExcel: JsonExcel,
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
            isLoading: false,
            validity_status: '',
            default_ccirs: [],
            json_fields: {
                'ID': {
                    callback: (value) => {
                        return value.id;
                    }
                },
                'Customer': {
                    callback: (value) => {
                        return value.complainant;
                    }
                },
                'Company': {
                    callback: (value) => {
                        return value.company.name +' - '+value.company.address;
                    }
                },
                'Commodity': {
                    callback: (value) => {
                        return value.commodity;
                    }
                },
                'Nature of Complaint': {
                    callback: (value) => {
                        return value.nature_of_complaint == 1 ? 'Wet/Lumpy' : value.nature_of_complaint == 2 ? 'Busted bag' : value.nature_of_complaint == 3 ? 'Under/Over weight' : value.nature_of_complaint == 4 ? 'Infestation' : value.nature_of_complaint == 5 ? 'Dirty packaging' : value.others;
                    }
                },
                'Date of Issuance': {
                    callback: (value) => {
                        return moment(value.date_request).format('LL');
                    }
                },
                'Validity': {
                    callback: (value) => {
                        return value.status == 2 ? 'PENDING' : value.status == 9 ? value.car_number : 'INVALID';
                    }
                },
            }
        }
    },
    created(){
        this.fetchCcirs();
    },
    methods:{
        moment,
        filterCcirs(){
            switch(this.validity_status) {
                case "0":
                    this.ccirs = this.default_ccirs.filter(ccir => {
                        return !['2','9'].includes(ccir.status);
                    });
                    break;
                case "2":
                case "9":
                    this.ccirs = this.default_ccirs.filter(ccir => {
                        return ccir.status == this.validity_status;
                    });
                    break;
                default:
                    this.ccirs = this.default_ccirs;
            }
        },
        fetchCcirs(){
            this.loading = true;
            axios.get('/admin/ccirs-all')
            .then(response => {
                this.ccirs = response.data;
                this.default_ccirs = response.data;
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
                var message = status == 1 ? 'Valid' : 'Invalid';
                this.$toast.success({
                    title:'SUCCESS',
                    message:'CCIR Succesfully marked as '+message,
                    position: 'top right'
                });

                this.selected_id = ' ';
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
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
                return ccir.requester.name.toLowerCase().includes(this.keywords.toLowerCase()) ||
                       ccir.commodity.toLowerCase().includes(this.keywords.toLowerCase()) ||
                       ccir.complainant.toLowerCase().includes(this.keywords.toLowerCase()) 
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
        viewCcirDetails(){
            return window.location.origin+`/admin/ccir-details/`;
        },
    }
}
</script>

