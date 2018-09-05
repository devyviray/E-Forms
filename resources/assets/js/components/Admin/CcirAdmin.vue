<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header ">
                <h4 class="card-title">Customer Complaint Inforamtion Report</h4>
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
                            <button @click="viewCcirDetails(ccir.id)" class="btn btn-warning">View</button>
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
            ccirs: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchCcirs();
    },
    methods:{
        fetchCcirs(){
            axios.get('/admin/ccirs-all')
            .then(response => {
                this.ccirs = response.data; 
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        viewCcirDetails(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/admin/ccir-details/${id}`;
            
        },
        generateByDate(){
            axios.get('/ccirs-generate/'+ this.startDate + '/' + this.endDate)
            .then(response => { 
                this.ccirs = response.data;
            })
            .catch(error => {
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

