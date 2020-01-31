<template>
    <div>
        <alert :message="'Message'" v-if="message"></alert>
        <routes-list :path="path" :urlMap="urlMap"></routes-list>
    </div>
</template>
<script>
import axios from 'axios'
import RoutesList from '../components/Routes/List.vue'

export default {
    name: 'RoutersPage',
    props: ['routes', 'list', 'path'],
    components: {
        'routes-list': RoutesList
    },
    data() {
        return {
            urlMap: {
                routes: '/' + this.path + '/seo?get',
                settings: '/' + this.path + '/seo/settings',
                groups: '/' + this.path + '/seo/groups?get',
                models: '/' + this.path + '/seo?models'
            },
            message: false
        }
    },
    methods: {
        updateRoute(value)
        {
            this.selectedRoute = value
        },
        addRoute()
        {
            this.$modal.show('modal-create-item')
        }
    }
}
</script>
