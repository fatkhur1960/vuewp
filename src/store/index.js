import Vue from 'vue'
import Vuex from 'vuex'
import createPersist from 'vuex-localstorage'
import * as actions from './actions'
import * as getters from './getters'
import hub from './modules/hub'
import user from './modules/user'
import post from './modules/post'
import page from './modules/page'
import menu from './modules/menu'
import slide from './modules/slide'
import widget from './modules/widget'
import categories from './modules/categories'
import auth from './modules/auth'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

let localStorage = createPersist({
  namespace: 'YOUR_APP_NAMESPACE',
  initialState: {},
  expires: 1.21e9, // Two Weeks
})

export default new Vuex.Store({
  actions,
  getters,
  modules: {
    hub,
    user,
    post,
    page,
    categories,
    menu,
    slide,
    widget,
    auth,
  },
  strict: debug,
  plugins: [localStorage],
})
