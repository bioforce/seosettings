<template>
    <!-- Create Group Modal -->
    <modal :name="modalName" :resizable="false" :height="'auto'" :scrollable="true" :adaptive="true">
        <div class="">
            <div class="modal-header">
                <h4 class="modal-title" v-if="create">
                    Create Item
                </h4>
                <h4 class="modal-title" v-else>
                    Edit Item
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
                <form class="form-horizontal" role="form" v-if="initial">
                    <!-- Fields -->
                    <item-form-group v-for="(field, index) in fields"
                        :key="index"
                        :field="field"
                        :value.sync="inputs[field.code]"
                        ></item-form-group>
                </form>
                <div class="d-flex justify-content-center" v-else>
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closemodal(modalName)">Close</button>

                <button type="button" class="btn btn-primary" @click="store" v-if="create" :disabled="loading">
                    Create
                </button>
                <button type="button" class="btn btn-primary" @click="edit" v-else :disabled="loading">
                    Edit
                </button>
            </div>
        </div>
    </modal>
</template>
<script>
import axios from 'axios'
import VModal from 'vue-js-modal'
import ItemInputGroup from './Inputs/Group.vue'

export default {
    name: 'ItemForm',
    props: ['fillable', 'apiUrl', 'item'],
    components: {
        'item-form-group': ItemInputGroup
    },
    data() {
        return {
            inputs: {},
            fields: this.fillableFields(),
            errors: [],
            modalName: 'modal-create-item',
            create: true,
            loading: false,
            initial: false
        }
    },
    methods: {
        fillableFields()
        {
            return this.fillable.filter(function (field) {
                return field.editable === true
            })
        },
        /**
         * Create a new item.
         */
        store() {
            this.loading = true
            this.errors = []
            axios.post(this.apiUrl, this.inputs)
                .then(response => {
                    this.$emit('update')
                    this.closemodal(this.modalName)
                })
                .catch(error => {
                    this.loading = false
                    if (typeof error.response.data === 'object') {
                        this.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.errors = ['Something went wrong. Please try again.'];
                    }
                });
        },

        /**
         * Create a new item.
         */
        edit() {
            this.loading = true
            this.errors = []
            axios.put(this.apiUrl + '/' + this.inputs.id, this.inputs)
                .then(response => {
                    this.$emit('update')
                    this.closemodal(this.modalName)
                })
                .catch(error => {
                    this.loading = false
                    if (typeof error.response.data === 'object') {
                        this.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.errors = ['Something went wrong. Please try again.'];
                    }
                });
        },
        /**
         * Get item to edit.
         */
        get(id) {
            this.errors = []
            axios.get(this.apiUrl + '/' + id + '/edit')
                .then(response => {
                    this.inputs = response.data
                    this.initial = true
                })
                .catch(error => {
                    this.loading = false
                    if (typeof error.response.data === 'object') {
                        this.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.errors = ['Something went wrong. Please try again.'];
                    }
                });
        },
        /**
         * Close modal
         */
        closemodal(name) {
            this.loading = false
            this.inputs = {}
            this.$modal.hide(name)
        },
        updateFields(v)
        {
            this.inputs = {}
            this.initial = false
            if (v == false)
            {
                this.create = true
                this.initial = true
            }
            else
            {
                this.create = false
                this.get(v)
            }
        }
    }
}
</script>
