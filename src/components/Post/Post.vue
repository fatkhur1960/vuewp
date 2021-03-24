<template>
  <section>
    <!-- Header -->
    <header id="header" class="ex-header">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <transition-group name="zoom">
              <template v-if="!postDetailLoading">
                <h1 key="postTitle">{{ postDetail.title.rendered }}</h1>
                <div key="postMeta" class="post-meta mt-3">
                  <DateFormatted :dateInput="postDetail.date" />
                  <Author :author="postDetail.author_meta" />
                  <Category :categories="postDetail.post_categories" />
                </div>
                <div
                  key="share"
                  class="share-button mt-3"
                  id="share-network-list"
                >
                  <ShareNetwork
                    v-for="s in share"
                    :key="s.key"
                    :network="s.key"
                    :url="currentUrl"
                    :title="`${postDetail.title.rendered} | ${siteName}`"
                    :description="postDetail.excerpt.rendered | truncate(120)"
                  >
                    <i :class="`fab fah fa-lg fa-${s.key}`"></i>
                    <span>{{ s.value }}</span>
                  </ShareNetwork>
                </div>
              </template>
              <template v-else>
                <div key="shimmer1">
                  <Shimmer width="60%" height="30px" radius="5" />
                  <Shimmer width="45%" height="30px" radius="5" />
                  <div class="mt-3 shimmer-list">
                    <Shimmer
                      v-for="i in 3"
                      :key="i"
                      width="8%"
                      height="15px"
                      radius="5"
                    />
                  </div>
                  <div class="mt-3 shimmer-list">
                    <Shimmer
                      v-for="i in 4"
                      :key="i"
                      width="10%"
                      height="25px"
                      radius="5"
                    />
                  </div>
                </div>
              </template>
            </transition-group>
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
                  <transition name="zoom">
                    <img
                      v-if="!postDetailLoading"
                      v-lazy="postDetail.fimg_url"
                    />
                    <Shimmer v-else width="100%" height="430px" />
                  </transition>
                </div>
                <template v-if="!postDetailLoading">
                  <div class="text-container mb-5">
                    <div
                      class="post-detail-content"
                      v-html="postDetail.content.rendered"
                    ></div>
                  </div>

                  <hr />

                  <Disqus shortname="pelajarnuwonosobo" />
                </template>
                <template v-else>
                  <Shimmer height="20px" width="100%" radius="5" />
                  <Shimmer height="20px" width="80%" radius="5" />
                  <Shimmer height="20px" width="30%" radius="5" />
                  <Shimmer
                    v-for="i in 3"
                    :key="`a-${i}`"
                    height="15px"
                    radius="5"
                    :width="`${100 - i * 3}%`"
                  />
                  <Shimmer
                    v-for="i in 4"
                    :key="`b-${i}`"
                    height="15px"
                    radius="5"
                    :width="`${100 - i * 3}%`"
                  />
                </template>
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
              <span>{{ postDetail.title.rendered }}</span>
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
  </section>
</template>

<script>
import api from '../../api'
import Shimmer from '../widgets/Shimmer.vue'
import { mapGetters } from 'vuex'
import SETTINGS from '../../settings'
import { Disqus } from 'vue-disqus'
import Author from '../widgets/Author'
import DateFormatted from '../widgets/DateFormatted'
import Category from '../widgets/Category'
import PostWidget from '../widgets/RecentPostsWidget'

export default {
  data() {
    return {
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
  computed: {
    ...mapGetters({
      postDetail: 'postDetail',
      postDetailLoading: 'postDetailLoading',
    }),
  },
  beforeMount() {
    this.$store.dispatch('init')
    this.siteName = SETTINGS.SITE_NAME
    this.currentUrl = window.location.href
    api.validator(
      'post',
      { key: 'slug', value: this.$route.params.postSlug },
      (res) => {
        if (!res.valid) {
          this.$router.replace({ name: 'Error404' })
        }
        this.$store.dispatch('getPostDetail', {
          slug: this.$route.params.postSlug,
        })
      },
    )
  },
  watch: {
    postDetail: function (newPost, oldPost) {
      if (newPost) {
        this.$route.meta.title = newPost.title.rendered
        // Add a temporary query parameter.
        this.$router.replace({ query: { temp: Date.now() } })
        // Remove the temporary query parameter.
        this.$router.replace({ query: { temp: undefined } })
      }
    },
  },
  components: {
    Shimmer,
    Author,
    DateFormatted,
    Category,
    PostWidget,
    Disqus,
  },
}
</script>
