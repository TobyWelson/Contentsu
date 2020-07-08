<template>
  <v-dialog v-model="isShowPostDialog" max-width="600px">
    <v-card>
      <v-container>
        <v-layout justify-center class="display-1">URL転載</v-layout>
      </v-container>
      <div v-show="loading" class="panel">
        <Loader>記事を投稿中...</Loader>
      </div>
      <div class="errors" v-if="errors">
        <ul v-if="errors.title">
          <li v-for="msg in errors.title" :key="msg">{{ msg }}</li>
        </ul>
        <ul v-if="errors.category">
          <li v-for="msg in errors.category" :key="msg">{{ msg }}</li>
        </ul>
        <ul v-if="errors.url">
          <li v-for="msg in errors.url" :key="msg">{{ msg }}</li>
        </ul>
      </div>
      <v-form @submit.prevent="submit">
        <v-container py-5>
          <v-container>
            <v-text-field
              label="タイトル"
              v-model="title"
              outlined/>
            <v-text-field
              label="カテゴリ"
              v-model="category"
              outlined/>
            <v-text-field
              label="URL"
              v-model="url"
              outlined/>
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="submit">投稿</v-btn>
          </v-row>
        </v-container>
        <v-card-actions>&nbsp;</v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
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
      errors: null
    }
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
      const response = await axios.post('/api/posts', formData)
      this.loading = false

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }
      this.reset()
      this.$emit('input', false)
      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
      this.$router.push(`/posts/${response.data.id}`)

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // メッセージ登録
      this.$store.commit('message/setContent', {
        content: '記事が投稿されました',
        timeout: 6000
      })

      this.$router.push(`/posts/${response.data.id}`)
    }
  }
}
</script>