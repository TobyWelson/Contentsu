<template>
  <v-layout wrap>
    <Post  
      v-for="post in posts"
      :key="post.id"
      :item="post"
    />
<Pagination :current-page="currentPage" :last-page="lastPage" />
  </v-layout>
      
</template>

<script>
import Post from '../components/Post.vue'
import Pagination from '../components/Pagination.vue'

export default {
  components: {
    Post,
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data () {
    return {
      posts: [],
      currentPage: 0,
      lastPage: 0
    }
  },
  watch: {
    $route: {
      async handler () {
      const posts = await this.$store.dispatch('post/fetchPage', this.page)

      this.posts = posts.data
      this.currentPage = posts.current_page
      this.lastPage = posts.last_page
      },
      immediate: true
    }
  }
}
</script>