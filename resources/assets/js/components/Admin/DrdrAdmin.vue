<template>
      <div>
        <div class="card-body table-full-width table-responsive">
            <div class="card-header ">
                <h4 class="card-title">Document Review & Document Request</h4>   
            </div>
            <div class="row mb-3">
                <div class="row">
                    <datepicker v-model="startDate" placeholder="Select Start Date"></datepicker>
                    <datepicker v-model="endDate" placeholder="Select End Date"></datepicker>
                    <button @click="generateByDate" class="btn btn-primary">Generate</button>
                </div>
            </div>
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
                    <tr v-for="drdr in filteredQueues" v-bind:key="drdr.id">
                        <td>{{ drdr.id }}</td>
                        <td>{{ drdr.document_title }}</td>
                        <td>{{ drdr.company.name  }}</td>
                        <td>{{ drdr.rev_number }}</td>
                        <td>{{ drdr.reviewer.name }}</td>
                        <td v-if="drdr.approver">{{ drdr.approver.name }}</td>
                        <td style="padding-left: 30px" v-else>{{ ' - '  }}</td>
                        <td>
                            <button  class="btn btn-warning" @click="viewDrdrDetails(drdr.id)">View</button>
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
                <span>{{ drdrs.length }} Drdr form(s)</span>
            </div>
        </div>
    </div>   
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {
    components:{
      Datepicker  
    },
    data(){
        return{
            drdrs: [],
            startDate: '',
            endDate: '',
            keywords: '',
            errors: '',
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    watch: {
        drdrs: function (val, oldVal) {
       
        }
    },
    created(){
        this.fetchDrdrs();
    },
    methods:{
        fetchDrdrs()
        {
            axios.get('/admin/drdrs-all')
            .then(response => {
                this.drdrs = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        viewDrdrDetails(id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+`/admin/drdr-details/${id}`;
            
        },
        generateByDate(){
            axios.get('/drdrs-generate/'+ this.startDate + '/' + this.endDate)
            .then(response => { 
                this.drdrs = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
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
            return self.drdrs.filter(drdr => {
                return drdr.document_title.toLowerCase().includes(this.keywords.toLowerCase())
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

