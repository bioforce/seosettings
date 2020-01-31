<template>
    <items-list :model="model" :columns="columns" :fillable="fillable" :api-url="apiUrl" :can-delete="canDelete" :addButtonText="addButtonText"></items-list>
</template>
<script>
import axios from 'axios'
import ItemsList from '../components/Item/List.vue'

export default {
    name: 'ItemsPage',
    props: ['addButtonText', 'model', 'fillable', 'apiUrl', 'canDelete', 'columns'],
    components: {
        'items-list': ItemsList
    },
    data() {
        return {
            test: true,
            selectedRoute: ''
        }
    },
    methods: {
    }
}
</script>
