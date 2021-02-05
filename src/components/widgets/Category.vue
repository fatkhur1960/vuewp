<template>
  <div>
    &nbsp;
    <i v-if="categories" class="fa fa-tags"></i>
    <router-link
      v-for="cat in categories"
      :key="cat.slug"
      :to="{
        name: 'Categories',
        params: { catSlug: cat.slug, catName: cat.name },
      }"
    >
      {{ cat.name }}
    </router-link>
  </div>
</template>
<script>
export default {
  name: 'Author',
  props: ['categories'],
}
</script>
