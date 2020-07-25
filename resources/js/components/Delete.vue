<template>
  <v-dialog v-model="isShowDeleteDialog" max-width="600px">
    <v-card class="post_delete">
      <!-- "v-overlay"を有効にすると、ローディング描画が前面に表示されて、ローディング中に"削除ボタン"を連打などされなくなる。 
      　　　よかったらPostFormとかにも使いたい-->
      <v-overlay :value="loading">
        <!-- Loaderを使わなくても"v-progress-circular"で簡単にローディング描画できるのでどうでしょうか。 -->
        <v-progress-circular indeterminate :size="80" :width="7" color="deep-orange lighten-2"></v-progress-circular>
      </v-overlay>
      <v-container py-5>
        <div class="title text-center pb-3">
          本当に記事を削除しますか？
        </div>
        <v-row justify="center" align-content="center">
          <v-col class="d-flax" cols="12" sm="6">
            <v-btn color="warning" width="100%" height="45" class="font-weight-bold title" @click="onDeleteClick">削除</v-btn>
          </v-col>
          <v-col class="d-flax" cols="12" sm="6">
            <v-btn color="warning" width="100%" height="45" class="font-weight-bold title" @click="isShowDeleteDialog = false">キャンセル</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
import { FAILURE } from '../util'
import Loader from './Loader.vue'

export default {
  components: {
    Loader
  },
  props: {
    post_id: {
      type: String,
      required: true
    },
    post_title: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      isShowDeleteDialog: false,
      loading: false,
    }
  },
  methods: {
    async onDeleteClick () {
        this.loading = true;
        var result = await this.$store.dispatch('post/delete', this.post_id);
        this.loading = false;
        this.isShowDeleteDialog = false;
        if (result != FAILURE) {
          this.$store.commit('post/setLastPage', 0);
          this.$store.commit('post/setPage', 0);
          this.$store.commit('post/setPosts', []);
          this.$router.push('/').catch(err => {})
          this.$store.commit('message/setContent', {
            content: `記事(${this.post_title})が削除されました`,
            timeout: 4000
            })
        } else {
          this.$store.commit('message/setContent', {
            content: `記事(${this.post_title})の削除に失敗しました`,
            timeout: 4000
          })
        }
    }
  }
}
</script>