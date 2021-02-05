<template>
  <div id="news" class="posts">
    <div class="container">
      <div class="row" v-if="isHome">
        <div class="col-lg-12">
          <div class="above-heading">BERITA</div>
          <h2 class="h2-heading">Berita Terbaru</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div v-if="recentPostsLoaded">
            <PostItem
              v-for="post in recentPosts(limit)"
              :key="post.id"
              :post="post"
            />
          </div>
          <div v-else>Loading...</div>
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

export default {
  props: {
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
