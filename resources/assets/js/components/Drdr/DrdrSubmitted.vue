<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Document title</th>
                    <th>Rev.</th>
                    <th>Reviewer</th>
                    <th>Approver</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="drdrSubmitted in filteredQueues  " v-bind:key="drdrSubmitted.id">
                        <td>{{ drdrSubmitted.id }}</td>
                        <td>{{ drdrSubmitted.document_title }}</td>
                        <td>{{ drdrSubmitted.rev_number  }}</td>
                        <td>
                            {{ drdrSubmitted.reviewer.name }}<br>
                            <span style="color: red" v-if="drdrSubmitted.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdrSubmitted.status == 5"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td v-if="drdrSubmitted.approver !== null">
                            {{ drdrSubmitted.approver.name }}<br>
                            <span style="color: red" v-if="drdrSubmitted.status == 3"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdrSubmitted.status == 5"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td style="padding-left: 30px" v-else>{{ "-" }}</td>
                        <td>
                            <button class="btn btn-primary" @click="viewDrdr(drdrSubmitted.id)">View</button>
                            <button v-if="drdrSubmitted.status == 2" class="btn btn-warning" @click="editDrdr(drdrSubmitted.id)">Edit</button>
                            <button  class="btn btn-danger" data-toggle="modal" :data-target="`#deleteModal-${drdrSubmitted.id}`">Delete</button>
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
                <span>{{ drdrSubmitteds.length }} Drdr form(s)</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
      data() {
        return {
            drdrSubmitteds: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created() {
        this.fetchDrdrSubmitteds();
    },
    methods:{
        fetchDrdrSubmitteds(){
            axios.get('/drdr-submitted')
            .then(response => {
                this.drdrSubmitteds = response.data;
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        viewDrdr(id){
            alert(id);
        },
        editDrdr(id){
            var base_url = window.location.origin;
            window.location.href = base_url+`/edit-drdr/${id}`;
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
            return self.drdrSubmitteds.filter(drdrSubmitted => {
                return drdrSubmitted.document_title.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredDrdrs.length / this.itemsPerPage)
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

