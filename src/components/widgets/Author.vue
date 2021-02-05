<template>
  <div>
    <i class="fa fa-user"></i>
    <router-link
      :to="{
        name: 'Authors',
        params: { authSlug: author.slug, authName: author.name },
      }"
    >
      {{ author.name }}
    </router-link>
  </div>
</template>
<script>
export default {
  name: 'Author',
  props: ['author'],
}
</script>
