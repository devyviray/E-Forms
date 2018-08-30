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
                                        <td>Document review & Distribution request</td>
                                        <td>{{ drdr.length }}</td>
                                    </tr>
                                    <tr>
                                        <td>Document distribution request</td>
                                        <td>{{ ddr.length }}</td>
                                    </tr>
                                    <tr>
                                        <td>Non-conformance notification</td>
                                        <td>{{ ncn.length }}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer complaint information report</td>
                                        <td>{{ ccir.length }}</td>
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
            drdr: [],
            ddr: [],
            ccir: [],
            ncn: []
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
            axios.get('/drdr-count')
                .then(response => {
                    this.drdr = response.data;
                })
                .catch(error => console.log(errors));  
        },
        fetchDdr(){
            axios.get('/ddr')
                .then(response => {
                    this.ddr = response.data;
                })
                .catch(error => console.log(errors));  
        },
        fetchCcir(){
            axios.get('/ccir')
                .then(response => {
                    this.ccir = response.data;
                })
                .catch(error => console.log(errors));  
        },
        fetchNcn(){
            axios.get('/ncn')
                .then(response => {
                    this.ncn = response.data;
                })
                .catch(error => console.log(errors));  
        }
    }   
}
</script>
