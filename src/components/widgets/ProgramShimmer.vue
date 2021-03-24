<template>
  <div class="card">
    <div class="card-image">
      <Shimmer height="180px" width="180px" radius="300"/>
    </div>
    <div class="card-body">
      <div class="card-caption">
        <Shimmer height="20px" width="100%" radius="5" />
        <Shimmer height="20px" width="80%" radius="5" />
        <Shimmer
          v-for="i in 3"
          :key="i"
          height="15px"
          radius="5"
          :width="`${100 - i * 2}%`"
        />
      </div>
    </div>
  </div>
</template>
<script>
import Shimmer from './Shimmer'
export default {
  components: { Shimmer },
}
</script>
