<template>
  <div>
    <SearchFilter @reset="reset"/>
    <v-layout wrap class="post_layout">
      <Post
        v-for="post in viewposts"
        :key="post.id"
        :item="post"
      />
    </v-layout>
    <infinite-loading ref="infiniteLoading" @infinite="infiniteLoad">
      <div slot="circle">Loading...</div>
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
import SearchFilter from '../components/SearchFilter.vue'
import InfiniteLoading from 'vue-infinite-loading';
import PostForm from '../components/PostForm.vue'
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import { mapState } from 'vuex'

export default {
  components: {
    PostForm,
    Post,
    SearchFilter,
    InfiniteLoading
  },
  data () {
    return {
      showForm: false,
      posts: [],
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
      var data = {
        page: this.page,
        category: this.getCategory =='' ? 'ALL': this.getCategory,
      }

      const response = await this.$store.dispatch('post/fetchPage', data)
      
      // レスポンス無し
      if (this.response == UNPROCESSABLE_ENTITY) {
        this.$refs.infiniteLoading.stateChanger.complete();
        return false;
      }

      this.posts = response.data
      this.lastPage = response.last_page

      // 記事取得無し
      if (this.posts == null) {
        this.$refs.infiniteLoading.stateChanger.complete();
        return false;
      }

      for (let i=0;i<this.posts.length;i++) {
        this.viewposts.push(this.posts[i]);
      }
      this.$refs.infiniteLoading.stateChanger.loaded();
      if (this.lastPage == this.page) {
        this.$refs.infiniteLoading.stateChanger.complete();
        return false;
      }
      this.page = this.page + 1;
    },
    showPost() {
      this.$refs.post.isShowPostDialog = true;
    },
    reset() {
      this.page = 1;
      this.posts = [];
      this.$store.commit('post/setPosts', []);
      this.$refs.infiniteLoading.stateChanger.reset();
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    getCategory () {
      return this.$store.getters['filter/category']
    },
    ...mapState({
        viewposts: state => state.post.viewposts,
    }),
  },
}
</script>