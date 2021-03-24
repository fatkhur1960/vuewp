<template>
  <div class="post-widget card">
    <div class="card-body" v-if="recentPostsLoaded">
      <div class="above-heading mb-3">Berita Terbaru</div>
      <transition-group name="fade2">
        <div
          class="widget-item mb-3"
          v-for="(post, index) in recentPosts(limit)"
          :key="post.id"
        >
          <template v-if="index == 0">
            <router-link
              :to="{ name: 'Post', params: { postSlug: post.slug } }"
              tag="div"
              class="highlight-item"
            >
              <img class="item-image" v-lazy="post.fimg_url" />
              <div class="content">
                <div class="card-caption">
                  <router-link
                    :to="{ name: 'Post', params: { postSlug: post.slug } }"
                    class="card-title"
                  >
                    {{ post.title.rendered }}
                  </router-link>
                  <div class="card-meta">
                    <DateFormatted :dateInput="post.date" />
                    <Author :author="post.author_meta" />
                  </div>
                </div>
              </div>
            </router-link>
          </template>
          <template v-else>
            <div class="d-flex flex-row">
              <img class="default-item-image" v-lazy="post.fimg_url" />
              <div class="content ml-3">
                <router-link
                  :to="{ name: 'Post', params: { postSlug: post.slug } }"
                  class="post-title"
                >
                  {{ post.title.rendered }}
                </router-link>
                <div class="card-meta default-post-meta">
                  <DateFormatted :dateInput="post.date" />
                  <Author :author="post.author_meta" />
                </div>
              </div>
            </div>
          </template>
        </div>
      </transition-group>
    </div>
    <div class="card-body" v-else>
      <Shimmer width="35%" height="20px" radius="5" />
      <Shimmer width="100%" height="5px" radius="5" />
      <Shimmer width="100%" height="190px" />
      <div v-for="i in 4" :key="`w-${i}`" class="d-flex flex-row mb-2">
        <Shimmer width="90px" height="90px" />
        <div class="ml-3" style="width: 60%;">
          <Shimmer width="100%" height="16px" radius="5" />
          <Shimmer width="80%" height="16px" radius="5" />
          <Shimmer width="20%" height="12px" radius="5" />
          <Shimmer width="20%" height="12px" radius="5" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Author from './Author'
import DateFormatted from './DateFormatted'
import Category from './Category'
import Shimmer from './Shimmer'

export default {
  props: {
    limit: {
      type: Number,
      default: 5,
    },
    filter: {
      type: String,
    },
  },
  components: {
    Author,
    DateFormatted,
    Category,
    Shimmer,
  },
  computed: {
    ...mapGetters({
      recentPosts: 'recentPosts',
      recentPostsLoaded: 'recentPostsLoaded',
    }),
  },
  beforeMount() {
    this.$store.dispatch('getPosts', { limit: this.limit, filter: this.filter })
  },
}
</script>
