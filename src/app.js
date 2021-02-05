import Vue from 'vue'
require('./bootstrap')
import './assets/css/styles.css'

import router from './router'
import App from './App.vue'
import store from './store'
import filters from './utils/filter'
import * as types from './store/mutation-types'

import titleMixin from './utils/titleMixin'
Vue.mixin(titleMixin)

import VueSocialSharing from 'vue-social-sharing'
Vue.use(VueSocialSharing)

import vueStickySidebar from "vue-sticky-sidebar";
Vue.use(vueStickySidebar)

import VueLazyload from 'vue-lazyload'
Vue.use(VueLazyload, {
  preLoad: 1.3,
  error: 'https://bkd.banjarmasinkota.go.id/wp-content/themes/evolve/library/media/images/no-thumbnail.jpg',
  loading: 'https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/6d391369321565.5b7d0d570e829.gif',
  attempt: 2
})

import VueMeta from 'vue-meta'
Vue.use(VueMeta)

new Vue({
  el: '#app',
  filters,
  store,
  router,
  render: (h) => h(App),
  created() {
    this.$store.commit(types.RESET_LOADING_PROGRESS)
    // this.$store.dispatch('getAllCategories')
    // this.$store.dispatch('getAllPages')
  },
})
