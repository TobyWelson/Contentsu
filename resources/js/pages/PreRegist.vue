<template>
  <v-container id="regist">
    <v-card flat>
      <v-overlay :value="loading">
        <v-progress-circular indeterminate :size="80" :width="7" color="deep-orange lighten-2"></v-progress-circular>
      </v-overlay>
      <div>
        <v-layout justify-center class="headline">PROVISIONAL SIGN UP</v-layout>
        <v-layout justify-center>仮登録</v-layout>
      </div>
      <div v-if="registerErrors" class="errors mt-3">
        <ul v-if="registerErrors.email"><li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li></ul>
      </div>
      <v-form @submit.prevent="register">
        <div class="login-dialog-input mt-4">
          <v-container>
            <v-text-field
              id="email"
              label="ID（メールアドレス）"
              prepend-icon="mdi-email"
              v-model="registerForm.email"
              outlined
              placeholder="xxx@xxx.com"/>
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="preRegister">メール送信</v-btn>
          </v-row>
        </div>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { SUCCESS } from '../util'

export default {
  data () {
    return {
      registerForm: {
        email: '',
      },
      loading: false,
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
    async preRegister () {
      this.loading = true
      // authストアのresigterアクションを呼び出す
      const response = await this.$store.dispatch('auth/preRegister', this.registerForm)

      if (response == SUCCESS) {
        // トップページに移動する
        this.$router.push('/preRegistComplete')
      }
      this.loading = false;
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