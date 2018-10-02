<template>
    <div>
        <content-placeholders v-if="loading">
            <content-placeholders-heading :img="true" />
            <content-placeholders-text :lines="3" />
        </content-placeholders>

        <div class="card-body table-full-width table-responsive" v-if="drdrsApprovedForms.length">
             <div class="row mb-4 ml-2">
                <div class="col-md-12">
                    <label for="name">Search by Document title</label>
                    <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                </div>
            </div>
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
                    <tr v-for="drdrsApprovedForm in filteredQueues" v-bind:key="drdrsApprovedForm.id">
                        <td>{{ drdrsApprovedForm.id }}</td>
                        <td>{{ drdrsApprovedForm.document_title }}</td>
                        <td>{{ drdrsApprovedForm.company.name  }}</td>
                        <td v-if="drdrsApprovedForm.rev_number !== 'null'">{{ drdrsApprovedForm.rev_number }}</td>
                        <td style="padding-left: 30px" v-else>{{ "-" }}</td> 
                        <td>
                            {{ drdrsApprovedForm.reviewer.name }}<br>
                            <span style="color: red" v-if="drdrsApprovedForm.status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdrsApprovedForm.status == 5"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td>{{ moment(drdrsApprovedForm.effective_date).format('LL') }}</td>
                        <td>
                            <button  class="btn btn-warning btn-round btn-fill" @click="viewApprovedDrdr(drdrsApprovedForm.id)">View</button>
                        </td>
                    </tr>    
                </tbody>
            </table>
        </div>
        <div class="row mb-3" v-if="drdrsApprovedForms.length">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ drdrsApprovedForms.length }} DRDR form(s)</span>
            </div>
        </div>

        <div class="card-body table-full-width table-responsive" v-if="!drdrsApprovedForms.length && !loading">
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
                    <tr>
                        <td>No data available in the table</td>
                    </tr>    
                </tbody>
            </table>
        </div>
    </div>   
</template>
<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
export default {
    components:{
      VueContentPlaceholders  
    },
    data() {
        return {
            drdrsApprovedForms: [],
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
            loading: false
        }
    },

    created() {
        this.fetchdrdrsApprovedForms();
    },

    methods: {
        moment,
        viewApprovedDrdr(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/drdr-view/${id}`;
        },
        fetchdrdrsApprovedForms()
        {
            this.loading = true;
            axios.get('/drdrs-approved-forms')
            .then(response => {
                this.drdrsApprovedForms = response.data;
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
    computed:
    {
        filteredDrdrs(){
            let self = this;
            return self.drdrsApprovedForms.filter(drdrsApprovedForm => {
                return drdrsApprovedForm.document_title.toLowerCase().includes(this.keywords.toLowerCase())
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

