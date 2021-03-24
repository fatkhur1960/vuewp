<template>
  <div :id="id" class="posts">
    <div class="container">
      <div class="row" v-if="isHome">
        <div class="col-lg-12">
          <div class="above-heading">{{ title }}</div>
          <h2 class="h2-heading">{{ subTitle }}</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <transition-group name="fade2">
            <template v-if="recentPostsLoaded">
              <PostItem
                v-for="post in recentPosts()"
                :key="post.id"
                :post="post"
              />
            </template>
            <template v-else>
              <PostShimmer v-for="i in 6" :key="i" />
            </template>
          </transition-group>
        </div>
        <div class="col-lg-12">
          <div class="text-center mt-2">
            <router-link
              class="btn-solid-reg"
              to="/news/all"
              v-if="isHome"
              exact-path
            >
              Semua Berita
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import PostItem from './PostItem'
import PostShimmer from './PostShimmer'

export default {
  props: {
    id: {
      type: String,
      default: 'news',
    },
    title: {
      type: String,
      default: 'BERITA',
    },
    subTitle: {
      type: String,
      default: 'Berita Terbaru',
    },
    limit: {
      type: Number,
      default: 10,
    },
    isHome: {
      type: Boolean,
      default: false,
    },
    filter: {
      type: String,
    },
  },
  components: {
    PostItem,
    PostShimmer,
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
