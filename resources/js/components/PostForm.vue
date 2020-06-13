<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">URL転載</h2>
    <div v-show="loading" class="panel">
      <Loader>記事を投稿中...</Loader>
    </div>
    <form v-show="! loading" class="form" @submit.prevent="submit">
      <div class="errors" v-if="errors">
        <ul v-if="errors.title">
          <li v-for="msg in errors.title" :key="msg">{{ msg }}</li>
        </ul>
        <ul v-if="errors.title">
          <li v-for="msg in errors.category" :key="msg">{{ msg }}</li>
        </ul>
        <ul v-if="errors.title">
          <li v-for="msg in errors.url" :key="msg">{{ msg }}</li>
        </ul>
      </div>
      <p>タイトル</p>
      <input class="form__title" v-model="title" type="text">
      <p>カテゴリ</p>
      <input class="form__category" v-model="category" type="text">
      <p>ＵＲＬ</p>
      <input class="form__url" v-model="url" type="text">
      <div class="form__button">
        <button type="submit" class="button button--inverse">投稿</button>
      </div>
    </form>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Loader from './Loader.vue'

export default {
  components: {
    Loader
  },
  props: {
    value: {
      type: Boolean,
      required: true
    }
  },
  data () {
    return {
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