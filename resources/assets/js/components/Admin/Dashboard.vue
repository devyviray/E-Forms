<template>
    <div>
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <h4 class="card-title">Dashboard</h4>   
                        </div>
                        <div class="card-body table-full-width table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>FORMS</th>
                                    <th>ENTRIES</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a :href="viewDrdrs"> Document review & Distribution request </a></td>
                                        <td>{{ drdrs }}</td>
                                    </tr>
                                    <tr>
                                        <td> <a :href="viewDdrs"> Document distribution request </a> </td>
                                        <td>{{ ddrs }}</td>
                                    </tr>
                                    <tr>
                                        <td><a :href="viewNcn"> Non-conformance notification</a></td>
                                        <td>{{ ncns }}</td>
                                    </tr>
                                    <tr>
                                        <td><a :href="viewCcir"> Customer complaint investigation report </a> </td>
                                        <td>{{ ccirs }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            drdrs: ' ',
            ddrs:  '',
            ccirs: '',
            ncns: '',
            errors: ''
         }
    },
    created(){
        this.fetchDrdr();
        this.fetchDdr();
        this.fetchCcir();
        this.fetchNcn();
    },
   
    methods: {  
        fetchDrdr(){
            axios.get('/admin/drdr-count')
                .then(response => {
                    this.drdrs = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });  
        },
        fetchDdr(){
            axios.get('/admin/ddr-count')
                .then(response => {
                    this.ddrs = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });  
        },
        fetchCcir(){
            axios.get('/admin/ccir-count')
                .then(response => {
                    this.ccirs = response.data;
                })
                .catch(error => console.log(errors));  
        },
        fetchNcn(){
            axios.get('/admin/ncn-count')
                .then(response => {
                    this.ncns = response.data;
                })
                .catch(error => console.log(errors));  
        },
    }, 
    computed:{
        viewDrdrs()
        {
            var base_url = window.location.origin;
            var url = base_url+'/admin/drdrs';

            return url;
        },
        viewDdrs()
        {
            var base_url = window.location.origin;
            var url = base_url+'/admin/ddrs';

            return url;
        },
        viewNcn()
        {
            var base_url = window.location.origin;
            var url = base_url+'/admin/ncns';

            return url;
        },
        viewCcir()
        {
            var base_url = window.location.origin;
            var url = base_url+'/admin/ccirs';

            return url;
        }
    }
}
</script>
