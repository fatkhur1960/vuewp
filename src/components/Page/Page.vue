<template>
  <div>
    <template v-if="post">
      <!-- Header -->
      <header id="header" class="ex-header">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h1>{{ post.post_title }}</h1>
            </div>
            <!-- end of col -->
          </div>
          <!-- end of row -->
        </div>
        <!-- end of container -->
      </header>
      <!-- end of ex-header -->
      <!-- end of header -->

      <!-- Breadcrumbs -->
      <div class="ex-basic-1">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="breadcrumbs">
                <router-link to="/">Beranda</router-link>
                <i class="fa fa-angle-double-right"></i>
                <span>{{ post.post_title }}</span>
              </div>
              <!-- end of breadcrumbs -->
            </div>
            <!-- end of col -->
          </div>
          <!-- end of row -->
        </div>
        <!-- end of container -->
      </div>
      <!-- end of ex-basic-1 -->
      <!-- end of breadcrumbs -->

      <div class="ex-basic-2">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="row" v-if="post.show_sidebar">
                <div class="col-lg-8">
                  <div
                    class="post-detail-content"
                    v-html="post.post_content"
                  ></div>
                </div>
                <div class="col-lg-4">
                  <PostWidget filter="[category_name]=news" />
                </div>
              </div>
              <div class="text-container" v-else>
                <div v-html="post.post_content"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Breadcrumbs -->
      <div class="ex-basic-1">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="breadcrumbs">
                <router-link to="/">Beranda</router-link>
                <i class="fa fa-angle-double-right"></i>
                <span>{{ post.post_title }}</span>
              </div>
              <!-- end of breadcrumbs -->
            </div>
            <!-- end of col -->
          </div>
          <!-- end of row -->
        </div>
        <!-- end of container -->
      </div>
      <!-- end of ex-basic-1 -->
      <!-- end of breadcrumbs -->
    </template>
    <Loader v-else />
  </div>
</template>

<script>
import axios from 'axios'
import Error404 from '../Error404'
import Loader from '../partials/Loader.vue'
import PostWidget from '../widgets/RecentPostsWidget.vue'
import SETTINGS from '../../settings'
import api from '../../api'

export default {
  data() {
    return {
      post: false,
    }
  },
  beforeMount() {
    api.validator(
      'page',
      { key: 'slug', value: this.$route.params.pageSlug },
      (res) => {
        if (!res.valid) {
          this.$router.replace({ name: 'Error404' })
        }
        this.getPage()
      },
    )
  },
  watch: {
    post: function (newPost, oldPost) {
      if (newPost) {
        this.$route.meta.title = newPost.post_title
        this.$router.replace({ query: { temp: Date.now() } })
        this.$router.replace({ query: { temp: undefined } })
      }
    },
  },
  methods: {
    getPage: function () {
      axios
        .get(SETTINGS.API_PAGE_DETAIL + this.$route.params.pageSlug)
        .then((response) => {
          this.post = response.data
        })
        .catch((e) => {
          if (e.response.status === 404) {
            this.$_error(Error404)
          }
        })
    },
  },

  components: {
    Loader,
    PostWidget,
  },
}
</script>
