<template>
  <nav class="navbar">
    <RouterLink id="logo" class="navbar__brand" to="/">
      <img src="../../img/logo_contents_1.png" alt="コン転ツ" width="75px"/>
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
        <v-btn depressed rounded @click="showLogin">ログイン</v-btn>
        <LoginForm ref="dlg"></LoginForm>
        <v-btn depressed rounded color="warning" class="font-weight-bold " @click="regist">新規登録</v-btn>
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
      this.$store.commit('message/setContent', {
        content: 'ログアウトしました',
        timeout: 2000
      })
    },
    regist () {
      this.$router.push('/Regist')
    },
    showLogin() {
      this.$refs.dlg.isDialogShow = true
    }
  }
}
</script>