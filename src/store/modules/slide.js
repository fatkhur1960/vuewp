import api from '../../api'
import * as types from '../mutation-types'

// initial state
const state = {
  items: [],
  loaded: false,
}

// getters
const getters = {
  slideItems: (state) => () => {
    return state.items
  },

  slideItemsLoaded: (state) => state.loaded,
}

// actions
const actions = {
  getSlides({ commit }, { limit }) {
    api.getSlides(limit, (slides) => {
      commit(types.STORE_FETCHED_SLIDES, { slides })
      commit(types.SLIDES_LOADED, true)
      commit(types.INCREMENT_LOADING_PROGRESS)
    })
  },
}

// mutations
const mutations = {
  [types.STORE_FETCHED_SLIDES](state, { slides }) {
    state.items = slides
  },

  [types.SLIDES_LOADED](state, val) {
    state.loaded = val
  },
}

export default {
  state,
  getters,
  actions,
  mutations,
}
