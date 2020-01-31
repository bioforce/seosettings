<template>
    <select :id="'create-item-'+field.code" v-model="valueLocal" class="form-control" @change="$emit('update:value', valueLocal)">
        <option v-if="loading" class="spinner-border"></option>
        <option v-for="(option, index) in options" :key="index" :value="option.id">{{option.name}}</option>
    </select>
</template>
<script>
export default {
    name: 'ModelSelect',
    props: ['value', 'field'],
    data() {
        return {
            valueLocal: this.value,
            options: [],
            loading: true
        }
    },
    methods: {
        getOptionItems() {
            this.loading = true
            this.options = []
            axios.get(this.field.modelListUrl)
                .then(response => {
                    this.loading = false
                    this.options = response.data
                })
                .catch(error => {
                    this.loading = false
                    console.log(error.response.data)
                });
        },
        showEditForm(item)
        {

        }
    },
    mounted() {
        this.getOptionItems()
    }
}
</script>
