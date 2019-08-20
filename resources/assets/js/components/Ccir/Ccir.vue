<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2"  @click="addCcirForm" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Add CCIR</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">CCIR Forms</h4>   
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="submitted-tab" data-toggle="tab" @click="selected=1" href="#submitted" role="tab" aria-controls="submitted" aria-selected="true">Submitted Forms</a>
                        </li>
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

