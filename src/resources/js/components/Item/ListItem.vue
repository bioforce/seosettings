<template>
    <tr>
        <td v-for="(field, index) in columns" :key="index">{{tableItem[field.code]}}</td>
        <td class="text-nowrap">
            <a class="btn btn-success btn-sm" href="#" role="button" @click="showEditForm(tableItem)">Edit</a>
            <a href="#" class="btn btn-danger btn-sm" @click="deleteItem(tableItem)" v-if="canDelete">Delete</a>
        </td>
    </tr>
</template>
<script>
export default {
    name: 'FieldListItem',
    props: ['item', 'columns', 'canDelete', 'apiUrl'],
    data() {
        return {
            tableItem: {}
        }
    },
    methods: {
        showEditForm(editItem) {
            this.$emit('show:edit', editItem)
        },
        deleteItem(item)
        {
            this.$modal.show('dialog', {
                title: 'Delete!',
                text: 'Confirm delete '+ item.name,
                buttons: [
                    {
                        title: 'Yes',
                        handler: () => {
                            axios.delete(this.apiUrl + '/' +  item.id)
                            .then(response => {
                                this.$emit('update')
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
                    title: 'No',       // Button title
                    default: true
                    }
                ]
            })
        }
    },
    mounted() {
        this.tableItem = this.item
    }
}
</script>
