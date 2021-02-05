<template>
  <div class="post-widget card">
    <div class="card-body" v-if="recentPostsLoaded">
      <div class="above-heading mb-3">Berita Terbaru</div>
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
    </div>
    <div v-else>Loading...</div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Author from './Author'
import DateFormatted from './DateFormatted'
import Category from './Category'

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
  },
  computed: {
    ...mapGetters({
      recentPosts: 'recentPosts',
      recentPostsLoaded: 'recentPostsLoaded',
    }),
  },
  mounted() {
    this.$store.dispatch('getPosts', { limit: this.limit, filter: this.filter })
  },
}
</script>
