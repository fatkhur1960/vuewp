import Vue from 'vue'
import Router from 'vue-router'

import SETTINGS from '../settings'

// Components
import Error404 from '../components/Error404.vue'
import Auth from '../components/Auth/Auth.vue'
import SocialAuth from '../components/Auth/SocialAuth.vue'
import Home from '../components/Home.vue'
import Post from '../components/Post/Post.vue'
import Page from '../components/Page/Page.vue'
import AllPosts from '../components/Post/AllPosts.vue'
import Categories from '../components/Post/Categories.vue'
import Authors from '../components/Post/Authors.vue'

Vue.use(Router)

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        title: 'Beranda',
      },
    },
    {
      path: '/auth/:action',
      name: 'Auth',
      component: Auth,
      meta: {
        title: 'Masuk',
      },
    },
    {
      path: '/auth/:provider/callback',
      name: 'SocialAuth',
      component: SocialAuth,
      meta: {
        title: 'Authorizing...',
        clear: true,
      },
    },
    {
      path: '/post/:postSlug',
      name: 'Post',
      component: Post,
      meta: {
        title: 'Post',
        transitionName: 'slide',
      },
    },
    {
      path: '/news/all',
      name: 'AllPosts',
      component: AllPosts,
      meta: {
        title: 'Semua Berita',
      },
    },
    {
      path: '/category/:catSlug',
      name: 'Categories',
      component: Categories,
      meta: {
        title: 'Kategori',
      },
    },
    {
      path: '/post/author/:authSlug',
      name: 'Authors',
      component: Authors,
      meta: {
        title: 'Penulis',
      },
    },
    {
      path: '/p/:pageSlug',
      name: 'Page',
      component: Page,
      meta: {
        title: 'Page',
      },
    },
    {
      path: '*',
      name: 'Error404',
      component: Error404,
      meta: {
        title: 'Halaman Tidak Ditemukan',
      },
    },
  ],
  mode: 'history',
  base: '',

  // Prevents window from scrolling back to top
  // when navigating between components/views
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  },
})

router.beforeEach((to, from, next) => {
  if (to.name === 'Categories') {
    document.title = `${to.meta.title}: ${to.params.catName} - ${SETTINGS.SITE_NAME}`
  } else if (to.name === 'Authors') {
    document.title = `${to.meta.title}: ${to.params.authName} - ${SETTINGS.SITE_NAME}`
  } else {
    document.title = `${to.meta.title} - ${SETTINGS.SITE_NAME}`
  }
  next()
})

router.afterEach((to) => {
  // (to, from)
  // Add a body class specific to the route we're viewing
  let body = document.querySelector('body')

  const slug = !to.params.postSlug ? to.params.pageSlug : to.params.postSlug
  body.classList.add('vue--page--' + slug)
})

export default router
