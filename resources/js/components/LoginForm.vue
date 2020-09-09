<template>
  <v-dialog v-model="isShowLoginDialog" max-width="600px">
    <v-card>
      <v-container>
        <v-layout justify-center class="headline">LOGIN</v-layout>
        <v-layout justify-center class="mb-1">ログイン</v-layout>
      </v-container>
      <v-container v-if="loginErrors" class="errors">
        <ul v-if="loginErrors.email"><li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li></ul>
        <ul v-if="loginErrors.password"><li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li></ul>
      </v-container>
      <v-form @submit.prevent="login">
        <v-container py-5>
          <v-container>
            <v-text-field
              id="login-email"
              label="ID（メールアドレス）"
              prepend-icon="mdi-email"
              v-model="loginForm.email"
              outlined
              placeholder="xxx@xxx.com"/>
            <v-text-field
              id="login-password"
              :type="isShowPassword ? 'text' : 'password'"
              label="パスワード"
              v-model="loginForm.password"
              prepend-icon="mdi-lock"
              :append-icon="isShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="isShowPassword= !isShowPassword"
              outlined
              placeholder="英数字 8桁以上"
              autocomplete="password"/>
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="login">ログイン</v-btn>
          </v-row>
          <v-row justify="center" align-content="center">
            <v-btn text v-on:click="regist">パスワードを忘れた方はこちら</v-btn>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
  </v-dialog>
</template>


<script>
import { mapState } from 'vuex'
import { SUCCESS } from '../util'

export default {
  data () {
    return {
      isShowLoginDialog: false,
      isShowPassword: false,
      loginForm: {
        email: '',
        password: ''
      }
    }
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      loginErrors: state => state.auth.loginErrorMessages,
    }),
  },
  methods: {
    async login () {
      // authストアのloginアクションを呼び出す
      var result = await this.$store.dispatch('auth/login', this.loginForm)
      if (result == SUCCESS) {
        this.$router.push('/').catch(err => {})
        this.isShowLoginDialog = false
        this.$store.commit('message/setContent', {
          content: 'ログインしました',
          timeout: 4000
        })
      }
    },
    regist () {
      this.isShowLoginDialog = false
      this.$router.push('/Regist')
    },
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
}
</script>