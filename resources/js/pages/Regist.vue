<template>
  <v-container id="regist">
    <v-card flat>
      <div>
        <v-layout justify-center class="display-1">SIGN UP</v-layout>
        <v-layout justify-center>本登録</v-layout>
      </div>
      <div v-if="registerErrors" class="errors mt-2">
        <ul v-if="registerErrors.name"><li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li></ul>
        <ul v-if="registerErrors.email"><li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li></ul>
        <ul v-if="registerErrors.password"><li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li></ul>
      </div>
      <v-form @submit.prevent="register">
        <div class="login-dialog-input mt-2">
          <v-container>
            <v-text-field
              id="username"
              label="ニックネーム"
              prepend-icon="mdi-account"
              v-model="registerForm.name"
              outlined
              placeholder="xxx"/>
            <v-text-field
              id="password"
              :type="'password'"
              label="パスワード"
              v-model="registerForm.password"
              prepend-icon="mdi-lock"
              outlined
              placeholder="英数字 8桁以上"
              autocomplete="password"/>
            <v-text-field
              id="password-confirmation"
              :type="'password'"
              label="パスワード (確認)"
              v-model="registerForm.password_confirmation"
              prepend-icon="mdi-lock-reset"
              outlined
              placeholder="英数字 8桁以上"
              autocomplete="password"/>
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="register">登録</v-btn>
          </v-row>
        </div>
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
        id: '',
        name: '',
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
      this.registerForm.id = this.$route.params.id;
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)

      if (this.apiStatus) {
        this.$store.commit('message/setContent', {
          content: '登録処理が完了しました。',
          timeout: 4000
        });
        // トップページに移動する
        this.$router.push('/')
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