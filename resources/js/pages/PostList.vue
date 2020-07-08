<template>
  <div>
    <v-layout wrap>
      <Post  
        v-for="post in postsview"
        :key="post.id"
        :item="post"
      />
    </v-layout>
    <infinite-loading ref="infiniteLoading" spinner="circle" @infinite="infiniteLoad">
      // ステータスがcompleteに更新されると下記が表示される
      <span slot="no-more">-----検索結果は以上です-----</span>
      // 結果が存在しない場合下記が表示される
      <span slot="no-results">-----検索結果はありません-----</span>
    </infinite-loading>

    <v-btn id="addPost" v-if="isLogin" fab color="warning" class="button hidden-md-and-up" @click="showPost">
      <v-icon>mdi-feather</v-icon>
    </v-btn>
    <PostForm ref="post"/>
  </div>
</template>

<script>
import Post from '../components/Post.vue'
import InfiniteLoading from 'vue-infinite-loading';
import PostForm from '../components/PostForm.vue'

export default {
  components: {
    PostForm,
    Post,
    InfiniteLoading
  },
  data () {
    return {
      showForm: false,
      posts: [],
      postsview: [],
      page: 1,
      lastPage: 0
    }
  },
  methods: {
  /*
   * infiniteLoad
   * 自動実行されるmethod  
   */
    async infiniteLoad() {
      const posts = await this.$store.dispatch('post/fetchPage', this.page)
      this.posts = posts.data
      this.lastPage = posts.last_page
      for (let i=0;i<this.posts.length;i++) {
        this.postsview.push(this.posts[i]);
      }
      this.$refs.infiniteLoading.stateChanger.loaded();
      if (this.lastPage == this.page) {
        this.$refs.infiniteLoading.stateChanger.complete();
      }
      this.page = this.page + 1;
    },
    showPost() {
      this.$refs.post.isShowPostDialog = true
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    }
  },
}
</script>