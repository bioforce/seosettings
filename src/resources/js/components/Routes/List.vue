<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <nav class="nav">
                        <a :class="[filled > 1 ? 'nav-link active text-secondary' : 'nav-link']" href="#" @click="filledSet(2)">All routes</a>
                        <a :class="[filled == 1 ? 'nav-link active text-secondary' : 'nav-link']" href="#" @click="filledSet(1)">Filled</a>
                        <a :class="[filled == 0 ? 'nav-link active text-secondary' : 'nav-link']" href="#" @click="filledSet(0)">Not filled</a>
                    </nav>
                </div>
                <div class="col-sm-12 col-md-6">
                    <input v-model="search" placeholder="Search" type="text" class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="table-responsive card-body">
            <table class="table table-hover w-100 table-sm" v-if="!loading">
                <thead>
                    <tr class="d-flex bg-light rounded">
                        <th scope="col" class="col-8">Route/Name</th>
                        <th scope="col" class="col-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="(route, index) in items" :key="index" class="d-flex">
                    <td scope="row" class="col-8">{{route.route}}<br/>{{route.route_name}}</td>
                    <td nowrap class="col-4 align-middle d-flex">
                        <a class="btn btn-success align-self-center btn-sm mr-2" href="#" role="button" @click="showEditForm(route)" v-if="route.is_filled">Edit</a>
                        <a class="btn btn-primary btn-sm  align-self-center" href="#" role="button" @click="showEditForm(route)" v-else>Create</a>
                        <a href="#" class="btn btn-danger btn-sm align-self-center" @click="deleteFilledRoute(route)" v-if="route.is_filled">Delete</a>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-center" v-if="loading">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <routes-modal-form
            :uri.sync="uri"
            :urlMap="urlMap"
            v-on:update="getRoutes"
            ref="form">
        </routes-modal-form>
        <v-dialog/>
    </div>
</template>
<script>
import RouteForm from './Form.vue'

export default {
    name: 'RoutesList',
    props: ['path', 'urlMap'],
    data() {
        return {
            routes: [],
            search: '',
            loading: true,
            uri: '',
            filled: 1
        }
    },
    components: {
        'routes-modal-form': RouteForm
    },
    methods: {
        filledSet(v) {
            this.filled = v
        },
        getRoutes() {
            this.loading = true
            this.routes = []
            axios.get(this.urlMap.routes)
                .then(response => {
                    this.loading = false
                    this.routes = response.data
                })
                .catch(error => {
                    this.loading = false
                    console.log(error)
                });
        },
        showEditForm(item)
        {
            this.uri = item.route
            this.$refs.form.updateFields(item)
            this.$modal.show('modal-create-item')
        },
        deleteFilledRoute(item){
            this.$modal.show('dialog', {
                title: 'Delete!',
                text: 'Confirm delete '+ item.route,
                buttons: [
                    {
                        title: 'Yes',
                        handler: () => {
                            axios.delete(this.urlMap.settings + '/' +  item.is_filled.id)
                            .then(response => {
                                this.getRoutes()
                                this.$modal.hide('dialog')
                            })
                            .catch(error => {
                                if (typeof error.response.data === 'object') {
                                    this.errors = _.flatten(_.toArray(error.response.data.errors));
                                } else {
                                    this.errors = ['Something went wrong. Please try again.'];
                                }
                            });
                        }
                    },
                    {
                        title: 'No',
                        default: true
                    }
                ]
            })
        }
    },
    mounted() {
        this.getRoutes()
    },
    computed: {
        items () {
            var returnItems = this.routes
            if (this.search)
            {
                returnItems = returnItems.filter(item =>
                item.route.includes(this.search) ||
                (item.route_name && item.route_name.includes(this.search)))
            }
            if (this.filled === 1)
            {
                returnItems = returnItems.filter(item => item.is_filled)
            }
            if (this.filled === 0)
            {
                returnItems = returnItems.filter(item => !item.is_filled)
            }

            return returnItems
        }
    }
}
</script>
