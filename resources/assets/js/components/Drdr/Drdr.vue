<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2"  style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));" @click="addDrdrForm" >Add Drdr</button>
                <div class="card strpied-tabled-with-hover">
                     <div class="card-header mb-3">
                        <h4 class="card-title">Document Review & Document Request</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                         <li class="nav-item">
                            <a class="nav-link active" id="submitted-tab" data-toggle="tab" @click="selected=1" href="#submitted" role="tab" aria-controls="submitted" aria-selected="true">Submitted Forms</a>
                        </li>
                        <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(4)">
                            <a class="nav-link" id="pending-reviews-tab" data-toggle="tab" @click="selected=2" href="#pending-reviews" role="tab" aria-controls="pending-reviews" aria-selected="false">Pending Reviews</a>
                        </li>
                        <li  class="nav-item" v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(4)">
                            <a class="nav-link" id="reviewed-forms-tab" data-toggle="tab" @click="selected=3" href="#reviewed-forms" role="tab" aria-controls="reviewed-forms" aria-selected="false">Reviewed Forms</a>
                        </li>
                        <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(3)">
                            <a class="nav-link" id="pending-approvals-tab" data-toggle="tab" @click="selected=4"  href="#pending-approvals" role="tab" aria-controls="pending-approvals" aria-selected="false">Pending Approvals</a>
                        </li>
                        <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2) || roleId.includes(3)">
                            <a class="nav-link" id="approved-forms-tab" data-toggle="tab"   @click="selected=5"  href="#approved-forms" role="tab" aria-controls="approved-forms" aria-selected="false">Approved Forms</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">

                            <drdr-submitted v-if="selected==1"></drdr-submitted>
                            
                        </div>
                        <div class="tab-pane fade" id="pending-reviews" role="tabpanel" aria-labelledby="pending-reviews-tab">
                                                    
                            <drdr-pending-review-list v-if="selected==2"></drdr-pending-review-list>

                        </div>
                        <div class="tab-pane fade" id="reviewed-forms" role="tabpanel" aria-labelledby=" reviewed-forms-tab">
                           
                            <drdr-reviewed-list v-if="selected==3"></drdr-reviewed-list>

                        </div>
                        <div class="tab-pane fade" id="pending-approvals" role="tabpanel" aria-labelledby="pending-approvals-tab">

                            <drdr-pending-approval-list v-if="selected==4"></drdr-pending-approval-list>

                        </div>
                        <div class="tab-pane fade" id="approved-forms" role="tabpanel" aria-labelledby="approved-forms-tab">

                            <drdr-approved-list v-if="selected==5"></drdr-approved-list>

                        </div>
                    </div>
                </div>
            </div>
        </div>
     
        <!-- Delete Modal
        <div  v-for="(drdr, d) in filteredQueues" :key="d" class="modal fade" :id="`deleteModal-${drdr.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete DRDR</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="drdr.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this DRDR?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteDrdr(drdr.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import DrdrPendingReviewList from './DrdrPendingReviewList';
import DrdrReviewedList from './DrdrReviewedList';
import DrdrPendingApprovalList from './DrdrPendingApprovalList';
import DrdrApprovedList from './DrdrApprovedList';
import DrdrSubmitted from './DrdrSubmitted';

export default {

    components:
    {
        DrdrPendingReviewList,
        DrdrReviewedList,
        DrdrPendingApprovalList,
        DrdrApprovedList,
        DrdrSubmitted,
    },

    props: ['roleId'],

    data(){
        return{
            selected: 1,
            attachments: [],
            formData: new FormData(),
            selectedStatus: '',
            keywords: '',
            keywords2: '',
            errors: '',
            currentPage: 0,
           itemsPerPage: 10,
        }
    },
    created(){
    },
    methods: {
        approveDrdr($id)
        {
            var base_url = window.location.origin;
            window.location.href = base_url+'/drdr-approve/'+$id;
        },
        prepareFields(){
            if(this.attachments.length > 0){
                for(var i = 0; i < this.attachments.length; i++){
                    let attachment = this.attachments[i];
                    this.formData.append('attachments[]', attachment);
                }
            } 
        },
        uploadFileChange(e){
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
                this.attachments.push(files[i]);
            }
        },
        resetData(){
          this.formData = new FormData();
          this.attachments = [];  
        },
        addDrdrForm(){
            var base_url = window.location.origin;
            window.location.href = base_url+'/add-drdr';
        },
        deleteDrdr(id){
            axios.delete(`/drdr/${id}`)
            .then(response => {
                this.fetchDrdrs();
            })
            .catch(error => {
               this.errors = error.response.data.errors; 
            });
        }
    },
}
</script>

