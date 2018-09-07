<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mb-2"  @click="addCcirForm" >Add Ccir</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Ccir Forms</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="submitted-tab" data-toggle="tab" @click="selected=1" href="#submitted" role="tab" aria-controls="submitted" aria-selected="true">Submitted Forms</a>
                        </li>
                        <!-- <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2)">
                            <a class="nav-link" id="pending-tab" data-toggle="tab" @click="selected=2" href="#pending" role="tab" aria-controls="pending" aria-selected="false">Pending Forms</a>
                        </li>
                        <li class="nav-item" v-if="roleId.includes(1) || roleId.includes(2)">
                            <a class="nav-link" id="approve-tab" data-toggle="tab" @click="selected=3"  href="#approve" role="tab" aria-controls="approve" aria-selected="false">Approved Forms</a>
                        </li> -->
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">

                            <ccir-submitted v-if="selected==1"></ccir-submitted>

                        </div>
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">

                            <ccir-pending-list v-if="selected==2"></ccir-pending-list>

                        </div>
                        <div class="tab-pane fade" id="approve" role="tabpanel" aria-labelledby="approve-tab">

                            <ccir-approved-list v-if="selected==3"></ccir-approved-list>

                        </div>
                    </div>
                </div>
            </div>
        </div>
          <!-- Update Modal
        <div v-for="(ccir,c) in filteredQueues" :key="'list-' +  c" class="modal fade" :id="`editModal-${ccir.id}`" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit ccir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Complainant" v-model="ccir.complainant">
                        <span v-if="errors.complainant">{{ errors.complainant}}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Commodity" v-model="ccir.commodity">
                        <span v-if="errors.commodity">{{ errors.commodity }}</span>
                    </div>
                    <div class="form-group">
                        <input placeholder="Commodity" v-model="ccir.product_control_number">
                        <span v-if="errors.product_control_number">{{ errors.product_control_number }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker placeholder="Select Date" v-model="ccir.delivery_date"></datepicker>
                        <span v-if="errors.delivery_date">{{ errors.delivery_date }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="ccir.reason" class="form-control form-control-lg">
                           <option value="" disabled selected>Nature of Complaint</option>
                           <option value="1">Wet/Lumpy</option>
                           <option value="2">Busted bag</option>
                           <option value="3">Under/Over weight</option>
                           <option value="3">Interested</option>
                           <option value="3">Dirty packaging</option>
                           <option value="3">Others</option>
                       </select>
                        <span v-if="errors.type">{{ errors.type }}</span>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Other Details"></textarea>
                        <span v-if="errors.other_details">{{ errors.other_details }}</span>
                    </div>
                     <div class="form-group">
                        <datepicker placeholder="Select Date" v-model="ccir.delivery_date"></datepicker>
                        <span v-if="errors.delivery_date">{{ errors.delivery_date }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Affected Quantity" v-model="ccir.affected_quantity">
                        <span v-if="errors.affected_quantity">{{ errors.affected_quantity }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Quantity of Sample" v-model="ccir.quality_of_sample">
                        <span v-if="errors.affected_quantity">{{ errors.affected_quantity }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker placeholder="Select Date" v-model="ccir.return_date"></datepicker>
                        <span v-if="errors.return_date">{{ errors.return_date }}</span>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="file">
                    </div>   
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="editDdr(ddr,company,approver,department)" type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
            </div>
        </div> -->

        <!-- Delete Modal
        <div  v-for="(ccir, c) in filteredQueues" :key="c" class="modal fade" :id="`deleteModal-${ccir.id}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete CCIR</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ccir.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this CCIR?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  @click="deleteCcir(ccir.id)" type="button" class="btn btn-primary">Delete</button>
                </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import CcirPendingList from './CcirPendingList';
import CcirApprovedList from './CcirApprovedList';
import CcirSubmitted from './CcirSubmitted';

export default {
    
    components: {
         Datepicker,
         CcirPendingList,
         CcirApprovedList,
         CcirSubmitted
    },
    props: ['roleId'],
    data(){
        return{
            selected: 1,
        }
    },
    created(){
 
    },
    methods: {
        addCcirForm(){
            var base_url = window.location.origin;
            window.location.href = base_url+'/add-ccir';
        },
        editCcir(ccir){
            axios.post(`/ccir/${ccir.id}`,{

            })
            .then(response => {
                this.fetchCcirs();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteCcir(id){
            axios.delete(`/ccir/${id}`)
            .then(response => {
                this.   fetchCcirs();
            })
            .catch(error => {
               this.errors = error.response.data.errors; 
            });
        },

    }
}
</script>

