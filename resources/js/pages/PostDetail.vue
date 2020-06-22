<template>
  <div
    v-if="post"
    class="post-detail">
    <figure
      class="post-detail__pane post-detail__image">
      <p>タイトル：{{ post.title }}</p>
      <p>カテゴリ：{{ post.category }}</p>
      <p>URL：{{ post.url }}</p>

      <Youtube :videoUrl="post.url"/>
      
      <figcaption>Posted by {{ post.owner.name }}</figcaption>
    </figure>
    <div class="post-detail__pane">
      <button
        class="button button--like"
        :class="{ 'button--liked': post.liked_by_user }"
        title="Like post"
        @click="onLikeClick"
      >
        <i class="icon ion-md-heart"></i>{{ post.likes_count }}
      </button>
      <h2 class="post-detail__title">
        <i class="icon ion-md-chatboxes"></i>コメント
      </h2>
      <ul v-if="post.comments.length > 0" class="post-detail__comments">
        <li
          v-for="comment in post.comments"
          :key="comment.content"
          class="post-detail__commentItem"
        >
          <p class="post-detail__commentBody">
            {{ comment.content }}
          </p>
          <p class="post-detail__commentInfo">
            転載者名: {{ comment.author.name }}
          </p>
        </li>
      </ul>
      <p v-else>まだコメントはありません。</p>
      <form v-if="isLogin" @submit.prevent="addComment" class="form">
        <div v-if="commentErrors" class="errors">
          <ul v-if="commentErrors.content">
            <li v-for="msg in commentErrors.content" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <textarea class="form__item" v-model="commentContent"></textarea>
        <div class="form__button">
          <button type="submit" class="button button--inverse">書込み</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Youtube from '../components/Youtube.vue'

export default {
  components: {
    Youtube
  },
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      post: null,
      commentContent: '',
      commentErrors: null
    }
  },
  methods: {
    async addComment () {

      const response = await axios.post(`/api/posts/${this.id}/comments`, {
        content: this.commentContent
      })

      // バリデーションエラー
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.commentErrors = response.data.errors
        return false
      }

      this.commentContent = ''
      // エラーメッセージをクリア
      this.commentErrors = null

      // その他のエラー
      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
      this.post.comments = [
        response.data,
        ...this.post.comments
      ]
    },
    async like () {
      const response = await axios.put(`/api/posts/${this.id}/like`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.post.likes_count = this.post.likes_count + 1
      this.post.liked_by_user = true
    },
    async unlike () {
      const response = await axios.delete(`/api/posts/${this.id}/like`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.post.likes_count = this.post.likes_count - 1
      this.post.liked_by_user = false
    },
    onLikeClick () {
      if (! this.isLogin) {
        alert('いいね機能を使うにはログインしてください。')
        return false
      }

      if (this.post.liked_by_user) {
        this.unlike()
      } else {
        this.like()
      }
    },
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    }
  },
  watch: {
    $route: {
      async handler () {
        this.post = await this.$store.dispatch('post/fetch', this.id)
      },
      immediate: true
    }
  }
}
</script>