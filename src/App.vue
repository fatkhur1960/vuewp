<template>
  <div id="my-app" class="flex flex-col md:min-h-screen">
    <app-header v-if="!$route.meta.clear" />

    <transition
      name="loader-animation"
      enter-active-class="animated fadeIn"
      leave-active-class="animated fadeOut"
    >
      <progress-bar :show-loader="showLoader" :loader-style="loaderStyle" />
    </transition>

    <PageTransition>
      <router-view :key="$route.fullPath"></router-view>
    </PageTransition>

    <app-footer v-if="!$route.meta.clear" />
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
import Header from './components/partials/Header.vue'
import Footer from './components/partials/Footer.vue'
import ProgressBar from './components/partials/ProgressBar.vue'
import PageTransition from './components/partials/PageTransition.vue'

export default {
  data() {
    return {
      showLoader: true,
    }
  },
  computed: {
    ...mapGetters({
      isLoading: 'isLoading',
      loadingProgress: 'loadingProgress',
    }),

    loaderStyle() {
      return `width: ${this.loadingProgress}%;`
    },
  },
  components: {
    appHeader: Header,
    appFooter: Footer,
    ProgressBar,
    PageTransition,
  },

  watch: {
    // watch the value of isLoading and once it's false hide the loader
    isLoading(val) {
      if (val == false) {
        let self = this
        setTimeout(function () {
          self.showLoader = false
        }, 1000)
      }
    },
  },
}
</script>
