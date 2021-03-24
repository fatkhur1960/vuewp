<template>
  <section>
    <!-- Header -->
    <header id="header" class="ex-header">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1>Kategori: {{ catName }}</h1>
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
              <span>Kategori</span>
              <i class="fa fa-angle-double-right"></i>
              <span>{{ catName }}</span>
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
              <template v-if="recentPosts().length > 0">
                <PostItem
                  v-for="post in recentPosts()"
                  :key="post.id"
                  :post="post"
                />
              </template>
              <template v-else>
                <SearchImg key="emptyList"/>
              </template>
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
              <span>Kategori</span>
              <i class="fa fa-angle-double-right"></i>
              <span>{{ catName }}</span>
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
import api from '../../api'
import SETTINGS from '../../settings'

import Loader from '../partials/Loader.vue'
import BottomLoader from '../widgets/BottomLoader'
import PostItem from '../widgets/PostItem'
import PostShimmer from '../widgets/PostShimmer'
import SearchImg from '../widgets/SearchImg'

export default {
  components: {
    Loader,
    BottomLoader,
    PostItem,
    PostShimmer,
    SearchImg,
  },
  data() {
    return {
      limit: 9,
      page: 1,
      catName: '',
    }
  },
  mounted() {
    this.catName = this.$route.params.catName || ''
  },
  methods: {
    loadMore() {
      if (!this.reachedMax) {
        this.page += 1
        this.$store.dispatch('loadMorePosts', {
          limit: this.limit,
          page: this.page,
          filter: `[category_name]=${this.$route.params.catSlug}`,
        })
      }
    },
  },
  watch: {
    catName: function (newName, oldName) {
      if (newName) {
        this.$route.meta.title = `${this.$route.meta.title}: ${newName} - ${SETTINGS.SITE_NAME}`
        // Add a temporary query parameter.
        this.$router.replace({ query: { temp: Date.now() } })
        // Remove the temporary query parameter.
        this.$router.replace({ query: { temp: undefined } })
      }
    },
  },
  computed: {
    ...mapGetters({
      recentPosts: 'recentPosts',
      recentPostsLoaded: 'recentPostsLoaded',
      postsLoading: 'postsLoading',
      hasReachedMax: 'hasReachedMax',
    }),
  },
  beforeMount() {
    this.$store.dispatch('init')
    api.validator(
      'category',
      { key: 'slug', value: this.$route.params.catSlug },
      (res) => {
        if (!res.valid) {
          this.$router.replace({ name: 'Error404' })
        }
        this.catName = this.$route.params.catName || res.data
        this.$store.dispatch('getPosts', {
          limit: this.limit,
          page: this.page,
          filter: `[category_name]=${this.$route.params.catSlug}`,
        })
      },
    )
  },
}
</script>
