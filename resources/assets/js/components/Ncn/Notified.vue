<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Position</th>
                    <th>Notification</th>
                    <th>Date of Issuance</th>
                    <th>Status</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="ncn in filteredQueues" v-bind:key="ncn.id">
                        <td>{{ ncn.id }}</td>
                        <td>{{ ncn.requester.name }}</td>
                        <td>{{ ncn.requester.position }}</td>
                        <td>{{ ncn.notification_number }}</td>
                        <td>{{ moment(ncn.issuance_date).format('LL') }}</td>
                        <td>
                            <span style="color: red" v-if="ncn.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ncn.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" @click="viewNcn(ncn.id)" href="javascript:void(0)">View</a>
                                    <a v-if="ncn.status == 4" @click="getNcnId(ncn.id)"  class="dropdown-item" data-toggle="modal" data-target="#validateNcnModal" href="javascript:void(0)">Validate</a>
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
                <span>{{ ncns.length }} Ncn form(s)</span>
            </div>
        </div>

        <!-- Validate Modal -->
        <div  class="modal fade" id="validateNcnModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Validate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="ncn.id">
                    <div class="form-group">
                        <label for="action">Immediate Action Taken</label>
                        <textarea v-model="ncn.action_taken" class="form-control" id="action"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="validateNcn(ncn)" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    data(){
        return{
            ncns: [],
            ncn:{
                id: ' ',
                action_taken: ' ' 
            },
            keywords: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchNcns();
    },
    methods:{
        moment,
        viewNcn(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/ncn-view/${id}`;
        },
        getNcnId(id)
        {
            this.ncn.id = id;
        },
        fetchNcns()
        {
            axios.get('/ncns-notified')
            .then(response => {
                this.ncns = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        validateNcn(ncn){
            axios.post('/notified/ncn-notified', { 
                'id': ncn.id,
                'action_taken': ncn.action_taken
            })
            .then(response=> {
                $('#validateNcnModal').modal('hide');
                this.selected_id = ' ';
                this.ncn.id = ' ';
                this.ncn.action_taken =  ' ';
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
        filteredNcns(){
            let self = this;
            return self.ncns.filter(ncn => {
                return ncn.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredNcns.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredNcns.slice(index, index + this.itemsPerPage);

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

