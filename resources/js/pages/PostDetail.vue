<template>
  <div v-if="post" class="post_detail">
  <v-card flat>
      <Video :videoUrl="post.url"/>
      <v-card-title class="font-weight-bold text-body-1 text-sm-h5 text-md-h6 text-lg-h6 text-xl-h6">{{ post.title }}</v-card-title>
      <v-card-actions>
       <span class="title_over_text text-subtitle-2 text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{ post.url }}</span>
        <v-spacer></v-spacer>
        <div class="menu_icons">
          <v-btn icon v-if="isLogin" @click="onLikeClick" class="button button--like" :class="{ 'button--liked': post.liked_by_user }"><v-icon>mdi-heart</v-icon></v-btn>
          <v-btn icon v-if="isPostFromCurrentUser" color="error" @click="onDeleteClick"><v-icon>mdi-delete</v-icon></v-btn>
        </div>
      </v-card-actions>
      <v-divider></v-divider>
      <v-card-actions>
        <v-icon small>mdi-account</v-icon><span class="name-over-text text-caption text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{ post.owner.name }}</span>
        <v-spacer></v-spacer>
        <div>
          <v-icon small>mdi-folder</v-icon><span class="mr-1 text-subtitle-2 text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{ post.category }}</span>
          <v-icon small>mdi-eye</v-icon><span class="mr-1 text-subtitle-2 text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{ post.view_count }}</span>
          <v-icon small>mdi-heart</v-icon><span class="mr-1 text-subtitle-2 text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{ post.likes_count }}</span>
        </div>
      </v-card-actions>
  </v-card>

    <div class="post-detail__pane">
      <h2 class="post-detail__title">
        <i class="icon ion-md-chatboxes"></i>コメント
      </h2>
      <form v-if="isLogin" @submit.prevent="addComment" class="form">
        <div v-if="commentErrors" class="errors">
          <ul v-if="commentErrors.content">
            <li v-for="msg in commentErrors.content" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <textarea class="form__item" v-model="commentContent"></textarea>
        <div class="form__button">
          <v-btn depressed rounded type="submit" color="warning"><v-icon small>mdi-lead-pencil</v-icon>書込み</v-btn>
        </div>
      </form>
      <ul v-if="post.comments.length > 0" class="post-detail__comments">
        <li
          v-for="comment in post.comments"
          :key="comment.content"
          class="post-detail__commentItem">
          <p class="post-detail__commentBody">
            {{ comment.content }}
          </p>
          <p class="post-detail__commentInfo">
            転載者名: {{ comment.author.name }}
          </p>
        </li>
      </ul>
      <p v-else>まだコメントはありません。</p>
    </div>
  </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Video from '../components/Video.vue'
import { FAILURE } from '../util'

export default {
  components: {
    Video
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
          this.$router.push('/').catch(err => {})
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
      },
      immediate: true
    }
  }
}
</script>