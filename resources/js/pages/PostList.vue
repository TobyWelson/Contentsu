<template>
  <v-layout wrap>
    <Post  
      v-for="post in posts"
      :key="post.id"
      :item="post"
    />
  </v-layout>  
</template>
<script>
import Post from '../components/Post.vue'

export default {
  components: {
    Post,
  },
  data () {
    return {
      posts: [],
    }
  },
  watch: {
    $route: {
      async handler () {
        this.posts = await this.$store.dispatch('post/fetchPage')
      },
      immediate: true
    }
  }
}
</script>