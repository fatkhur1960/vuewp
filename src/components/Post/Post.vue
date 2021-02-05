<template>
  <div>
    <article v-if="post">
      <!-- Header -->
      <header id="header" class="ex-header">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h1>{{ post.title.rendered }}</h1>
              <div class="post-meta mt-3">
                <DateFormatted :dateInput="post.date" />
                <Author :author="post.author_meta" />
                <Category :categories="post.post_categories" />
              </div>
              <div class="share-button mt-3" id="share-network-list">
                <ShareNetwork
                  v-for="s in share"
                  :key="s.key"
                  :network="s.key"
                  :url="currentUrl"
                  :title="`${post.title.rendered} | ${siteName}`"
                  :description="post.excerpt.rendered | truncate(120)"
                >
                  <i :class="`fab fah fa-lg fa-${s.key}`"></i>
                  <span>{{ s.value }}</span>
                </ShareNetwork>
              </div>
            </div>
            <!-- end of col -->
          </div>
          <!-- end of row -->
        </div>
        <!-- end of container -->
      </header>
      <!-- end of ex-header -->
      <!-- end of header -->

      <div class="ex-basic-2 post-detail mb-5">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-8">
                  <div class="post-detail-thumbnail mb-5">
                    <img v-lazy="post.fimg_url" />
                  </div>
                  <div class="text-container mb-5">
                    <div
                      class="post-detail-content"
                      v-html="post.content.rendered"
                    ></div>
                  </div>

                  <hr />

                  <Disqus shortname="pelajarnuwonosobo" />
                </div>
                <div class="col-lg-4">
                  <PostWidget filter="[category_name]=news" />
                </div>
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
                <span>{{ post.title.rendered }}</span>
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
    </article>
    <Loader v-else />
  </div>
</template>

<script>
import axios from 'axios'
import api from '../../api'
import Loader from '../partials/Loader.vue'
import { mapGetters } from 'vuex'
import SETTINGS from '../../settings'
import { Disqus } from 'vue-disqus'
import Author from '../widgets/Author'
import DateFormatted from '../widgets/DateFormatted'
import Category from '../widgets/Category'
import PostWidget from '../widgets/RecentPostsWidget'

export default {
  title() {
    this.getTitle()
  },
  data() {
    return {
      post: false,
      share: [
        { key: 'twitter', value: 'Twitter' },
        { key: 'facebook', value: 'Facebook' },
        { key: 'whatsapp', value: 'Whatsapp' },
        { key: 'telegram', value: 'Telegram' },
      ],
      siteName: '',
      currentUrl: '',
    }
  },
  mounted() {
    this.siteName = SETTINGS.SITE_NAME
    this.currentUrl = window.location.href
  },
  computed: {},
  filters: {
    parseDate: (input) => {
      return moment.utc(new Date(input)).format('D MMM, YYYY')
    },
  },
  beforeMount() {
    api.validator(
      'post',
      { key: 'slug', value: this.$route.params.postSlug },
      (valid) => {
        if (!valid) {
          this.$router.replace({ name: 'Error404' })
        }
        this.getPost()
      },
    )
  },
  watch: {
    post: function (newPost, oldPost) {
      this.$route.meta.title = newPost.title.rendered
      // Add a temporary query parameter.
      this.$router.replace({ query: { temp: Date.now() } })
      // Remove the temporary query parameter.
      this.$router.replace({ query: { temp: undefined } })
    },
  },
  methods: {
    getTitle() {
      return `${SETTINGS.SITE_NAME} - Semua Berita`
    },
    getPost: function () {
      axios
        .get(
          SETTINGS.API_BASE_PATH + 'posts?slug=' + this.$route.params.postSlug,
        )
        .then((response) => {
          this.post = response.data[0]
        })
        .catch((e) => {
          console.log(e)
        })
    },
  },

  components: {
    Loader,
    Author,
    DateFormatted,
    Category,
    PostWidget,
    Disqus,
  },
}
</script>
