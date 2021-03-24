import api from '../../api'

// initial state
const state = {
  authLoading: false,
  result: {},
  registerResult: {},
  resetPasswdResult: {},

  oAuthProvider: null,
  oAuthLoading: false,
  oAuthLoaded: null,
  oAuthResult: null,
}

// getters
const getters = {
  authLoading: (state) => state.authLoading,
  authResult: (state) => state.result,
  registerResult: (state) => state.registerResult,
  resetPasswdResult: (state) => state.resetPasswdResult,

  oAuthProvider: (state) => state.oAuthProvider,
  oAuthLoading: (state) => state.oAuthLoading,
  oAuthLoaded: (state) => state.oAuthLoaded,
  oAuthResult: (state) => state.oAuthResult,
}

// actions
const actions = {
  signIn({ commit }, { username, password }) {
    commit('AUTH_LOADING', true)
    api.authLogin(username, password, (res) => {
      commit('AUTH_RESULT', { res })
      commit('AUTH_LOADING', false)
    })
  },
  signUp({ commit }, { payload }) {
    commit('AUTH_LOADING', true)
    api.authRegister(payload, (res) => {
      commit('REGISTER_RESULT', { res })
      commit('AUTH_LOADING', false)
    })
  },
  resetPassword({ commit }, { payload }) {
    commit('AUTH_LOADING', true)
    api.authResetPassword(payload, (res) => {
      commit('RESET_PASSWD_RESULT', { res })
      commit('AUTH_LOADING', false)
    })
  },

  oAuthRequest({ commit }, { provider }) {
    commit('OAUTH_LOADING', true)
    commit('OAUTH_PROVIDER', provider)
    api.oAuthRequest(provider, (res) => {
      commit('OAUTH_LOADED', { res })
    })
  },
  oAuthProcess({ commit }, { payload, provider }) {
    api.oAuthLogin(provider, payload, (res) => {
      commit('OAUTH_LOADING', false)
      commit('OAUTH_PROVIDER', null)
      commit('OAUTH_RESULT', { res })
    })
  },
  clearOAuthLoading({ commit }) {
    commit('OAUTH_LOADING', false)
    commit('OAUTH_PROVIDER', null)
  },
}

// mutations
const mutations = {
  AUTH_RESULT(state, { res }) {
    state.result = res
  },
  AUTH_LOADING(state, val) {
    state.authLoading = val
  },
  REGISTER_RESULT(state, { res }) {
    state.registerResult = res
  },
  RESET_PASSWD_RESULT(state, { res }) {
    state.resetPasswdResult = res
  },
  OAUTH_LOADING(state, val) {
    state.oAuthLoading = val
  },
  OAUTH_LOADED(state, { res }) {
    state.oAuthLoaded = res
  },
  OAUTH_RESULT(state, { res }) {
    state.oAuthResult = res
  },
  OAUTH_PROVIDER(state, val) {
    state.oAuthProvider = val
  }
}

export default {
  state,
  getters,
  actions,
  mutations,
}
