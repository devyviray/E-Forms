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
                    <th>Effective date</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="drdrsPendingApprovalForm in drdrsPendingApprovalForms" v-bind:key="drdrsPendingApprovalForm.id">
                        <td>{{ drdrsPendingApprovalForm.id }}</td>
                        <td>{{ drdrsPendingApprovalForm.document_title }}</td>
                        <td>{{ drdrsPendingApprovalForm.company.name  }}</td>
                        <td>{{ drdrsPendingApprovalForm.rev_number }}</td>  
                        <td>
                            {{ drdrsPendingApprovalForm.reviewer.name }}<br>
                            <span style="color: red" v-if="drdrsPendingApprovalForm.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdrsPendingApprovalForm.status == 5"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>{{ drdrsPendingApprovalForm.effective_date }}</td>
                        <td>
                            <button  class="btn btn-warning" @click="approveDrdr(drdrsPendingApprovalForm.id)">Approve</button>
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
                <span>{{ drdrsPendingApprovalForms.length }} Drdr form(s)</span>
            </div>
        </div>
    </div>      
</template>
<script>
export default {
    data() {
        return {
            drdrsPendingApprovalForms: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },

    created() {
        this.fetchdrdrsPendingApprovalForms()
    },

    methods: {

        fetchdrdrsPendingApprovalForms()
        {
            axios.get('/drdrs-pending-approvals')
            .then(response => {
                this.drdrsPendingApprovalForms = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        approveDrdr($id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+'/drdr-approve/'+$id;
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
            return self.drdrsPendingApprovalForms.filter(drdrsPendingApprovalForm => {
                return drdrsPendingApprovalForm.document_title.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.drdrsPendingApprovalForms.length / this.itemsPerPage)
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

