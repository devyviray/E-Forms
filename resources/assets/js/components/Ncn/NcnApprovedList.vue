<template>
    <div>

        <content-placeholders v-if="loading">
            <content-placeholders-heading :img="true" />
            <content-placeholders-text :lines="3" />
        </content-placeholders>

        <div class="card-body table-full-width table-responsive" v-if="ncnApprovedForms.length">
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
                    <tr v-for="ncnApprovedForm in filteredQueues" v-bind:key="ncnApprovedForm.id">
                        <td>{{ ncnApprovedForm.id }}</td>
                        <td>{{ ncnApprovedForm.requester.name }}</td>
                        <td>{{ ncnApprovedForm.requester.position }}</td>
                        <!-- <td>{{ ncn.attached_files }}</td>
                        <td>{{ ncn.non_conformity_details }}</td> -->
                        <td>{{ ncnApprovedForm.notification_number }}</td>
                        <td>{{ moment(ncnApprovedForm.issuance_date).format('LL') }}</td>
                        <td>
                            <span style="color: red" v-if="ncnApprovedForm.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ncnApprovedForm.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>
                            <button  class="btn btn-warning btn-round btn-fill" @click="viewApprovedNcn(ncnApprovedForm.id)" >View</button>
                            <!-- <button  class="btn btn-danger btn-round btn-fill" data-toggle="modal" :data-target="`#deleteModal-${ncnApprovedForm.id}`">Delete</button> -->
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3" v-if="ncnApprovedForms.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ ncnApprovedForms.length }} NCN form(s)</span>
            </div>
        </div>


        <div class="card-body table-full-width table-responsive" v-if="!ncnApprovedForms.length && !loading">
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
                    <tr>
                        <td>No data availbale in the table</td>
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
            ncnApprovedForms: [],
            keywords: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }
    },
    created(){
        this.fetchNcnApprovedForms();
    },
    methods:{
        moment,
        viewApprovedNcn(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/ncn-view/${id}`;
        },
        fetchNcnApprovedForms()
        {
            this.loading = true;
            axios.get('/ncns-approved-forms')
            .then(response => {
                this.ncnApprovedForms = response.data;
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
            return self.ncnApprovedForms.filter(ncnApprovedForm => {
                return ncnApprovedForm.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
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

