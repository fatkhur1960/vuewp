<template>
  <div class="card">
    <div class="card-body">
      <div class="post-meta-thumb">
        <Shimmer height="235px" width="100%" />
      </div>
      <div class="card-caption">
        <Shimmer height="20px" width="100%" radius="5" />
        <Shimmer height="20px" width="80%" radius="5" />
        <Shimmer height="20px" width="30%" radius="5" />
        <Shimmer height="20px" width="30%" radius="5" />
        <Shimmer height="20px" width="30%" radius="5" />
        <Shimmer
          v-for="i in 6"
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
