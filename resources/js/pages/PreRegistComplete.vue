<template>
  <v-container id="regist">
    <v-card flat>
      <div>
        <v-layout justify-center class="headline">COMPLETE</v-layout>
        <v-layout justify-center>仮登録完了</v-layout>
      </div>
      <v-form @submit.prevent="register">
        <div class="login-dialog-input mt-4">
          <v-container>
            本登録メールを送信しました。
          </v-container>
          <v-row justify="center" align-content="center">
            <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="top">TOPへ</v-btn>
          </v-row>
        </div>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
export default {
  methods: {
    async top () {
      await this.$store.dispatch('screen/showLoading')
      this.$router.push('/')
      this.$router.go({path: this.$router.currentRoute.path, force: true})
    }
  }
}
</script>