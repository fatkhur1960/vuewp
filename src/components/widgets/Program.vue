<template>
  <!-- Description -->
  <div class="cards-1">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="above-heading">PROGRAM UNGGULAN</div>
          <h3 class="h3-heading">
            PC IPNU IPPNU Wonosobo
            <br />
            Periode 2020-2022
          </h3>
        </div>
        <!-- end of col -->
      </div>
      <!-- end of row -->
      <div class="row">
        <div class="col-lg-12">
          <!-- Card -->
          <transition-group name="fade2">
            <template v-if="widgetItemsLoaded">
              <div
                class="card"
                v-for="(item, index) in widgetItems()"
                :key="index"
              >
                <div class="card-image">
                  <img class="img-fluid" :src="item.image" alt="alternative" />
                </div>
                <div class="card-body">
                  <h4 class="card-title">{{ item.title }}</h4>
                  <p>
                    {{ item.content }}
                  </p>
                </div>
              </div>
            </template>
            <template v-else>
              <ProgramShimmer v-for="i in 3" :key="i" />
            </template>
          </transition-group>
          <!-- end of card -->
        </div>
        <!-- end of col -->
      </div>
      <!-- end of row -->
    </div>
    <!-- end of container -->
  </div>
  <!-- end of cards-1 -->
  <!-- end of description -->
</template>
<script>
import { mapGetters } from 'vuex'
import ProgramShimmer from './ProgramShimmer'

export default {
  components: { ProgramShimmer },
  computed: {
    ...mapGetters({
      widgetItems: 'widgetItems',
      widgetItemsLoaded: 'widgetItemsLoaded',
    }),
  },
  beforeMount() {
    this.$store.dispatch('getWidget', { id: 'proker' })
  },
}
</script>
