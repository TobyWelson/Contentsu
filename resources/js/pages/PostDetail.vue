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

      <youtube :video-id="videoId" />
      
      <figcaption>Posted by {{ post.owner.name }}</figcaption>
    </figure>
    <div class="post-detail__pane">
      <button class="button button--like" title="Like post">
        <i class="icon ion-md-heart"></i>
      </button>
      <h2 class="post-detail__title">
        <i class="icon ion-md-chatboxes"></i>コメント
      </h2>
    </div>
  </div>
</template>

<script>
import { OK } from '../util'
import Vue from 'vue'
import VueYoutube from 'vue-youtube'

Vue.use(VueYoutube)

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
      fullWidth: false,
      videoId: 'Q3nYKYhwEy4'
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
      // var youbeResult = this.post.url.match(/youtu\.be/);
      // var youtubeResult = this.post.url.match(/youtube\.com/);
      // if (youbeResult != null) {
      //   videoId = this.post.url.match(/\=.*/);
      // } else if (youtubeResult != null) {
      //   videoId = this.post.url.match(/=.*/);
      // }
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