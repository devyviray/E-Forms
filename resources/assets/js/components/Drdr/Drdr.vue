<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2"  style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));" @click="addDrdrForm" >Add DRDR</button>
                <div class="card strpied-tabled-with-hover">
                     <div class="card-header mb-3">
                        <h4 class="card-title">Document Review & Distribution Request</h4>   
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
            fileSize: 0,
            maximumSize: 5000000,
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
            this.attachments = [];
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
                this.attachments.push(files[i]);
                this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
            }
            if(this.fileSize > 5){
                alert('File size exceeds 5 MB');
                document.getElementById('attachments').value = "";
                this.attachments = [];
                this.fileSize = 0;
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

