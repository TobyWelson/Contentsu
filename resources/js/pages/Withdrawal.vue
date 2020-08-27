<template>
  <v-container id="withdrawal">
    <v-card flat>
      <v-overlay :value="loading">
        <v-progress-circular indeterminate :size="80" :width="7" color="deep-orange lighten-2"></v-progress-circular>
      </v-overlay>
      <div>
        <v-layout justify-center class="display-1">WITHDRAWAL</v-layout>
        <v-layout justify-center>退会</v-layout>
      </div>
      <br/>
      <p>
        いつもコン転ツのご利用ありがとうございます。<br/>
        こちらは、コン転ツ会員の退会手続きです。
      </p>
      <div>
        <h2 style="color: red;">
          下記のコン転ツ会員情報がすべてご利用できなくなります。ご承知おき下さい。
        </h2>
        <br/>
        <ul style="list-style: none;">
          <li><v-icon small>mdi-feather</v-icon>転載記事の投稿</li>
          <li><v-icon small>mdi-heart</v-icon>いいね機能</li>
          <li><v-icon small>mdi-heart</v-icon>コメント機能</li>
        </ul>
      </div>
      <br/>
      <h2 style="color: red;">
        また、転載しました投稿記事、コメント、いいねは全て抹消されます。
      </h2>
      <br/>
      <v-row justify="center" align-content="center">
        <v-btn depressed rounded width="90%" height="45" color="warning" class="font-weight-bold title" v-on:click="withdrawaler">退会する</v-btn>
        <v-btn outlined rounded width="90%" height="45" class="font-weight-bold title" @click.native="reload" to="/">キャンセル</v-btn>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { mapState } from 'vuex'

export default {
  computed: {
    ...mapState({
    apiStatus: state => state.auth.apiStatus,
   }),
  },
  data () {
    return {
      loading: false,
    }
  },
  methods: {
    async withdrawaler () {
      this.loading = true;
      await this.$store.dispatch('auth/withdrawaler');
      this.loading = false;
      if (this.apiStatus) {
        this.$store.commit('post/setLastPage', 0);
        this.$store.commit('post/setPage', 0);
        this.$store.commit('post/setPosts', []);
        this.$router.push('/').catch(err => {});
        this.$store.commit('message/setContent', {
          content: '退会手続きを完了しました。ご利用ありがとうございました。',
          timeout: 4000
          });
      } else {
        this.$store.commit('message/setContent', {
          content: '退会手続きに失敗しました。',
          timeout: 4000
        });
      }
    },
  },
  async reload() {
    await this.$store.dispatch('screen/showLoading')
    this.$router.go({path: this.$router.currentRoute.path, force: true})
  }
}
</script>