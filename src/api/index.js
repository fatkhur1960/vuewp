import axios from 'axios'
import SETTINGS from '../settings'

export default {
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
        cb(e)
      })
  },

  getMenus(cb) {
    axios
      .get(SETTINGS.API_MENU_BASE_PATH)
      .then((response) => {
        cb(response.data.items)
      })
      .catch((e) => {
        cb(e)
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
        cb(e)
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

  getPosts(limit = 5, filter = '', cb) {
    if (filter != '') {
      filter = `&filter${filter}`
    }
    axios
      .get(SETTINGS.API_BASE_PATH + `posts?per_page=${limit}${filter}`)
      .then((response) => {
        cb(response.data)
      })
      .catch((e) => {
        cb(e)
      })
  },

  validator(type, params, cb) {
    axios
      .get(
        SETTINGS.API_VALIDATOR_PATH + `${type}?${params.key}=${params.value}`,
      )
      .then((response) => {
        cb(response.data.valid)
      })
      .catch((e) => {
        cb(e)
      })
  },
}
