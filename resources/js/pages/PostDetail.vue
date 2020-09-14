<template>
  <div v-if="post" class="post_detail mt-2">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate :size="80" :width="7" color="deep-orange lighten-2"></v-progress-circular>
    </v-overlay>
    <v-row>
      <!-- 動画 -->
      <v-col xl="9" lg="9" md="9" sm="0" cols="0" class="video_layout px-2  py-0">
        <v-card flat>
            <Video :videoUrl="post.url"/>
            <v-card-title class="pa-2 detail_title font-weight-bold">{{ post.title }}</v-card-title>
            <v-card-actions class="pr-1 py-1">
              <span class="url"><a :href="`${ getUrl(post.url) }`" target="_brank">{{ post.url }}</a></span>
            </v-card-actions>
            <v-divider></v-divider>
            <v-card-actions class="pa-1">
              <v-icon>mdi-account</v-icon><span class="user">{{ post.owner.name }}</span>
              <v-spacer></v-spacer>
              <div class="menu_icons">
                <v-btn icon v-if="isLogin" @click="onLikeClick" class="button button--like" :class="{ 'button--liked': post.liked_by_user }"><v-icon>mdi-heart</v-icon></v-btn>
                <v-btn icon v-if="isPostCreateUser" color="error" @click="showDelete"><v-icon>mdi-delete</v-icon></v-btn>
              </div>
            </v-card-actions>
        </v-card>
      </v-col>
      <!-- 詳細・コメント -->
      <v-col xl="3" lg="3" md="3" sm="12" cols="12" class="detail_layout px-0 py-0">
        <v-divider></v-divider>
        <v-card-actions class="detail py-2 px-0">
          <v-spacer></v-spacer>
          <div><v-icon color="orange">mdi-folder</v-icon>{{ categories.filter(e => e.id == post.category)[0]['categoryName'] }}</div>
          <v-spacer></v-spacer>
          <div><v-icon color="blue">mdi-eye</v-icon>{{ post.view_count }}</div>
          <v-spacer></v-spacer>
          <div><v-icon color="#e4406f">mdi-heart</v-icon>{{ post.likes_count }}</div>
          <v-spacer></v-spacer>
        </v-card-actions>
        <v-divider></v-divider>
        <div class="comments_layout pt-5">
          <form v-if="isLogin" @submit.prevent="addComment" class="py-1">
            <div v-if="commentsErrors" class="errors">
              <ul v-if="commentsErrors.content">
                <li v-for="msg in commentsErrors.content" :key="msg">{{ msg }}</li>
              </ul>
            </div>
            <div class="comment_submit">
              <input type="text" class="input form__item ma-0" v-model="commentContent" placeholder="コメント入力..."/>
              <div class="submit">
                <v-btn icon type="submit" color="warning"><v-icon small>mdi-lead-pencil</v-icon></v-btn>
              </div>
            </div>
          </form>
          <ul v-if="post.comments.length > 0" class="comments pa-0" :class="{ 'tiktok_comments': isVideoTypeTiktok}">
            <li v-for="comment in post.comments" :key="comment.id" class="pb-4 pl-2 pr-2">
              <v-divider></v-divider>
              <div class="pt-2 user">{{ comment.author.name }}&nbsp;{{ dateFormat(comment.created_at) }}</div>
              <div class="comment">{{ comment.content }}</div>
            </li>
          </ul>
          <p v-else class="pa-2">コメントはありません。</p>
        </div>
      </v-col>
    </v-row>
    <Delete ref="delete" :post_id="post.id" :post_title="post.title"/>
  </div>
</template>

<script>
import { MATCH_URL_TIKTOK } from '../util'
import { OK, FAILURE } from '../util'
import Video from '../components/Video.vue'
import Delete from '../components/Delete.vue'
import { mapState } from 'vuex'

export default {
  components: {
    Video,
    Delete,
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
      commentErrors: null,
      loading: false,
    }
  },
  methods: {
    // コメント投稿
    async addComment () {
      if (this.loading) return;
      this.loading = true;
      var data = {
        id: this.id,
        comment: this.commentContent,
      }
      var response = await this.$store.dispatch('comments/add', data)
      this.loading = false;
      if (response != FAILURE) {
        this.commentContent = ''
        this.post.comments = [
          response,
          ...this.post.comments
        ]
      }
    },
    // いいね追加
    async like () {
      const response = await axios.put(`/api/posts/${this.id}/like`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.post.likes_count = this.post.likes_count + 1
      this.post.liked_by_user = true
    },
    // いいね削除
    async unlike () {
      const response = await axios.delete(`/api/posts/${this.id}/like`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.post.likes_count = this.post.likes_count - 1
      this.post.liked_by_user = false
    },
    // いいね押下
    onLikeClick () {
      if (this.post.liked_by_user) {
        this.unlike()
      } else {
        this.like()
      }
    },
    // 削除ポップアップ表示
    showDelete() {
      this.$refs.delete.isShowDeleteDialog = true
    },
    // 日付成形
    dateFormat(date) {
      var yyyymmdd = date.split('T')[0];
      var hh = date.split('T')[1].split(':')[0];
      var mm = date.split('T')[1].split(':')[1];
      return yyyymmdd + ' ' + hh + ':' + mm;
    },
    // URL取得
    getUrl(date) {
      return date.replace('sp.', '');
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    isPostCreateUser() {
      var userId = this.$store.getters['auth/userid']
      if(userId == this.post.owner.id) {
        return true
      } else {
        return false
      }
    },
    isVideoTypeTiktok() {
      if (this.post.url.match(MATCH_URL_TIKTOK)) {
        return true;
      } else {
        return false;
      }
    },
    ...mapState({
      commentsErrors: state => state.comments.commentsErrorMessages,
      categories: state => state.filter.categories
    }),
  },
  watch: {
    $route: {
      async handler () {
        var data = {
          id: this.id
        }
        this.post = await this.$store.dispatch('post/fetch', data)
      },
      immediate: true
    }
  }
}
</script>