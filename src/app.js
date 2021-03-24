import Vue from 'vue'
require('./bootstrap')
import './assets/css/styles.css'

import router from './router'
import App from './App.vue'
import store from './store'
import filters from './utils/filter'
import * as types from './store/mutation-types'

import VueSocialSharing from 'vue-social-sharing'
Vue.use(VueSocialSharing)

import vueStickySidebar from 'vue-sticky-sidebar'
Vue.use(vueStickySidebar)

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

import VueNoty from 'vuejs-noty'
Vue.use(VueNoty, {
  timeout: 4000,
  progressBar: true,
  layout: 'topRight',
})
import 'vuejs-noty/dist/vuejs-noty.css'

import VueLazyload from 'vue-lazyload'
Vue.use(VueLazyload, {
  preLoad: 1.3,
  error:
    'https://bkd.banjarmasinkota.go.id/wp-content/themes/evolve/library/media/images/no-thumbnail.jpg',
  loading:
    'https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/6d391369321565.5b7d0d570e829.gif',
  attempt: 2,
})

new Vue({
  el: '#app',
  filters,
  store,
  router,
  render: (h) => h(App),
  created() {
    this.$store.commit(types.RESET_LOADING_PROGRESS)
    this.$store.dispatch('getMenus')
    this.$store.dispatch('getSlides', { limit: 5 })
  },
})
