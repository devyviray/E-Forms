<template>
    <div>
        <div class="card-body table-full-width table-responsive">
            <input type="text" class="form-control  mb-5" placeholder="Search" v-model="keywords">
            <table class="table table-hover table-striped">
                <thead>
                    <th>ID</th>
                    <th>Approver</th>
                    <th>Reason</th>
                    <th>date_requested</th>
                    <th>Approver status</th>
                    <th>Distributed</th>
                    <th>Option</th>
                </thead>    
                <tbody>
                    <tr v-for="ddrSubmitted in ddrSubmitteds" v-bind:key="ddrSubmitted.id">
                        <td>{{ ddrSubmitted.id }}</td>
                        <td>{{ ddrSubmitted.approver.name }}</td>
                        <td>{{ ddrSubmitted.reason_of_distribution }}</td>
                        <td>{{ moment(ddrSubmitted.date_request).format('LL') }}</td>
                        <td>
                            <span style="color: red" v-if="ddrSubmitted.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ddrSubmitted.status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>
                            <span style="color: red" v-if="ddrSubmitted.status == 4"> PENDING </span>
                            <span style="color: green" v-else-if="ddrSubmitted.status == 14"> DISTRIBUTED </span>
                            <span style="color: green; padding-left: 30px" v-else> {{ '-' }} </span>
                        </td>
                        <td>
                            <button class="btn btn-primary" @click="viewDdr(ddrSubmitted.id)">View</button>
                            <button v-if="ddrSubmitted.status == 2" class="btn btn-warning" @click="editDdr(ddrSubmitted.id)">Edit</button>
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
import moment from 'moment';
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
        moment,
        viewDdr(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/ddr-view/${id}`;
        },
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
        viewDrdr(id){
            alert(id);
        },
        editDdr(id){
            var base_url = window.location.origin;
            window.location.href = base_url+`/edit-ddr/${id}`;
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

