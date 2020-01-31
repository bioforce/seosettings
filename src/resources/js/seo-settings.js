require('./bootstrap')

import VModal from 'vue-js-modal'

Vue.use(VModal, {
    dynamic: true,
    dialog: true
})

Vue.component('routes-page', require('./pages/Routes.vue').default)
Vue.component('items-page', require('./pages/Items.vue').default)
