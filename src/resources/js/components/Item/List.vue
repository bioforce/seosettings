<template>
    <div>
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" @click.prevent="showCreateForm()">{{addButtonText}}</button>
            </div>
            <div class="table-responsive card-body">
                <table id="example1" class="table table-hover w-100 table-sm">
                    <thead v-if="!loading">
                        <tr class="bg-light rounded">
                            <th v-for="(field, index) in fillable" :key="index">{{field.name}}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <item-list-item
                            v-for="(item,index) in items"
                            :key="index"
                            :item="item"
                            :columns="columns"
                            :model="model"
                            :apiUrl="apiUrl"
                            :can-delete="canDelete"
                            v-on:update="fetchItems"
                            v-on:show:edit="showEditForm(item)">
                        </item-list-item>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center" v-if="loading">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <pagination v-if="pagination.last_page > 1 && !loading" :pagination="pagination" :offset="5" @paginate="fetchItems()"></pagination>
        <v-dialog/>
        <item-modal-form :model="model" :fillable="fillable" :api-url="apiUrl" ref="form" v-on:update="fetchItems"></item-modal-form>
    </div>
</template>
<script>
import axios from 'axios'
import ItemListItem from './ListItem.vue'
import Pagination from '../Pagination.vue'
import ItemForm from './Form.vue'

export default {
    name: 'ItemsList',
    components: {
        'item-list-item': ItemListItem,
        'pagination': Pagination,
        'item-modal-form': ItemForm
    },
    props: ['model', 'fillable', 'apiUrl', 'canDelete', 'columns', 'addButtonText'],
    created() {
    },
    data() {
        return {
            pagination: {
                'current_page': 1
            },
            items: [
            ],
            loading: false,
            selectedItem: {}
        }
    },
    methods: {
        fetchItems() {
            this.loading = true
            this.items = []
            var urlGet = this.apiUrl+'?page=' + this.pagination.current_page
            axios.get(urlGet)
                .then(response => {
                    this.items = response.data.data
                    let {data, ...pagination} = response.data
                    this.pagination = pagination
                    this.loading = false
                })
                .catch(error => {
                    this.loading = false
                    console.log(error.response.data)
                });
        },
        showCreateForm()
        {
            this.$refs.form.updateFields(false)
            this.$modal.show('modal-create-item')
        },
        showEditForm(item)
        {
            this.$refs.form.updateFields(item.id)
            this.$modal.show('modal-create-item')
        }
    },
    mounted() {
        this.fetchItems()
    }
}
</script>
