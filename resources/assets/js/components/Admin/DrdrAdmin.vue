<template>
      <div>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header ">
                <h4 class="card-title">Document Review & Document Request</h4>   
            </div>
            <div class="row mb-3">
                <div class="row">
                    <datepicker v-model="startDate" placeholder="Select Start Date"></datepicker>
                    <datepicker v-model="endDate" placeholder="Select End Date"></datepicker>
                    <button @click="generateByDate" class="btn btn-primary">Generate</button>
                </div>
            </div>
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Document title</th>
                    <th>Company</th>
                    <th>Rev.</th>
                    <th>Reviewer</th>
                    <th>Approver</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="drdr in filteredQueues" v-bind:key="drdr.id">
                        <td @click="viewDrdrDetails(drdr.id)">{{ drdr.id }}</td>
                        <td>{{ drdr.document_title }}</td>
                        <td>{{ drdr.company.name  }}</td>
                        <td>{{ drdr.rev_number }}</td>
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
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a v-if="drdr.status == 4" @click="getDrdrId(drdr.id)"  class="dropdown-item" data-toggle="modal" data-target="#distributedDrdrModal" href="javascript:void(0)">Mark as verify</a>
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
                <span>{{ drdrs.length }} Drdr form(s)</span>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="distributeDrdr(selected_id)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>

    </div>   
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {
    components:{
      Datepicker  
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
        }
    },
    watch: {
        drdrs: function (val, oldVal) {
       
        }
    },
    created(){
        this.fetchDrdrs();
    },
    methods:{
        fetchDrdrs()
        {
            axios.get('/admin/drdrs-all')
            .then(response => {
                this.drdrs = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        viewDrdrDetails(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/admin/drdr-details/${id}`;
            
        },
        generateByDate(){
            axios.get('/drdrs-generate/'+ this.startDate + '/' + this.endDate)
            .then(response => { 
                this.drdrs = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        getDrdrId(id)
        {
            this.selected_id = id;
        },
        distributeDrdr(id){
            axios.post('/admin/drdr-distributed', { 
                'id': id
            })
            .then(response=> {
                $('#distributedDrdrModal').modal('hide');
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
    computed:
    {
        filteredDrdrs(){
            let self = this;
            return self.drdrs.filter(drdr => {
                return drdr.document_title.toLowerCase().includes(this.keywords.toLowerCase())
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
        }
    }
}
</script>

