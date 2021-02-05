<template>
  <div>
    <template v-if="!postLoading">
      <!-- Header -->
      <header id="header" class="ex-header">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h1>Penulis: {{ authName }}</h1>
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
                <span>Penulis</span>
                <i class="fa fa-angle-double-right"></i>
                <span>{{ authName }}</span>
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
            <PostItem v-for="post in posts" :key="post.id" :post="post" />
          </div>

          <div class="col-lg-12">
            <div class="text-center mt-2">
              <div class="mb-3" v-if="isLoading">
                <BottomLoader />
              </div>
              <button
                v-else-if="!reachedMax && !isLoading && posts.length > 0"
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
                <span>Penulis</span>
                <i class="fa fa-angle-double-right"></i>
                <span>{{ authName }}</span>
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
import SETTINGS from '../../settings'

import Loader from '../partials/Loader.vue'
import BottomLoader from '../widgets/BottomLoader'
import PostItem from '../widgets/PostItem'
import Error404 from '../Error404'

export default {
  components: {
    Loader,
    BottomLoader,
    PostItem,
  },
  data() {
    return {
      posts: [],
      limit: 9,
      page: 1,
      reachedMax: false,
      bottomStatus: '',
      isLoading: false,
      postLoading: false,
      authName: '',
    }
  },
  mounted() {
    this.authName = this.$route.params.authName || ''
  },
  methods: {
    getPosts(page) {
      axios
        .get(
          SETTINGS.API_BASE_PATH +
            `posts?per_page=${this.limit}&page=${page}&filter[author_name]=${this.$route.params.authSlug}`,
        )
        .then((response) => {
          const append = response.data
          this.posts = this.posts.concat(append)
          this.isLoading = false
          this.postLoading = false
        })
        .catch((e) => {
          this.reachedMax = true
          this.isLoading = false
          this.postLoading = false
          console.error(e)
        })
    },
    loadMore() {
      this.isLoading = true
      if (!this.reachedMax) {
        this.page += 1
        this.getPosts(this.page)
      }
    },
  },
  beforeMount() {
    this.isLoading = true
    this.postLoading = true
    api.validator(
      'author',
      { key: 'nickname', value: this.$route.params.authSlug },
      (valid) => {
        if (!valid) {
          this.$router.replace({ name: 'Error404' })
        }
        this.getPosts(this.page)
      },
    )
  },
}
</script>
