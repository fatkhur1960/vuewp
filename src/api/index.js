import axios from 'axios'
import SETTINGS from '../settings'

const headers = {
  headers: {
    'Content-Type': 'application/json;charset=UTF-8',
    'Access-Control-Allow-Origin': '*',
  },
}

export default {
  authLogin(username, password, cb) {
    axios
      .post(
        SETTINGS.API_AUTH_PATH + 'login',
        {
          username: username,
          password: password,
        },
        headers,
      )
      .then((res) => cb(res.data))
      .catch((e) => cb(e.response.data))
  },

  authRegister(payload, cb) {
    axios
      .post(SETTINGS.API_AUTH_PATH + 'register', payload, headers)
      .then((res) => cb(res.data))
      .catch((e) => {
        cb(e.response.data)
      })
  },

  oAuthRequest(provider, cb) {
    axios
      .get(SETTINGS.API_AUTH_PATH + `${provider}/request`)
      .then((res) => cb(res.data))
      .catch((e) => {
        cb(e)
      })
  },

  oAuthLogin(provider, payload, cb) {
    axios
      .post(SETTINGS.API_AUTH_PATH + `${provider}/login`, payload, headers)
      .then((res) => cb(res.data))
      .catch((e) => {
        cb(e)
      })
  },

  authResetPassword(payload, cb) {
    axios
      .post(SETTINGS.API_BASE_PATH + 'users/lost-password', payload, headers)
      .then((res) => cb(res.data))
      .catch((e) => {
        cb(e.response.data)
      })
  },

  getCategories(cb) {
    axios
      .get(
        SETTINGS.API_BASE_PATH +
          'categories?sort=name&hide_empty=true&per_page=50',
      )
      .then((response) => {
        cb(response.data.filter((c) => c.name !== 'Uncategorized'))
      })
      .catch((e) => {
        cb(e.response.data)
      })
  },

  getMenus(cb) {
    axios
      .get(SETTINGS.API_MENU_BASE_PATH)
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(e.response.data)
      })
  },

  getSlides(limit = 5, cb) {
    axios
      .get(SETTINGS.API_BASE_PATH + `slides?per_page=${limit}`)
      .then((response) => {
        const append = response.data.map((result) => ({
          title: result.title.rendered,
          content: result.content.rendered,
          image: result.image,
        }))
        cb(append)
      })
      .catch((e) => {
        cb(e.response.data)
      })
  },

  getPages(cb) {
    axios
      .get(SETTINGS.API_BASE_PATH + 'pages?per_page=10')
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(e)
      })
  },

  getAuthor(id, cb) {
    axios
      .get(SETTINGS.API_BASE_PATH + 'users/' + id)
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(e)
      })
  },

  getWidget(id, cb) {
    axios
      .get(SETTINGS.API_WIDGET_PATH + 'get?id=' + id)
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(e)
      })
  },

  getPage(id, cb) {
    if (!Number.isInteger(id) || !id) return false

    axios
      .get(SETTINGS.API_BASE_PATH + 'pages/' + id)
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(e)
      })
  },

  getPosts(limit = 5, page = 1, filter = '', cb) {
    if (filter != '') {
      filter = `&filter${filter}`
    }
    axios
      .get(
        SETTINGS.API_BASE_PATH +
          `posts?per_page=${limit}&page=${page}${filter}`,
      )
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(false)
      })
  },

  getPostDetail(slug, cb) {
    axios
      .get(SETTINGS.API_BASE_PATH + 'posts?slug=' + slug)
      .then((response) => {
        cb(response.data[0])
      })
      .catch((e) => {
        cb(false)
      })
  },

  validator(type, params, cb) {
    axios
      .get(
        SETTINGS.API_VALIDATOR_PATH + `${type}?${params.key}=${params.value}`,
      )
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(false)
      })
  },
}
