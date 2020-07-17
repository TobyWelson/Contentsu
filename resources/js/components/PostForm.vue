<template>
  <v-dialog v-model="isShowPostDialog" max-width="600px">
    <v-card>
      <div v-show="loading" class="post_regist_load">
        <Loader>投稿中...</Loader>
      </div>
      <div class="errors" v-if="postErrors">
        <ul v-if="postErrors.title">
          <li v-for="msg in postErrors.title" :key="msg">{{ msg }}</li>
        </ul>
        <ul v-if="postErrors.category">
          <li v-for="msg in postErrors.category" :key="msg">{{ msg }}</li>
        </ul>
        <ul v-if="postErrors.url">
          <li v-for="msg in postErrors.url" :key="msg">{{ msg }}</li>
        </ul>
      </div>
      <v-form @submit.prevent="submit">
        <v-container py-5>
          <v-container>
            <v-text-field
              label="タイトル"
              v-model="title"
              outlined/>
            <v-select
              label="カテゴリ"
              v-model="category"
              :items="categories"
              outlined></v-select>
            <v-text-field
              label="URL"
              v-model="url"
              outlined/>
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="submit">投稿</v-btn>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapState } from 'vuex'
import { FAILURE } from '../util'
import Loader from './Loader.vue'

export default {
  components: {
    Loader
  },
  data () {
    return {
      isShowPostDialog: false,
      loading: false,
      title: '',
      category: '',
      url: '',
    }
  },
  computed: {
    ...mapState({
      postErrors: state => state.post.postsErrorMessages,
      categories: state => state.filter.categories,
    }),
  },
  methods: {
    onFileChange (event) {
        this.title = event.target.title
        this.category = event.target.category
        this.url = event.target.url
    },
    reset () {
      this.title = ''
      this.category = ''
      this.url = ''
    },
    async submit () {
      this.loading = true
      const formData = new FormData()
      formData.append('title', this.title)
      formData.append('category', this.category)
      formData.append('url', this.url)
      var result = await this.$store.dispatch('post/posts', formData)
      this.loading = false
      if (result != FAILURE) {
        this.reset()
        this.isShowPostDialog = false
        this.$router.push(`/posts/${result}`)
        this.$store.commit('message/setContent', {
          content: '記事が投稿されました',
          timeout: 4000
        })
      }
    }
  }
}
</script>