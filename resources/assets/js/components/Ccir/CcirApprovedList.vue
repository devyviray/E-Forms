<template>
    <div>
        <content-placeholders v-if="loading">
            <content-placeholders-heading :img="true" />
            <content-placeholders-text :lines="3" />
        </content-placeholders>

        <div class="card-body table-full-width table-responsive" v-if="ccirApprovedForms.length">
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
                    <tr v-for="ccirApprovedForm in filteredQueues" v-bind:key="ccirApprovedForm.id">
                        <td>{{ ccirApprovedForm.id }}</td>
                        <td>{{ ccirApprovedForm.requester.name }}</td>
                        <td>{{ ccirApprovedForm.brand_name }}</td>
                        <td>{{ ccirApprovedForm.nature_of_complaint }}</td>
                        <td>{{ ccirApprovedForm.delivery_date }}</td>
                        <td>{{ ccirApprovedForm.status }}</td>
                        <td>
                           <button  class="btn btn-warning" @click="viewApprovedCcir(ccirApprovedForm.id)">View</button>
                            <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ccirApprovedForm.id}`">Delete</button>
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3" v-if="ccirApprovedForms.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ ccirApprovedForms.length }} Ccir form(s)</span>
            </div>
        </div>

        <div class="card-body table-full-width table-responsive" v-if="!ccirApprovedForms.length && !loading">
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
            ccirApprovedForms: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }
    },
    created(){
        this.fetchCcirApprovedForms();
    },
    methods:{
        moment,
        viewApprovedCcir(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/ccir-view/${id}`;
        },
        fetchCcirApprovedForms(){
            this.loading = true;
            axios.get('/ccirs-approved-forms')
            .then(response => {
                this.ccirApprovedForms = response.data;
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
        filteredCcirs(){
            let self = this;
            return self.ccirApprovedForms.filter(ccirApprovedForm => {
                return ccirApprovedForm.complainant.toLowerCase().includes(this.keywords.toLowerCase())
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

