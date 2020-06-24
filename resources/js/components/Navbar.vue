<template>
  <nav class="navbar">
    <RouterLink class="navbar__brand" to="/">
      <img src="../../img/logo_contents.jpg" alt="コン転ツ" width="180" height="80"/>
    </RouterLink>
    <div class="navbar__menu">
      <div v-if="isLogin" class="navbar__item">
        <button class="button" @click="showForm = ! showForm">
          <i class="icon ion-md-add"></i>
          URL転載
        </button>
      </div>
      <span v-if="isLogin" class="navbar__item">
        {{ username }}
      </span>
      <button v-if="isLogin" class="navbar__item button button--link" @click="logout">ログアウト</button>
      <div v-else class="navbar__item">
        <RouterLink class="button button--link" to="/login">
          ログイン / 新規登録
        </RouterLink>
      </div>
    </div>
    <PostForm v-model="showForm" />
  </nav>
</template>

<script>
import PostForm from './PostForm.vue'

export default {
  components: {
    PostForm
  },
  data () {
    return {
      showForm: false
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    username () {
      return this.$store.getters['auth/username']
    }
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')

      this.$router.push('/login')
    }
  }
}
</script>