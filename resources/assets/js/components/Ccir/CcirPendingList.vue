<template>
    <div>

        <content-placeholders v-if="loading">
            <content-placeholders-heading :img="true" />
            <content-placeholders-text :lines="3" />
        </content-placeholders>

        <div class="card-body table-full-width table-responsive" v-if="ccirs.length">
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
                        <td>{{ moment(ccir.date_request ).format('LL') }}</td>
                        <td>{{ ccir.status }}</td>
                        <td>
                           <button  class="btn btn-warning btn-round btn-fill" @click="approveCcir(ccir.id)">Click for approval</button>
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3" v-if="ccirs.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ ccirs.length }} CCIR form(s)</span>
            </div>
        </div>

        <div class="card-body table-full-width table-responsive" v-if="!ccirs.length && !loading">
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
            ccirs: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }
    },
    moment,
    created(){
        this.fetchCcirs();
    },
    methods:{
        fetchCcirs(){
            this.loading = true;
            axios.get('/ccirs')
            .then(response => {
                this.ccirs = response.data;
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

