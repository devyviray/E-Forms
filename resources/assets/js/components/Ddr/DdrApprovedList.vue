<template>
   <div>
        <div class="card-body table-full-width table-responsive">
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Reason</th>
                    <th>date_requested</th>
                    <th>Status</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="ddr in filteredQueues" v-bind:key="ddr.id">
                        <td>{{ ddr.id }}</td>
                        <td>{{ ddr.requester.name }}</td>
                        <td>{{ ddr.reason_of_distribution }}</td>
                        <td>{{ moment(ddr.date_request).format('LL') }}</td>
                        <td>
                            <span style="color: red" v-if="ddr.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ddr.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>
                            <button  class="btn btn-warning" @click="viewApprovedDdr(ddr.id)">View</button>
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
                <span>{{ ddrs.length }} Ddr form(s)</span>
            </div>
        </div>
   </div>
</template>

<script>
import moment from 'moment';
export default {
    data(){
        return{
            ddrs: [],
            selectedStatus: ' ',
            keywords: '',
            errors: '',
            currentPage: 0,
           itemsPerPage: 10,
        }
    },
    created(){
        this.fetchDdrs();
    },
    methods:{
        moment,
        viewApprovedDdr(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/ddr-view/${id}`;
        },
        fetchDdrs()
        {
            axios.get('/ddrs-approved-forms')
            .then(response => {
                this.ddrs = response.data;
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
        filteredDdrs(){
            let self = this;
            return self.ddrs.filter(ddr => {
                return ddr.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.ddrs.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredDdrs.slice(index, index + this.itemsPerPage);

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

