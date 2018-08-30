<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2"  @click="addCcirForm" >Add Ccir</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Ccir Forms</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="approve-tab" data-toggle="tab" href="#approve" role="tab" aria-controls="approve" aria-selected="false">Approved Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="submitted-tab" data-toggle="tab" href="#submitted" role="tab" aria-controls="submitted" aria-selected="false">Submitted Forms</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="card-body table-full-width table-responsive">
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
                                        <tr v-for="ccir in filteredQueues" v-bind:key="ccir.id">
                                            <td>{{ ccir.id }}</td>
                                            <td>{{ ccir.requester.name }}</td>
                                            <td>{{ ccir.brand_name }}</td>
                                            <td>{{ ccir.nature_of_complaint }}</td>
                                            <td>{{ ccir.delivery_date }}</td>
                                            <td>{{ ccir.status }}</td>
                                            <td>
                                                <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ccir.id}`">Edit</button>
                                                <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ccir.id}`">Delete</button>
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
                                    <span>{{ ccir.length }} Ccir form(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="approve" role="tabpanel" aria-labelledby="approve-tab">
                            <h1>sample 2</h1>
                        </div>
                        <div class="tab-pane fade" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">
                            <div class="card-body table-full-width table-responsive">
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
                                        <tr v-for="ccirSubmitted in ccirSubmitteds" v-bind:key="ccirSubmitted.id">
                                            <td>{{ ccirSubmitted.id }}</td>
                                            <td>{{ ccirSubmitted.requester.name }}</td>
                                            <td>{{ ccirSubmitted.brand_name }}</td>
                                            <td>{{ ccirSubmitted.nature_of_complaint }}</td>
                                            <td>{{ ccirSubmitted.delivery_date }}</td>
                                            <td>{{ ccirSubmitted.status }}</td>
                                            <td>
                                                <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ccirSubmitted.id}`">Edit</button>
                                                <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ccirSubmitted.id}`">Delete</button>
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
                                    <span>{{ ccir.length }} Ccir form(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <!-- Update Modal -->
        <div v-for="(ccir,c) in filteredQueues" :key="'list-' +  c" class="modal fade" :id="`editModal-${ccir.id}`" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit ccir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Complainant" v-model="ccir.complainant">
                        <span v-if="errors.complainant">{{ errors.complainant}}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Commodity" v-model="ccir.commodity">
                        <span v-if="errors.commodity">{{ errors.commodity }}</span>
                    </div>
                    <div class="form-group">
                        <input placeholder="Commodity" v-model="ccir.product_control_number">
                        <span v-if="errors.product_control_number">{{ errors.product_control_number }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker placeholder="Select Date" v-model="ccir.delivery_date"></datepicker>
                        <span v-if="errors.delivery_date">{{ errors.delivery_date }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="ccir.reason" class="form-control form-control-lg">
                           <option value="" disabled selected>Nature of Complaint</option>
                           <option value="1">Wet/Lumpy</option>
                           <option value="2">Busted bag</option>
                           <option value="3">Under/Over weight</option>
                           <option value="3">Interested</option>
                           <option value="3">Dirty packaging</option>
                           <option value="3">Others</option>
                       </select>
                        <span v-if="errors.type">{{ errors.type }}</span>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Other Details"></textarea>
                        <span v-if="errors.other_details">{{ errors.other_details }}</span>
                    </div>
                     <div class="form-group">
                        <datepicker placeholder="Select Date" v-model="ccir.delivery_date"></datepicker>
                        <span v-if="errors.delivery_date">{{ errors.delivery_date }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Affected Quantity" v-model="ccir.affected_quantity">
                        <span v-if="errors.affected_quantity">{{ errors.affected_quantity }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Quantity of Sample" v-model="ccir.quality_of_sample">
                        <span v-if="errors.affected_quantity">{{ errors.affected_quantity }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker placeholder="Select Date" v-model="ccir.return_date"></datepicker>
                        <span v-if="errors.return_date">{{ errors.return_date }}</span>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="file">
                    </div>   
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="editDdr(ddr,company,approver,department)" type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div  v-for="(ccir, c) in filteredQueues" :key="c" class="modal fade" :id="`deleteModal-${ccir.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete CCIR</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ccir.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this CCIR?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteCcir(ccir.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    data(){
        return{
            ccirs: [],
            ccir: { 
                id: '',
                issuer_id: '',
                company_id: '',
                complainant: '',
                commodity: '',
                brand_name: '',
                product_control_number: '',
                request_date: '',
                cancel_date: '',
                delivery_date: '',
                returned_date: '',
                nature_of_complaint: '',
                other_details: '',
                affected_quantity: '', 
                quality_of_sample: '',
                attached_file: '',
                verifier_id: '',
                status: '',
                remarks: ''

            },
            ccirSubmitteds: [],
            ccirSubmitted: { 
                id: '',
                issuer_id: '',
                company_id: '',
                complainant: '',
                commodity: '',
                brand_name: '',
                product_control_number: '',
                request_date: '',
                cancel_date: '',
                delivery_date: '',
                returned_date: '',
                nature_of_complaint: '',
                other_details: '',
                affected_quantity: '', 
                quality_of_sample: '',
                attached_file: '',
                verifier_id: '',
                status: '',
                remarks: ''

            },
            keywords: '',
            errors: '',
            currentPage: 0,
           itemsPerPage: 10,
        }
    },
    created(){
        this.fetchCcirs();
        this.fetchCcirSubmitted();
    },
    methods: {
        fetchCcirs(){
            axios.get('/ccirs')
            .then(response => {
                this.ccirs = response.data; 
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        fetchCcirSubmitted(){
            axios.get('/ccirs-submitted')
            .then(response => {
                this.ccirSubmitteds = response.data; 
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        addCcirForm(){
            window.location.href = '/add-ccir';
        },
        editCcir(ccir){
            axios.post(`/ccir/${ccir.id}`,{

            })
            .then(response => {
                this.fetchCcirs();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteCcir(id){
            axios.delete(`/ccir/${id}`)
            .then(response => {
                this.fetchCcirs();
            })
            .catch(error => {
               this.errors = error.response.data.errors; 
            });
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
    },
     components: {
         Datepicker
    }
}
</script>

