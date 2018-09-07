<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Document title</th>
                    <th>Company</th>
                    <th>Rev.</th>
                    <th>Reviewer</th>
                    <th>Approver</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="drdrsReviewedForm in drdrsReviewedForms" v-bind:key="drdrsReviewedForm.id">
                        <td>{{ drdrsReviewedForm.id }}</td>
                        <td>{{ drdrsReviewedForm.document_title }}</td>
                        <td>{{ drdrsReviewedForm.company.name  }}</td>
                        <td>{{ drdrsReviewedForm.rev_number }}</td>
                        <td>{{ drdrsReviewedForm.reviewer.name }}</td>
                        <td>{{ drdrsReviewedForm.approver.name }}</td>
                        <td>
                            <button  class="btn btn-warning" @click="viewReviewedDrdr(drdrsReviewedForm.id)">View</button>
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
                <span>{{ drdrsReviewedForms.length }} Drdr form(s)</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            drdrsReviewedForms: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchDrdrsReviewedForms();
    },
    methods:{
        viewReviewedDrdr(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/drdr-view/${id}`;
        },
        fetchDrdrsReviewedForms()
        {
            axios.get('/drdrs-reviewed-forms')
            .then(response => {
                this.drdrsReviewedForms = response.data;
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
            return self.drdrsReviewedForms.filter(drdrsReviewedForm => {
                return drdrsReviewedForm.document_title.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.drdrsReviewedForms.length / this.itemsPerPage)
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
