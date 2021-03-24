import api from '../../api'
import * as types from '../mutation-types'

const createPostSlug = (post) => {
  let slug = post.link.replace(
    window.location.protocol + '//' + window.location.host,
    '',
  )
  post.slug = slug
  return post
}

// initial state
const state = {
  recent: [],
  loaded: false,
  loading: false,
  reachedMax: false,
  postDetail: false,
  detailLoading: false,
}

// getters
const getters = {
  recentPosts: (state) => (limit) => {
    if (!limit || !Number.isInteger(limit) || typeof limit == 'undefined') {
      return state.recent
    }
    let recent = state.recent
    return recent.slice(0, limit)
  },
  recentPostsLoaded: (state) => state.loaded,
  postsLoading: (state) => state.loading,
  hasReachedMax: (state) => state.reachedMax,

  // detail post
  postDetail: (state) => state.postDetail,
  postDetailLoading: (state) => state.detailLoading,
}

// actions
const actions = {
  init({ commit }) {
    commit(types.POSTS_LOADED, false)
    commit(types.POST_DETAIL_LOADING, true)
  },
  getPosts({ commit }, { limit, page, filter }) {
    commit(types.POSTS_LOADED, false)
    api.getPosts(limit, page, filter, (posts) => {
      commit(types.STORE_FETCHED_POSTS, { posts })
      commit(types.POSTS_LOADED, true)
      commit(types.POSTS_LOADING, false)
      commit(types.POSTS_REACHED_MAX, false)
      commit(types.INCREMENT_LOADING_PROGRESS)
    })
  },
  loadMorePosts({ commit }, { limit, page, filter }) {
    commit(types.POSTS_LOADING, true)
    api.getPosts(limit, page, filter, (posts) => {
      if (posts) {
        commit(types.POSTS_REACHED_MAX, false)
        commit(types.STORE_FETCHED_MORE_POSTS, { posts })
        commit(types.POSTS_LOADING, false)
      } else {
        commit(types.POSTS_REACHED_MAX, true)
        commit(types.POSTS_LOADING, false)
      }
    })
  },
  getPostDetail({ commit }, { slug }) {
    commit(types.POST_DETAIL_LOADING, true)
    api.getPostDetail(slug, (post) => {
      if (post) {
        commit(types.POST_LOADED, { post })
      }
      commit(types.POST_DETAIL_LOADING, false)
    })
  },
}

// mutations
const mutations = {
  [types.STORE_FETCHED_POSTS](state, { posts }) {
    state.recent = posts
  },

  [types.STORE_FETCHED_MORE_POSTS](state, { posts }) {
    state.recent = state.recent.concat(posts)
  },

  [types.POSTS_LOADED](state, val) {
    state.loaded = val
  },

  [types.POST_LOADED](state, { post }) {
    state.postDetail = post
  },

  [types.POST_DETAIL_LOADING](state, val) {
    state.detailLoading = val
  },

  [types.POSTS_LOADING](state, val) {
    state.loading = val
  },

  [types.POSTS_REACHED_MAX](state, val) {
    state.reachedMax = val
  },
}

export default {
  state,
  getters,
  actions,
  mutations,
}
