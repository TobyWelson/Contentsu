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
        <v-btn @click="showLogin">ログイン / 新規登録</v-btn>
        <LoginForm ref="dlg"></LoginForm>
      </div>
    </div>
    <PostForm v-model="showForm" />
  </nav>
</template>

<script>
import PostForm from './PostForm.vue'
import LoginForm from './LoginForm.vue'

export default {
  components: {
    PostForm,
    LoginForm
  },
  data () {
    return {
      showForm: false,
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
      // this.$router.push('/')
    },
    showLogin() {
      this.$refs.dlg.isDialogShow = true
    }
  }
}
</script>