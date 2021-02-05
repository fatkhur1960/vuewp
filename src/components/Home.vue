<template>
  <div class="page page--home">
    <!-- Header -->
    <header id="header">
      <vueper-slides
        :autoplay="true"
        :lazy="true"
        :lazyLoad="true"
        :infinite="true"
        :slideRatio="1 / 2"
        :touchable="false"
      >
        <template v-if="slideItemsLoaded">
          <vueper-slide
            v-for="(slide, i) in slideItems()"
            :key="i"
            :image="slide.image"
          >
            <template v-slot:content>
              <div class="vueperslide__content-wrapper">
                <div class="content-inner">
                  <h2>{{ slide.title }}</h2>
                  <div v-html="slide.content"></div>
                </div>
              </div>
            </template>
            <template v-slot:loader>
              <BottomLoader />
            </template>
          </vueper-slide>
        </template>
        <vueper-slide v-else>
          <template v-slot:content>
            <div class="vueperslide__content-wrapper">
              <BottomLoader />
            </div>
          </template>
        </vueper-slide>
      </vueper-slides>
      <!-- end of header-content -->
    </header>

    <Program />
    <Satir />
    <Posts :limit="6" :isHome="true" filter="[category_name]=news" />
  </div>
</template>

<script>
import Program from './widgets/Program.vue'
import Satir from './widgets/Satir.vue'
import BottomLoader from './widgets/BottomLoader.vue'
import Posts from './widgets/RecentPosts.vue'

import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'

import { mapGetters } from 'vuex'
import SETTINGS from '../settings'

export default {
  title() {
    return `${SETTINGS.SITE_NAME} - ${SETTINGS.SITE_TAGLINE}`
  },
  components: {
    Program,
    Satir,
    Posts,
    VueperSlides,
    VueperSlide,
    BottomLoader,
  },
  computed: {
    ...mapGetters({
      slideItems: 'slideItems',
      slideItemsLoaded: 'slideItemsLoaded',
    }),
  },
  beforeMount() {
    this.$store.dispatch('getSlides', { limit: 5 })
  },
}
</script>

<style type="postcss"></style>
