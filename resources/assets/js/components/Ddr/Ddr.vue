<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2"  style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));" @click="addDdrForm" >ADD DDR</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header mb-3">
                        <h4 class="card-title">Document Distribution Request</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="submitted-tab" data-toggle="tab" @click="selected=1" href="#submitted" role="tab" aria-controls="submitted" aria-selected="true">Submitted Forms</a>
                        </li>
                        <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(3)">
                            <a class="nav-link" id="pending-tab" data-toggle="tab" @click="selected=2" href="#pending" role="tab" aria-controls="pending" aria-selected="false">Pending Forms</a>
                        </li>
                        <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(3)">
                            <a class="nav-link" id="approve-tab" data-toggle="tab"  @click="selected=3" href="#approve" role="tab" aria-controls="approve" aria-selected="false">Approved Forms</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">

                            <ddr-submitted  v-if="selected==1"></ddr-submitted>

                        </div>
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">

                            <ddr-pending-list v-if="selected==2"></ddr-pending-list>

                        </div>
                        <div class="tab-pane fade" id="approve" role="tabpanel" aria-labelledby="approve-tab">
                            
                             <ddr-approved-list v-if="selected==3"></ddr-approved-list>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal
        <div  v-for="(ddr, d) in filteredQueues" :key="d" class="modal fade" :id="`deleteModal-${ddr.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete DDR</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ddr.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this DDR?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteDdr(ddr.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import DdrPendingList from './DdrPendingList';
import DdrApprovedList from './DdrApprovedList';
import DdrSubmitted from './DdrSubmitted';

export default {
     components: {
         Datepicker,
         DdrPendingList,
         DdrApprovedList,
         DdrSubmitted
    },
    props: ['roleId'],
    data(){
        return{
            selected: 1,
            companies: [],
            company:{
                id: '',
                name: '',
                address: ''
            },
            departments: [],
            department:{
                id: '',
                name: '',
            },
            approvers: [],
            approver: {
                id: '',
                name: '',
                email: '',
                department_id: ''
            },
            selected_company: ' ',
            selected_department: ' ',
            errors: '',
            currentPage: 0,
           itemsPerPage: 10,
        }
    },
    created(){
    },
    methods: {
        addDdrForm()
        {
            var base_url = window.location.origin;
            window.location.href =  base_url+'/add-ddr';
        },
        editDdr(ddr,company, approver, department){
            axios.post(`/ddr/${ddr.id}`,{

            })
            .then(response => {
                this.resetData();
                this.fetchDdrs();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteDdr(id){
            axios.delete(`/ddr/${id}`)
            .then(response => {
                this.fetchDdrs();
            })
            .catch(error => {
               this.errors = error.response.data.errors; 
            });
        }
    }
}
</script>

