<template>
  <div
    v-if="post"
    class="post-detail"
    :class="{ 'post-detail--column': fullWidth }"
  >
    <figure
      class="post-detail__pane post-detail__image"
      @click="fullWidth = ! fullWidth"
    >
      <p>タイトル：{{ post.title }}</p>
      <p>カテゴリ：{{ post.category }}</p>
      <p>URL：{{ post.url }}</p>
      
      <figcaption>Posted by {{ post.owner.name }}</figcaption>
    </figure>
    <div class="post-detail__pane">
      <button class="button button--like" title="Like post">
        <i class="icon ion-md-heart"></i>12
      </button>
      <h2 class="post-detail__title">
        <i class="icon ion-md-chatboxes"></i>Comments
      </h2>
    </div>
  </div>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      post: null,
      fullWidth: false
    }
  },
  methods: {
    async fetchPost () {
      const response = await axios.get(`/api/posts/${this.id}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.post = response.data
    }
  },
  watch: {
    $route: {
      async handler () {
        await this.fetchPost()
      },
      immediate: true
    }
  }
}
</script>