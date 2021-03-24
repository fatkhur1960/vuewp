import api from '../../api'
import * as types from '../mutation-types'

// initial state
const state = {
  items: [],
  loaded: false,
}

// getters
const getters = {
  widgetItems: (state) => () => {
    return state.items
  },

  widgetItemsLoaded: (state) => state.loaded,
}

// actions
const actions = {
  getWidget({ commit }, { id }) {
    api.getWidget(id, (widgets) => {
      commit(types.STORE_FETCHED_WIDGETS, { widgets })
      commit(types.WIDGETS_LOADED, true)
      commit(types.INCREMENT_LOADING_PROGRESS)
    })
  },
}

// mutations
const mutations = {
  [types.STORE_FETCHED_WIDGETS](state, { widgets }) {
    state.items = widgets
  },

  [types.WIDGETS_LOADED](state, val) {
    state.loaded = val
  },
}

export default {
  state,
  getters,
  actions,
  mutations,
}
