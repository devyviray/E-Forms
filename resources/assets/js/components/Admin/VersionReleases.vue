<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button class="hidden-xs btn btn-new btn-wd btn-neutral btn-round mb-2" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));"  @click="cleanData" data-toggle="modal" data-target="#addModal">Add Version Release</button>
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header ">
                        <h4 class="card-title">Version Releases
                            <pre>{{ versionreleases }}</pre>
                        </h4>
                    </div>

                    <content-placeholders v-if="loading">
                        <content-placeholders-heading :img="true" />
                        <content-placeholders-text :lines="3" />
                    </content-placeholders>

                    <div class="card-body table-full-width table-responsive" v-if="versionreleases.length">
                        <div class="row mb-4 ml-2">
                            <div class="col-md-12">
                                <label for="name">Search by Version Release</label>
                                <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                            </div>
                        </div>
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Version Release</th>
                                <th>Created By</th>
                                <th>Updates</th>
                                <th>Fixes</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="versionrelease in filteredVersionReleases" :key="versionrelease.id">
                                    <td>{{ versionrelease.id }}</td>
                                    <td>{{ versionrelease.version }}</td>
                                    <td>{{ versionrelease.user_id }}</td>
                                    <td>{{ versionrelease.updates }}</td>
                                    <td>{{ versionrelease.fixes }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Option
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="javascript:void(0)" @click="copyObject(versionrelease)" data-toggle="modal" :data-target="`#editModal-${versionrelease.id}`">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" :data-target="`#deleteModal-${versionrelease.id}`">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3" v-if="versionreleases.length">
                        <div class="col-6">
                            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                            <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                            <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                        </div>
                        <div class="col-6 text-right">
                            <span>{{ versionreleases.length }} Version Release(s)</span>
                        </div>
                    </div>
                    <div class="card-body table-full-width table-responsive" v-if="!versionreleases.length && !loading">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Version Release</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>No data available in the table</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addVersionReleaseLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVersionReleaseLabel">Add Version Release</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="version">Version</label>
                            <input type="text" class="form-control" placeholder="Version" v-model="versionrelease.version" id="version">
                            <span class="error" v-if="errors.version">{{ errors.version[0] }}</span>
                        </div>
                        <div class="form-group">
                            <label for="updates">Updates</label>
                            <textarea class="form-control" placeholder="Updates" v-model="versionrelease.updates" id="updates"></textarea>
                            <span class="error" v-if="errors.updates">{{ errors.updates[0] }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fixes">Fixes</label>
                            <textarea class="form-control" placeholder="Fixes" v-model="versionrelease.fixes" id="fixes"></textarea>
                            <span class="error" v-if="errors.fixes">{{ errors.fixes[0] }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                        <button @click="addVersionRelease(versionrelease)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Update Modal -->
        <div v-for="(versionrelease, d) in filteredVersionReleases" :key="'list-' +  d" class="modal fade" :id="`editModal-${versionrelease.id}`" tabindex="-1" role="dialog" aria-labelledby="editVersionReleaseLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit Version Release</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="copiedObject.id">
                    <div class="form-group">
                        <label for="version">Version</label>
                        <input type="text" class="form-control" placeholder="Version" v-model="copiedObject.version" id="version">
                         <span class="error" v-if="errors.version">{{ errors.version[0] }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="editVersionRelease(copiedObject)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Update</button>
                </div>
            </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-for="(versionrelease, d) in filteredVersionReleases" :key="d" class="modal fade" :id="`deleteModal-${versionrelease.id}`" tabindex="-1" role="dialog" aria-labelledby="deleteVersionReleaseLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteVersionReleaseLabel">Delete Version Release</h5>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="versionrelease.id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this version release?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                    <button @click="deleteVersionRelease(versionrelease.id)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueContentPlaceholders from 'vue-content-placeholders';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);
export default {
    components:{
        VueContentPlaceholders
    },
    data(){
        return{
            versionreleases: [],
            versionrelease: {
                id: '',
                version: '',
                updates: '',
                fixes: '',
                user_id: ''
            },
            copiedObject: [],
            pagination: {},
            edit: false,
            keywords: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
        }
    },
    created(){
        this.fetchVersionReleases();
    },
    methods: {
        copyObject(versionrelease){
            this.copiedObject = Object.assign({}, versionrelease)
        },
        getCurrentUserId() {
        // Logic to get the current user ID
        // You can fetch it from a global state (Vuex, for example)
        // Or you can directly get it from an element or a global variable
        return window.user_id || this.$store.state.user.id; // If you're using Vuex
    },
        cleanData(){
            this.errors = ' ';
            this.versionrelease.version = ' ';
            this.versionrelease.updates = ' ';
            this.versionrelease.fixes = ' ';
        },
        fetchVersionReleases(){
            this.loading = true;
            axios.get('/versionreleases')    
            .then(response => {
                this.versionreleases = response.data;
                this.loading = false;
            });
        },
        addVersionRelease(versionrelease){
            axios.post('/versionrelease', {
                version: versionrelease.version,
                updates: versionrelease.updates,
                fixes: versionrelease.fixes,
                user_id: this.getCurrentUserId()  // Make sure to pass the user ID here
            })
            .then(response => {
                this.versionrelease.version = '';
                this.versionrelease.updates = '';
                this.versionrelease.fixes = '';
                this.errors = [];
                $('#addModal').modal('hide');
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Version Release Successfully Added',
                    position: 'top right'
                });
                this.versionreleases = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteVersionRelease(id){
            let versionreleaseIndex = this.versionreleases.findIndex(item => item.id == id);
            $('#deleteModal-'+id).modal('hide');
            axios.delete(`/versionrelease/${id}`)
            .then(response => {
                this.versionreleases.splice(versionreleaseIndex, 1);
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Version Release Successfully Deleted',
                    position: 'top right'
                });
            })
            .catch(error => {
                this.errors = error.response.data.error;
            });
        },
        editVersionRelease(versionrelease){
            let versionreleaseIndex = this.versionreleases.findIndex(item => item.id == versionrelease.id);
            axios.patch(`/versionrelease/${versionrelease.id}`, {
                id: versionrelease.id,
                version: versionrelease.version,
                updates: versionrelease.updates,
                fixes: versionrelease.fixes
            })
            .then(response => {
                this.errors = [];
                this.versionreleases.splice(versionreleaseIndex, 1, response.data);
                $('#editModal-'+versionrelease.id).modal('hide');
                this.$toast.success({
                    title:'SUCCESS',
                    message:'Version Release Successfully Edited',
                    position: 'top right'
                });
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },

        resetStartRow() {
            this.currentPage = 0;
        },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }
    },
    computed: {
        filteredVersionReleases(){
            let self = this;
            return self.versionreleases.filter(versionrelease => {
                return versionrelease.version.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(this.filteredVersionReleases.length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var versionreleasesArray = this.filteredVersionReleases.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return versionreleasesArray;
        },
    }
}
</script>
