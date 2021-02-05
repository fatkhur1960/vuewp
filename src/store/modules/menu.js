import api from '../../api'
import * as types from '../mutation-types'

// initial state
const state = {
  items: [],
  loaded: false,
}

// getters
const getters = {
  menuItems: (state) => () => {
    return state.items
  },

  menuItemsLoaded: (state) => state.loaded,
}

// actions
const actions = {
  getMenus({ commit }) {
    api.getMenus((menus) => {
      commit(types.STORE_FETCHED_MENUS, { menus })
      commit(types.MENUS_LOADED, true)
      commit(types.INCREMENT_LOADING_PROGRESS)
    })
  },
}

// mutations
const mutations = {
  [types.STORE_FETCHED_MENUS](state, { menus }) {
    state.items = menus
  },

  [types.MENUS_LOADED](state, val) {
    state.loaded = val
  },
}

export default {
  state,
  getters,
  actions,
  mutations,
}
