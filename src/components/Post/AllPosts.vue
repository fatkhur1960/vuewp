<template>
  <section>
    <!-- Header -->
    <header id="header" class="ex-header">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1>Semua Berita</h1>
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
              <span>Semua Berita</span>
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

    <div class="container posts">
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
            <div class="mb-3 mt-5" v-if="postsLoading">
              <BottomLoader />
            </div>
            <button
              v-else-if="
                !hasReachedMax &&
                !postsLoading &&
                recentPostsLoaded &&
                recentPosts().length > 0
              "
              class="btn-solid-reg"
              @click.prevent="loadMore"
            >
              Lihat Lainnya
            </button>
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
              <span>Semua Berita</span>
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
import { mapGetters } from 'vuex'

import PostShimmer from '../widgets/PostShimmer'
import PostItem from '../widgets/PostItem'
import BottomLoader from '../widgets/BottomLoader'

export default {
  components: {
    PostShimmer,
    PostItem,
    BottomLoader,
  },
  data() {
    return {
      limit: 9,
      page: 1,
    }
  },
  methods: {
    loadMore() {
      if (!this.hasReachedMax) {
        this.page += 1
        this.$store.dispatch('loadMorePosts', {
          limit: this.limit,
          page: this.page,
          filter: '[category_name]=news',
        })
      }
    },
  },
  beforeMount() {
    this.$store.dispatch('init')
    this.$store.dispatch('getPosts', {
      limit: this.limit,
      page: this.page,
      filter: '[category_name]=news',
    })
  },
  computed: {
    ...mapGetters({
      recentPosts: 'recentPosts',
      recentPostsLoaded: 'recentPostsLoaded',
      postsLoading: 'postsLoading',
      hasReachedMax: 'hasReachedMax',
    }),
  },
}
</script>
