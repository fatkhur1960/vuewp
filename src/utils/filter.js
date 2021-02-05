import Vue from 'vue'

const truncate = Vue.filter('truncate', function (text, stop, clamp) {
  return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '')
})

export default {
  truncate
}
