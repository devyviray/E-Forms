<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">

            <content-placeholders v-if="loading">
                <content-placeholders-heading :img="true" />
                <content-placeholders-text :lines="3" />
            </content-placeholders>
            
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Document title</th>
                    <th>Reason of request</th>
                    <th>Rev.</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="drdrsPendingReview in filteredQueues" v-bind:key="drdrsPendingReview.id">
                        <td>{{ drdrsPendingReview.id }}</td>
                        <td>{{ drdrsPendingReview.document_title }}</td>
                        <td>{{ drdrsPendingReview.reason_request  }}</td>
                        <td>{{ drdrsPendingReview.rev_number }}</td>
                        <td>
                            <button  class="btn btn-warning" @click="viewDrdr(drdrsPendingReview.id)">Review</button>
                            <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${drdrsPendingReview.id}`">Delete</button>
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
                <span>{{ drdrsPendingReviews.length }} Drdr form(s)</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            drdrsPendingReviews:[],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }
    },
    created(){
        this.fetchDrdrsPendingReviews();
    },
    methods:{
        viewDrdr($id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+'/drdr-review/'+$id;
        },
        fetchDrdrsPendingReviews(){
            this.loading = true;
            axios.get('/drdrs-pending-reviews')
            .then(response => {
                this.drdrsPendingReviews = response.data;
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
    computed:{
        filteredDrdrs(){
            let self = this;
            return self.drdrsPendingReviews.filter(drdrsPendingReview => {
                return drdrsPendingReview.document_title.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.drdrsPendingReviews.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredDrdrs.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return queues_array;
        }
    },
}
</script>

