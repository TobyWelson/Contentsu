<template>
  <v-container max-width="600px">
    <v-card flat color="amber lighten-5">
      <v-container>
        <v-layout justify-center class="display-1">SIGN UP</v-layout>
        <v-layout justify-center class="mb-1">新規登録</v-layout>
      </v-container>
      <v-container v-if="registerErrors" class="errors">
        <ul v-if="registerErrors.name"><li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li></ul>
        <ul v-if="registerErrors.email"><li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li></ul>
        <ul v-if="registerErrors.password"><li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li></ul>
      </v-container>
      <v-form @submit.prevent="register">
        <v-container class="login-dialog-input" py-10>
          <v-container>
            <v-text-field
              id="username"
              label="ニックネーム"
              prepend-icon="mdi-account"
              v-model="registerForm.name"
              outlined
              placeholder="xxx"/>
            <v-text-field
              id="email"
              label="ID（メールアドレス）"
              prepend-icon="mdi-email"
              v-model="registerForm.email"
              outlined
              placeholder="xxx@xxx.com"/>
            <v-text-field
              id="password"
              :type="'password'"
              label="パスワード"
              v-model="registerForm.password"
              width="80%"
              prepend-icon="mdi-lock"
              outlined
              placeholder="英数字 8桁以上"/>
            <v-text-field
              id="password-confirmation"
              :type="'password'"
              label="パスワード (確認)"
              v-model="registerForm.password_confirmation"
              width="80%"
              prepend-icon="mdi-lock-reset"
              outlined
              placeholder="英数字 8桁以上"/>
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="register">登録</v-btn>
          </v-row>
        </v-container>
        <v-card-actions>&nbsp;</v-card-actions>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  data () {
    return {
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: {
    ...mapState({
    apiStatus: state => state.auth.apiStatus,
    registerErrors: state => state.auth.registerErrorMessages
  }),
  ...mapGetters({
      isLogin: 'auth/check'
    })
  },

  methods: {
    async register () {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)

      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/')
      }
    },
    async logout () {
      await this.$store.dispatch('auth/logout')

      if (this.apiStatus) {
        this.$router.push('/login')
      }
    },
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
      this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
}
</script>