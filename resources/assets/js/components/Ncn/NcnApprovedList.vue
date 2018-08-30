<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Position</th>
                    <th>Notification</th>
                    <th>Date of Issuance</th>
                    <th>Status</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="ncnApprovedForm in filteredQueues" v-bind:key="ncnApprovedForm.id">
                        <td>{{ ncnApprovedForm.id }}</td>
                        <td>{{ ncnApprovedForm.requester.name }}</td>
                        <td>{{ ncnApprovedForm.requester.position }}</td>
                        <!-- <td>{{ ncn.attached_files }}</td>
                        <td>{{ ncn.non_conformity_details }}</td> -->
                        <td>{{ ncnApprovedForm.notification_number }}</td>
                        <td>{{ ncnApprovedForm.issuance_date }}</td>
                        <td>{{ ncnApprovedForm.status }}</td>
                        <td>
                            <button  class="btn btn-warning" data-toggle="modal" :data-target="`#editModal-${ncnApprovedForm.id}`">Edit</button>
                            <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${ncnApprovedForm.id}`">Delete</button>
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
                <span>{{ ncnApprovedForms.length }} Ncn form(s)</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            ncnApprovedForms: [],
            keywords: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchNcnApprovedForms();
    },
    methods:{
        fetchNcnApprovedForms()
        {
            axios.get('/ncns-approved-forms')
            .then(response => {
                this.ncnApprovedForms = response.data;
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
        filteredNcns(){
            let self = this;
            return self.ncnApprovedForms.filter(ncnApprovedForm => {
                return ncnApprovedForm.requester.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredNcns.length / this.itemsPerPage)
        },

        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredNcns.slice(index, index + this.itemsPerPage);

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

