<template>
  <v-container id="regist">
    <v-card flat>
      <div>
        <v-layout justify-center class="display-1">PROVISIONAL COMPLETE</v-layout>
        <v-layout justify-center>仮登録完了</v-layout>
      </div>
      <v-form @submit.prevent="register">
        <div class="login-dialog-input mt-2">
          <v-container>
            本登録メールを送信しました。確認し登録作業を進めてください。
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
    top () {
      this.$router.push('/')
    }
  }
}
</script>