<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Complainant" v-model="ccir.complainant">
                        <span v-if="errors.complainant">{{ errors.complainant }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Commodity" v-model="ccir.commodity">
                        <span v-if="errors.commodity">{{ errors.commodity }}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Product control number" v-model="ccir.product_control_number">
                        <span v-if="errors.product_control_number">{{ errors.product_control_number }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker  placeholder="Select Delivery Date" v-model="ccir.delivery_date"></datepicker>
                        <span v-if="errors.delivery_date">{{ errors.delivery_date }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="ccir.nature_of_complaint" class="form-control form-control-lg">
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
                        <textarea  class="form-control" v-model="ccir.other_details" placeholder="Other Details"></textarea>
                        <span v-if="errors.other_details">{{ errors.other_details }}</span>
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
                        <datepicker placeholder="Select returned Date" v-model="ccir.returned_date"></datepicker>
                        <span v-if="errors.returned_date">{{ errors.returned_date }}</span>
                    </div>
                    <div class="form-group">
                         <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange">
                    </div>
                    <button @click="addCcir(ccir)" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    data(){
        return{
            ccirs: [],
            ccir: {
                id: '',
                issuer_id: '',
                company_id: '',
                complainant: '',
                commodity: '',
                brand_name: '',
                product_control_number: '',
                request_date: '',
                cancel_date: '',
                delivery_date: '',
                returned_date: '',
                nature_of_complaint: '',
                other_details: '',
                affected_quantity: '', 
                quality_of_sample: '',
                attached_file: '',
                verifier_id: '',
                status: '',
                remarks: ''

            },
            attachments: [],
            formData: new FormData(),
            errors: '',
        }
    },
    methods: {
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
        addCcir(ccir){
            this.prepareFields();
            this.formData.append('complainant', ccir.complainant);
            this.formData.append('commodity', ccir.commodity);
            this.formData.append('product_control_number', ccir.product_control_number);
            this.formData.append('delivery_date', ccir.delivery_date);
            this.formData.append('nature_of_complaint', ccir.nature_of_complaint);
            this.formData.append('other_details', ccir.other_details);
            this.formData.append('delivery_date', ccir.delivery_date);
            this.formData.append('affected_quantity', ccir.affected_quantity);
            this.formData.append('quality_of_sample', ccir.quality_of_sample);
            this.formData.append('request_date',ccir.request_date);
            this.formData.append('returned_date', ccir.returned_date);

            axios.post('/ccir', this.formData)
            .then(response => {
                this.resetData();
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
    },
     components: {
         Datepicker
    }
}
</script>
