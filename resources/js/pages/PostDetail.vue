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
      <p>閲覧者数: {{ post.view_count }}</p>
      <div v-if="isPostFromCurrentUser" class="delete_btn my-2">
        <v-btn
          color="error"
          class="delete_btn"
          @click="onDeleteClick"
        >
        記事を削除する
        </v-btn>
      </div>
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
import { FAILURE } from '../util'

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
    async onDeleteClick () {
      if(confirm('本当に記事を削除しますか？')){
        var result = await this.$store.dispatch('post/delete', this.post.id);
        if (result != FAILURE) {
          this.$store.commit('post/setLastPage', 0);
          this.$store.commit('post/setPage', 0);
          this.$store.commit('post/setPosts', []);
          this.$router.push('/')
          this.$store.commit('message/setContent', {
            content: '記事が削除されました'.result,
            timeout: 4000
            })
        } else {
          this.$store.commit('message/setContent', {
            content: '記事の削除に失敗しました',
            timeout: 4000
          })
        }
      }
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    username () {
      return this.$store.getters['auth/username']
    },
    isPostFromCurrentUser() {
      if(this.username == this.post.owner.name) {
        return true
      }
      return false
    }
  },
  watch: {
    $route: {
      async handler () {
        var data = {
          id: this.id,
          isLogin: this.isLogin,
        }
        this.post = await this.$store.dispatch('post/fetch', data)
        this.$store.commit('screen/setCurrent', 'PostDetail');
      },
      immediate: true
    }
  }
}
</script>