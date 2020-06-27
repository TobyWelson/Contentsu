<template>
  <v-dialog v-model="isDialogShow" max-width="600px">
    <v-card color="amber lighten-5">
      <v-container>
        <v-layout justify-center class="display-1">LOGIN</v-layout>
        <v-layout justify-center class="mb-1">ログイン</v-layout>
      </v-container>
      <v-container v-if="loginErrors" class="errors">
        <ul v-if="loginErrors.email"><li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li></ul>
        <ul v-if="loginErrors.password"><li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li></ul>
      </v-container>
      <v-form @submit.prevent="login">
        <v-container class="login-dialog-input" py-10>
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
              :type="isPasswordShow ? 'text' : 'password'"
              label="パスワード"
              v-model="loginForm.password"
              width="80%"
              prepend-icon="mdi-lock"
              :append-icon="isPasswordShow ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="isPasswordShow= !isPasswordShow"
              outlined
              placeholder="英数字 8桁以上"/>
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="login">ログイン</v-btn>
          </v-row>
          <v-row justify="center" align-content="center" class="mt-5">
            <v-btn text v-on:click="regist">新規登録はこちら</v-btn>
          </v-row>
        </v-container>
        <v-card-actions>&nbsp;</v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>


<script>
import { mapState } from 'vuex'

export default {
  data () {
    return {
      isDialogShow: false,
      isPasswordShow: false,
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
      await this.$store.dispatch('auth/login', this.loginForm)
    },
    regist () {
      this.isDialogShow = false
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