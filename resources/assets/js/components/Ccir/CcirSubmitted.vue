<template>
    <div>
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
                            <button  class="btn btn-primary">View</button>
                            <!-- <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ccirSubmitted.id}`">Edit</button> -->
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
                <span>{{ ccirSubmitteds.length }} Ccir form(s)</span>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data(){
        return{
            ccirSubmitteds: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchCcirSubmitted();
    },
    methods:{
        fetchCcirSubmitted(){
            axios.get('/ccirs-submitted')
            .then(response => {
                this.ccirSubmitteds = response.data; 
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
            return self.ccirSubmitteds.filter(ccirSubmitted => {
                return ccirSubmitted.complainant.toLowerCase().includes(this.keywords.toLowerCase())
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

