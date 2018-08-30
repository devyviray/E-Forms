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
                    <tr v-for="ddrSubmitted in ddrSubmitteds" v-bind:key="ddrSubmitted.id">
                        <td>{{ ddrSubmitted.id }}</td>
                        <td>{{ ddrSubmitted.requester.name }}</td>
                        <td>{{ ddrSubmitted.reason_of_distribution }}</td>
                        <td>{{ ddrSubmitted.date_requested }}</td>
                        <td>{{ ddrSubmitted.status }}</td>
                        <td>
                            <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ddrSubmitted.id}`">Edit</button>
                            <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ddrSubmitted.id}`">Delete</button>
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
                <span>{{ ddrSubmitteds.length }} Ddr submitted form(s)</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            ddrSubmitteds: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchDdrSubmitted();
    },
    methods:{
        fetchDdrSubmitted()
        {
            axios.get('/ddrs-submitted')
            .then(response => {
                this.ddrSubmitteds = response.data;
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
            return self.ddrSubmitteds.filter(ddr => {
                return ddrSubmitted.document_title.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.ddrSubmitteds.length / this.itemsPerPage)
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

