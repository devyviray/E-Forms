<template>
   <div>
        <select v-model="selectedStatus" @change="fetchDdrBystatus">
            <option value="" disabled selected>Select By</option>
            <option value="all">ALL</option>
            <option value="pending">PENDING</option>
            <option value="approved">APPROVED</option>
        </select>
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
                        <td>{{ ddr.date_requested }}</td>
                        <td>{{ ddr.status }}</td>
                        <td>
                            <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ddr.id}`">Edit</button>
                            <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ddr.id}`">Delete</button>
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
        fetchDdrs()
        {
            axios.get('/ddrs')
            .then(response => {
                this.ddrs = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        fetchDdrBystatus()
        {
            axios.get(`/ddrs-by/${this.selectedStatus}`)
            .then(response => {
                this.ddrs = response.data;
            })
            .catch(error => {
                this.errors = error.responde.data.errors;
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
        filteredDdrs(){
            let self = this;
            return self.ddrs.filter(ddr => {
                return ddr.document_title.toLowerCase().includes(this.keywords.toLowerCase())
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

