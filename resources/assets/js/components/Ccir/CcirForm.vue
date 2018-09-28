<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12">
            <div class="row">
                <!-- <div class="col-md-2"></div> -->
                <div class="card col-md-12">
                    <form>
                        <div class="form-group">
                            <h1 style="color: #888888; text-align: center;">ADD CCIR</h1>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="complainant" class="col-sm-2 col-form-label">Complainant</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="ccir.complainant" id="complainant" placeholder="Complainant">
                                        <span class="error" v-if="errors.complainant">{{ errors.complainant[0] }}</span> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="company" class="col-sm-2 col-form-label">Company</label>
                                    <div class="col-sm-10">
                                        <select v-model="ccir.company_id" class="form-control form-control-lg">
                                            <option value="" disabled selected>Select Company</option>
                                            <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.company">{{ errors.company[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="companyLocation" class="col-sm-2 col-form-label">Location</label>
                                    <div class="col-sm-10">
                                        <select v-model="ccir.company_location" class="form-control form-control-lg"  id="companyLocation">
                                            <option value="" disabled selected>Select  Company Location</option>
                                            <option v-for="(loc, c) in selectedLocation" :value="loc.id" v-bind:key="c">{{ loc.address }}</option>
                                        </select>
                                        <span class="error" v-if="errors.company_location">{{ errors.company_location[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="commodity" class="col-sm-2 col-form-label">Commodity</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="ccir.commodity" id="commodity">
                                        <span class="error" v-if="errors.commodity">{{ errors.commodity[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="product_control_number" class="col-sm-2 col-form-label">Product control number</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="ccir.product_control_number" id="product_control_number">
                                        <span class="error" v-if="errors.product_control_number">{{ errors.product_control_number[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="affected_quantity" class="col-sm-2 col-form-label">Affected Quantity</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="ccir.affected_quantity" id="affected_quantity">
                                        <span class="error" v-if="errors.affected_quantity">{{ errors.affected_quantity[0] }}</span>
                                    </div>   
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="delivery_date" class="col-sm-2 col-form-label">Delivery date</label>
                                    <div class="col-sm-10">
                                        <datepicker v-model="ccir.delivery_date" id="delivery_date" placeholder="Select delivery date"></datepicker>
                                        <span class="error" v-if="errors.delivery_date">{{ errors.delivery_date[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="quality_of_sample" class="col-sm-2 col-form-label">Quantity of sample</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="ccir.quality_of_sample" id="quality_of_sample">
                                        <span class="error" v-if="errors.affected_quantity">{{ errors.affected_quantity[0] }}</span>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="nature_of_complaint" class="col-sm-2 col-form-label">Nature of Complaint</label>
                                    <div class="col-sm-10">
                                        <select v-model="ccir.nature_of_complaint" @change="selectedReason(ccir.nature_of_complaint)" class="form-control form-control-lg">
                                            <option value="" disabled selected>Nature of Complaint</option>
                                            <option value="1">Wet/Lumpy</option>
                                            <option value="2">Busted bag</option>
                                            <option value="3">Under/Over weight</option>
                                            <option value="4">Infested</option>
                                            <option value="5">Dirty packaging</option>
                                            <option value="6">Others</option>
                                        </select>
                                        <span class="error"  v-if="errors.nature_of_complaint">{{ errors.nature_of_complaint[0] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="others == 1">
                                    <label for="others" class="col-sm-2 col-form-label">Others (Please specify)</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="Others (Please specify)" v-model="ccir.others">
                                        <span class="error" v-if="errors.others">{{ errors.others[0] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="returned_date" class="col-sm-2 col-form-label">Returned Date</label>
                                    <div class="col-sm-10">
                                        <datepicker v-model="ccir.returned_date" placeholder="Select returned date" id="returned_date"></datepicker>
                                        <span class="error" v-if="errors.returned_date">{{ errors.returned_date[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="other_details" class="col-sm-2 col-form-label">Other Details</label>
                                    <div class="col-sm-10">
                                        <textarea  class="form-control" v-model="ccir.other_details" id="other_details"></textarea>
                                        <span class="error" v-if="errors.other_details">{{ errors.other_details[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="attachments" class="col-sm-2 col-form-label">Attach File</label>
                                    <div class="col-sm-10">
                                        <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                        <span class="error" v-if="errors.attachments">{{ errors.attachments[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>    
                        </div>
                        <button @click="addCcir(ccir)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .vdp-datepicker  input{
        background-color: #FFFFFF;
        border: 1px solid #E3E3E3;
        border-radius: 4px;
        color: #565656;
        padding: 8px 12px;
        height: 40px;
        -webkit-box-shadow: none;
        box-shadow: none;
        display: block;
        width: 100%;
        line-height: 1.5;   
    }
</style>
<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>

<script>
import Datepicker from 'vuejs-datepicker';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

export default {
    data(){
        return{
            ccirs: [],
            ccir: {
                id: '',
                issuer_id: '',
                company_id: '',
                company_location: '',
                complainant: '',
                commodity: '',
                brand_name: '',
                product_control_number: '',
                request_date: '',
                cancel_date: '',
                delivery_date: '',
                returned_date: '',
                nature_of_complaint: '',
                others: ' ',
                other_details: '',
                affected_quantity: '', 
                quality_of_sample: '',
                attached_file: '',
                verifier_id: '',
                status: '',
                remarks: '',
            },
            others: '',
            companies: [],
            locations: [],
            attachments: [],
            formData: new FormData(),
            errors: '',
            isLoading: false
        }
    },
    created(){
        this.fetchCompanies();
        this.fetchLocations();
    },
    computed: {
        selectedLocation() {
            var findCompany = this.companies.find(item => item.id === this.ccir.company_id)
            if(findCompany) {
                return this.locations.filter(item => {
                    return item.name === findCompany.name
                })
            }
        }
    },
    methods: {
        fetchLocations() {
            axios.get('/companyLocation')
            .then(response => {
                this.locations = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        fetchCompanies(){
            axios.get('/companies')
            .then(response => {
                this.companies = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
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
        addCcir(ccir){
            this.isLoading = true;
            this.errors = [];
            this.prepareFields();
            this.formData.append('complainant', ccir.complainant);
            this.formData.append('company', ccir.company_location);
            this.formData.append('company_location', ccir.company_location);
            this.formData.append('commodity', ccir.commodity);
            this.formData.append('product_control_number', ccir.product_control_number);
            this.formData.append('delivery_date', ccir.delivery_date);
            this.formData.append('nature_of_complaint', ccir.nature_of_complaint);
            this.formData.append('others', ccir.others);
            this.formData.append('other_details', ccir.other_details);
            this.formData.append('delivery_date', ccir.delivery_date);
            this.formData.append('affected_quantity', ccir.affected_quantity);
            this.formData.append('quality_of_sample', ccir.quality_of_sample);
            this.formData.append('request_date',ccir.request_date);
            this.formData.append('returned_date', ccir.returned_date);

            axios.post('/ccir', this.formData)
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'CCIR Succesfully Added',
                    position: 'top right'
                });
                this.resetData();
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            });
        },
        selectedReason(id){
           id == 6 ? this.others = 1 : this.others = 2; 
        }
    },
     components: {
         Datepicker,
         SpinnerLoading
    }
}
</script>
