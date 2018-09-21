<template>
    <div>
        <content-placeholders v-if="loading">
            <content-placeholders-heading :img="true" />
            <content-placeholders-text :lines="3" />
        </content-placeholders>

        <div class="card-body table-full-width table-responsive" v-if="ncnSubmitteds.length">
            <div class="row mb-4 ml-2">
                <div class="col-md-12">
                    <label for="name">Search by Requester Name</label>
                    <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                </div>
            </div>
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
                    <tr v-for="ncnSubmitted in filteredQueues" v-bind:key="ncnSubmitted.id">
                        <td>{{ ncnSubmitted.id }}</td>
                        <td>{{ ncnSubmitted.requester.name }}</td>
                        <td>{{ ncnSubmitted.requester.position }}</td>
                        <!-- <td>{{ ncn.attached_files }}</td>
                        <td>{{ ncn.non_conformity_details }}</td> -->
                        <td>{{ ncnSubmitted.notification_number }}</td>
                        <td>{{ moment(ncnSubmitted.issuance_date).format('LL') }}</td>
                        <td>
                            <span style="color: red" v-if="ncnSubmitted.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ncnSubmitted.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td style="display:inline-grid">
                            <button  class="btn btn-primary btn-round btn-fill mb-1" @click="viewSubmittedNcn(ncnSubmitted.id)">View</button>
                            <!-- <button  class="btn btn-danger btn-round btn-fill mb-1" data-toggle="modal" :data-target="`#deleteModal-${ncnSubmitted.id}`">Delete</button> -->
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3"  v-if="ncnSubmitteds.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ ncnSubmitteds.length }} Ncn form(s)</span>
            </div>
        </div>

        <div class="card-body table-full-width table-responsive" v-if="!ncnSubmitteds.length && !loading">
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
                    <tr>
                        <td>No data available in the table</td>
                    </tr>    
                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
export default {
    components:{
        VueContentPlaceholders
    },
    data(){
        return{
            ncnSubmitteds: [],
            keywords: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }
    },
    created(){
        this.fetchNcnSubmitteds();
    },
    methods:{
        moment,
        viewSubmittedNcn(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/ncn-view/${id}`;
        },
        fetchNcnSubmitteds()
        {
            this.loading = true;
            axios.get('/ncns-submitted')
            .then(response => {
                this.ncnSubmitteds = response.data;
                this.loading = false;
            })
            .catch(error =>{
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
        filteredNcns(){
            let self = this;
            return self.ncnSubmitteds.filter(ncnSubmitted => {
                return ncnSubmitted.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
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

