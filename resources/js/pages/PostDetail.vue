<template>
  <div v-if="post" class="post_detail">
    <v-card flat>
        <Video :videoUrl="post.url"/>
        <v-card-title class="pa-2 detail_title font-weight-bold">{{ post.title }}</v-card-title>
        <v-card-actions class="pr-1 py-1">
          <span class="url"><a :href="`${ post.url }`" target="_brank">{{ post.url }}</a></span>
          <v-spacer></v-spacer>
          <div class="menu_icons">
            <v-btn icon v-if="isLogin" @click="onLikeClick" class="button button--like" :class="{ 'button--liked': post.liked_by_user }"><v-icon>mdi-heart</v-icon></v-btn>
            <v-btn icon v-if="isPostFromCurrentUser" color="error" @click="showDelete"><v-icon>mdi-delete</v-icon></v-btn>
          </div>
        </v-card-actions>
        <v-divider></v-divider>
        <v-card-actions class="detail">
          <v-icon>mdi-account</v-icon>{{ post.owner.name }}
          <v-spacer></v-spacer>
          <div class="detail_right">
            <div><v-icon color="orange">mdi-folder</v-icon>{{ post.category }}</div>
            <div><v-icon color="blue">mdi-eye</v-icon>{{ post.view_count }}</div>
            <div><v-icon color="#e4406f">mdi-heart</v-icon>{{ post.likes_count }}</div>
          </div>
        </v-card-actions>
    </v-card>
    <div class="pt-6">
      <div class="comments_title px-1 py-1">コメント</div>
      <form v-if="isLogin" @submit.prevent="addComment" class="form mb-5">
        <div v-if="commentErrors" class="errors">
          <ul v-if="commentErrors.content">
            <li v-for="msg in commentErrors.content" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <input type="text" class="form__item my-2" v-model="commentContent" placeholder="コメント入力..."/>
        <div class="form__button">
          <v-btn depressed rounded type="submit" color="warning"><v-icon small>mdi-lead-pencil</v-icon>書込み</v-btn>
        </div>
      </form>
      <ul v-if="post.comments.length > 0" class="comments pa-0 my-2">
        <li v-for="comment in post.comments" :key="comment.content" class="pb-1">
          <div class="comment">{{ comment.content }}</div>
          <div class="px-2 pt-2"><v-icon small>mdi-account</v-icon>{{ comment.author.name }}</div>
        </li>
      </ul>
      <p v-else>まだコメントはありません。</p>
    </div>
    <Delete ref="delete" :post_id="post.id" :post_title="post.title"/>
  </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Video from '../components/Video.vue'
import Delete from '../components/Delete.vue'
import { FAILURE } from '../util'

export default {
  components: {
    Video,
    Delete
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
    showDelete() {
      this.$refs.delete.isShowDeleteDialog = true
    },
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