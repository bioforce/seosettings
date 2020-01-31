<template>
    <div class="form-group">
        <label class="" v-if="field.type != 'checkbox'">{{field.name}}</label>
        <input
            :id="'create-item-'+field.code"
            type="text" class="form-control"
            @keyup.enter="store"
            v-model="valueLocal"
            v-if="field.type == 'text'">

        <div class="form-check" v-else-if="field.type == 'checkbox'">
            <input class="form-check-input" type="checkbox" :id="'create-item-'+field.code" v-model="valueLocal">
            <label class="form-check-label" :for="'create-item-'+field.code">
                {{field.name}}
            </label>
        </div>

        <model-select
            :value.sync="valueLocal"
            :field="field"
            v-else-if="field.type == 'model'">
        </model-select>
    </div>
</template>
<script>
import ModelSelect from './Model.vue'

export default {
    name: 'ItemInputGroup',
    props: ['value', 'field'],
    components: {
        'model-select': ModelSelect
    },
    data() {
        return {
            valueLocal: this.value
        }
    },
    methods: {
        store()
        {
            this.$emit('store');
        }
    },
    watch: {
        valueLocal (value) {
            this.$emit('update:value', value)
        }
    }
}
</script>
