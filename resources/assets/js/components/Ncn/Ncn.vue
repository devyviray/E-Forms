<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));"  @click="addNcnForm">Add NCN</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">NCN Forms</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="submitted-tab" data-toggle="tab" @click="selected=1" href="#submitted" role="tab" aria-controls="submitted" aria-selected="true">Submitted Forms</a>
                        </li>
                        <li class="nav-item"  v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(3)">
                            <a class="nav-link" id="pending-tab" data-toggle="tab" @click="selected=2" href="#pending" role="tab" aria-controls="pending" aria-selected="false">Pending Forms</a>
                        </li>
                        <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(3)">
                            <a class="nav-link" id="approved-tab" data-toggle="tab" @click="selected=3" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved Forms</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">

                             <ncn-submitted v-if="selected==1"></ncn-submitted>

                        </div>
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">

                            <ncn-pending-approval-list v-if="selected==2"></ncn-pending-approval-list>

                        </div>
                        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                            
                              <ncn-approved-list v-if="selected==3"></ncn-approved-list>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal
        <div  v-for="(ncn, n) in filteredQueues" :key="n" class="modal fade" :id="`deleteModal-${ncn.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete NCN</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ncn.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this NCN?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteNcn(ncn.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import NcnPendingApprovalList from './NcnPendingApprovalList';
import NcnApprovedList from './NcnApprovedList';
import NcnSubmitted from './NcnSubmitted';

export default {
    components: {
        Datepicker,
        NcnPendingApprovalList,
        NcnApprovedList,
        NcnSubmitted
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
        }
    },
    created(){
      
    },
    methods: {
        addNcnForm(){
            var base_url = window.location.origin;
            window.location.href = base_url+'/add-ncn';
        },
        editNcn(ncn){
            axios.post(`/ncn/${ncn.id}`,{

            })
            .then(response => {
                this.fetchNcns();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteNcn(id){
            axios.delete(`/ncn/${id}`)
            .then(response => {
                this.fetchNcns();
            })  
            .catch(error => {
               this.errors = error.response.data.errors; 
            });
        },
    }
}
</script>

