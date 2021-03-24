<template>
  <section>
    <!-- Navigation -->
    <nav
      :class="`navbar navbar-expand-lg  navbar-custom fixed-top ${
        $route.name === 'Auth' || $route.name === 'Error404'
          ? 'navbar-light'
          : 'navbar-dark'
      }`"
    >
      <div class="container">
        <!-- Text Logo - Use this if you don't have a graphic logo -->
        <!-- <a class="navbar-brand logo-text page-scroll" href="index.html">Tivo</a> -->

        <!-- Image Logo -->
        <router-link class="navbar-brand logo-image" to="/">
          Pelajar NU Wonosobo
        </router-link>

        <!-- Mobile Menu Toggle Button -->
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarsExampleDefault"
          aria-controls="navbarsExampleDefault"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-awesome fas fa-bars"></span>
          <span class="navbar-toggler-awesome fas fa-times"></span>
        </button>
        <!-- end of mobile menu toggle button -->

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav ml-auto" v-if="menuItemsLoaded">
            <template v-for="item in menuItems()">
              <li
                :key="item.id"
                :class="item.children ? 'nav-item dropdown' : 'nav-item'"
              >
                <router-link
                  v-if="item.type == 'custom'"
                  :class="
                    item.children
                      ? 'nav-link dropdown-toggle'
                      : 'nav-link page-scroll'
                  "
                  :id="item.children ? `navbarDropdown` : ''"
                  role="button"
                  :aria-haspopup="item.children && item.children.length > 0"
                  :aria-expanded="item.children ? 'false' : ''"
                  :to="parseNav(item.url)"
                >
                  {{ item.title }}
                </router-link>
                <router-link
                  v-else-if="item.type == 'taxonomy'"
                  :class="
                    item.children
                      ? 'nav-link dropdown-toggle'
                      : 'nav-link page-scroll'
                  "
                  :id="item.children ? `navbarDropdown` : ''"
                  role="button"
                  :aria-haspopup="item.children && item.children.length > 0"
                  :aria-expanded="item.children ? 'false' : ''"
                  :to="{
                    name: 'Categories',
                    params: {
                      catSlug: parseUrl(item.url),
                      catName: item.title,
                    },
                  }"
                >
                  {{ item.title }}
                </router-link>
                <router-link
                  v-if="item.type == 'post_type'"
                  :class="
                    item.children
                      ? 'nav-link dropdown-toggle'
                      : 'nav-link page-scroll'
                  "
                  :id="item.children ? `navbarDropdown` : ''"
                  role="button"
                  :aria-haspopup="item.children && item.children.length > 0"
                  :aria-expanded="item.children ? 'false' : ''"
                  :to="{
                    name: 'Page',
                    params: { pageSlug: item.object_slug },
                  }"
                >
                  {{ item.title }}
                </router-link>
                <div
                  class="dropdown-menu"
                  v-if="item.children"
                  :aria-labelledby="`navbarDropdown`"
                >
                  <template v-for="sub in item.children">
                    <router-link
                      v-if="sub.type == 'custom'"
                      class="dropdown-item"
                      :to="parseNav(sub.url)"
                    >
                      {{ sub.title }}
                    </router-link>
                    <router-link
                      v-else-if="sub.type == 'taxonomy'"
                      class="dropdown-item"
                      :to="{
                        name: 'Categories',
                        params: {
                          catSlug: parseUrl(sub.url),
                          catName: sub.title,
                        },
                      }"
                    >
                      {{ sub.title }}
                    </router-link>
                    <router-link
                      v-if="sub.type == 'post_type'"
                      class="dropdown-item"
                      :to="{
                        name: 'Page',
                        params: {
                          pageSlug: sub.object_slug,
                        },
                      }"
                    >
                      <span class="item-text">{{ sub.title }}</span>
                    </router-link>
                  </template>
                </div>
              </li>
            </template>
          </ul>
          <span class="nav-item">
            <router-link
              class="btn-outline-sm"
              :to="{
                name: 'Auth',
                params: { action: 'sign-in' },
              }"
            >
              Masuk
            </router-link>
          </span>
        </div>
      </div>
      <!-- end of container -->
    </nav>
    <!-- end of navbar -->
    <!-- end of navigation -->
    <Modal />
  </section>
</template>
<script>
import { mapGetters } from 'vuex'
import SETTINGS from '../../settings'
import Modal from '../widgets/Modal'

export default {
  components: { Modal },
  beforeMount() {
    this.$store.dispatch('getMenus')
  },
  computed: {
    ...mapGetters({
      menuItems: 'menuItems',
      menuItemsLoaded: 'menuItemsLoaded',
    }),
  },
  methods: {
    parseNav(target) {
      if (this.$route.path == '/') {
        return target
      } else {
        return `/${target}`
      }
    },
    parseUrl(url) {
      const s = url.split('/')
      return s[s.length - 2]
    },
  },
}
</script>
