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
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="drdrsApprovedForm in drdrsApprovedForms" v-bind:key="drdrsApprovedForm.id">
                        <td>{{ drdrsApprovedForm.id }}</td>
                        <td>{{ drdrsApprovedForm.document_title }}</td>
                        <td>{{ drdrsApprovedForm.company.name  }}</td>
                        <td>{{ drdrsApprovedForm.rev_number }}</td>
                        <td>{{ drdrsApprovedForm.reviewer.name }}</td>
                        <td>
                            <button  class="btn btn-warning" @click="deleteDrdr(drdrsApprovedForm.id)">Delete</button>
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
                <span>{{ drdrsApprovedForms.length }} Drdr form(s)</span>
            </div>
        </div>
    </div>   
</template>
<script>
export default {
    data() {
        return {
            drdrsApprovedForms: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },

    created() {
        this.fetchdrdrsApprovedForms();
    },

    methods: {

        fetchdrdrsApprovedForms()
        {
            axios.get('/drdrs-approved-forms')
            .then(response => {
                this.drdrsApprovedForms = response.data;
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
    computed:
    {
        filteredDrdrs(){
            let self = this;
            return self.drdrsApprovedForms.filter(drdrsApprovedForm => {
                return drdrsApprovedForm.document_title.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.drdrsApprovedForms.length / this.itemsPerPage)
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
    }
}
</script>

