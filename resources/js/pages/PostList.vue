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
    }
  },
  methods: {
  /*
   * infiniteLoad
   * 自動実行されるmethod  
   */
    async infiniteLoad() {
      var data = {
        page: this.page + 1,
        category: this.getCategory =='' ? 'ALL': this.getCategory,
      }
      const response = await this.$store.dispatch('post/fetchPage', data)
      
      if (response.data.posts != null
        || response.last_page >= this.page + 1) {

        this.posts = response.data
        this.$store.commit('post/setPage', this.page + 1);
        this.$store.commit('post/setLastPage', response.last_page);

        for (let i=0;i<this.posts.length;i++) {
          this.viewposts.push(this.posts[i]);
        }
        this.$refs.infiniteLoading.stateChanger.loaded();
        
      } else {
        this.$refs.infiniteLoading.stateChanger.complete();
        return false;
      }
    },
    showPost() {
      this.$refs.post.isShowPostDialog = true;
    },
    reset() {
      this.$store.commit('post/setLastPage', 0);
      this.$store.commit('post/setPage', 0);
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
        page: state => state.post.page,
        lastPage: state => state.post.lastPage,
    }),
  },
}
</script>