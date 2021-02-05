<template>
  <div class="card">
    <div class="card-body">
      <div class="post-meta-thumb">
        <img class="card-image" v-lazy="post.fimg_url" />
        <div class="sh-overlay-style1">
          <div class="sh-table-full">
            <router-link
              :to="{ name: 'Post', params: { postSlug: post.slug } }"
              class="sh-overlay-item sh-table-cell"
            >
              <div class="sh-overlay-item-container">
                <i class="fa fa-link"></i>
              </div>
            </router-link>
            <a
              href="#"
              @click.prevent="showImage = true"
              class="sh-overlay-item sh-table-cell"
              data-rel="lightcase"
            >
              <div class="sh-overlay-item-container">
                <i class="fa fa-search-plus"></i>
              </div>
            </a>
          </div>
        </div>
      </div>
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
          <Category :categories="post.post_categories" />
        </div>
        <div
          class="card-excerpt"
          :inner-html.prop="post.excerpt.rendered | truncate(90)"
        ></div>
      </div>
      <router-link
        class="post-readmore sh-default-color sh-columns"
        :to="{ name: 'Post', params: { postSlug: post.slug } }"
      >
        <div>Read more</div>
        <div><i class="fa fa-chevron-right"></i></div>
      </router-link>
    </div>
    <transition name="fade">
      <div
        id="lightcase-overlay"
        class="d-flex justify-content-center"
        v-if="showImage"
      >
        <img :src="post.fimg_url" />
        <a
          href="#"
          @click.prevent="showImage = false"
          class="lightcase-icon-close"
        >
          <i class="far fa-times-circle"></i>
        </a>
      </div>
    </transition>
  </div>
</template>
<script>
import Author from './Author'
import DateFormatted from './DateFormatted'
import Category from './Category'

export default {
  name: 'PostItem',
  props: ['post'],
  components: {
    Author,
    DateFormatted,
    Category,
  },
  data: () => ({
    showImage: false,
  }),
}
</script>
