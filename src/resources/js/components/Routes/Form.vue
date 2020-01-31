<template>
    <div class="container">
        <!-- Create Tags for Route modal -->
        <modal :name="modalName" :resizable="true" :height="'auto'" :scrollable="true" :adaptive="true" :width="'100%'" :maxWidth="1140">
            <div class="dialog-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Create Tags for route
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closemodal(modalName)">&times;</button>
                </div>

                <div class="modal-body">
                    <!-- Form Errors -->
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <p><strong>Whoops!</strong> Something went wrong!</p>
                        <br>
                        <ul>
                            <li v-for="(error, index) in errors" :key="index">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Create Item Form -->
                    <form class="form-horizontal" role="form" v-if="!initial && groups.length">
                        <h4>Select model</h4>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Model</label>
                                <div class="">
                                    <select class="form-control" v-model="inputs.model">
                                        <option value="">No model</option>
                                        <option v-for="(model, index) in models" :value="index" :key="index">{{index}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6" v-if="inputs.model">
                                <label for="inputPassword4">Can use</label>
                                <div class="">
                                    <span class="badge badge-pill badge-primary mr-1" v-for="(field, index) in models[inputs.model]" :key="index">{{field}}</span>
                                </div>
                                <small class="form-text text-muted" v-if="models[inputs.model] && models[inputs.model][0]">Use in {}. For example: {{'{'+models[inputs.model][0]+'}'}}</small>
                            </div>
                        </div>
                        <div v-for="group in groups" :key="group.id">
                            <h4>{{group.name}}</h4>
                            <!-- Fields -->
                            <div class="form-row">
                                <div class="form-group col-md-6" v-for="tag in group.tags" :key="tag.id">
                                    <label class="control-label" v-if="tag.editable">{{tag.name}}</label>

                                    <div class="" v-if="tag.editable">
                                        <input
                                            :id="'create-item-'+group.code+'-'+tag.id"
                                            type="text" class="form-control"
                                            @keyup.enter="store"
                                            v-model="inputs.values[tag.id]"
                                            >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-center" v-if="initial">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" @click="closemodal(modalName)">Close</button>

                    <button type="button" class="btn btn-primary" @click="store" :disabled="loading || initial">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
                        <span v-if="create">Create</span>
                        <span v-else>Edit</span>
                    </button>
                </div>
            </div>
        </modal>
    </div>
</template>
<script>
import axios from 'axios'
import VModal from 'vue-js-modal'

export default {
    name: 'RouteForm',
    props: ['uri', 'urlMap'],
    components: {
    },
    data() {
        return {
            inputs: {
                uri: this.uri,
                values: {},
                model: ''
            },
            errors: [],
            modalName: 'modal-create-item',
            loading: false,
            initial: true,
            groups: [],
            models: {},
            create: true
        }
    },
    methods: {
        /**
         * Restore default values for data
         */
        restoreDataToDefault()
        {
            this.loading = false
            this.initial = true
            this.inputs = {
                uri: '',
                values: {},
                model: ''
            }
            this.groups = {}
            this.errors = []
        },
        /**
         * Set loading true and null for errors
         */
        setValuesBeforEvent()
        {
            this.loading = true
            this.errors = []
        },
        /**
         * Create a new or edit exist setting for the route.
         */
        store() {
            this.setValuesBeforEvent()
            this.inputs.uri = this.uri
            var method = 'post'
            var postUri = this.urlMap.settings
            if (this.inputs.id)
            {
                postUri = this.urlMap.settings + '/' + this.inputs.id
                method = 'put'
            }
            axios[method](postUri, this.inputs)
                .then(response => {
                    this.$emit('update')
                    this.closemodal(this.modalName)
                })
                .catch(error => {
                    this.showErrors(error)
                });
        },
        /**
         * Close modal
         */
        closemodal(name) {
            this.restoreDataToDefault()
            this.$modal.hide(name)
        },
        getGroups() {
            this.groups = []
            axios.get(this.urlMap.groups)
                .then(response => {
                    this.groups = response.data
                    this.getModels()
                })
                .catch(error => {
                    this.showErrors(error)
                });
        },
        getCurrenParams(id) {
            axios.get(this.urlMap.settings + "/" + id)
                .then(response => {
                    this.inputs.id = id
                    this.inputs.uri = response.data.uri
                    this.inputs.model = response.data.model
                    this.inputs.values = {}
                    let newValues = {};
                    $.each(response.data.tags, function(key, item) {
                        //this.$set(this.inputs.values, item.id, item.pivot.value)
                        newValues[item.id] = item.pivot.value
                    });
                    this.inputs.values = newValues
                    this.getGroups()
                })
                .catch(error => {
                    this.showErrors(error)
                });
        },
        showErrors(error) {
            this.initial = false
            this.loading = false
            if (error.response.data.message)
            {
                this.errors = [error.response.data.message]
                return false
            }
            if (typeof error.response.data.errors === 'object') {
                this.errors = _.flatten(_.toArray(error.response.data.errors));
            } else {
                if (typeof error.response.data === 'object') {
                    this.errors = _.flatten(_.toArray(error.response.data));
                } else {
                    this.errors = ['Something went wrong. Please try again.'];
                }
            }
        },
        getModels() {
            this.models = {}
            axios.get(this.urlMap.models)
                .then(response => {
                    this.initial = false
                    this.models = response.data
                })
                .catch(error => {
                    this.initial = false
                    this.showErrors(error)
                });
        },
        updateFields(item)
        {
            this.initial = true
            this.create = true
            if (item.is_filled)
            {
                this.create = false
                this.getCurrenParams(item.is_filled.id)
            }
            else
            {
                this.getGroups()
            }
        }
    }
}
</script>
