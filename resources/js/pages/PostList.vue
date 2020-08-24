<template>
  <v-container class="post_list pa-0">
    <v-row>
      <!-- メニュー -->
      <v-col xl="2" lg="3" md="3" sm="0" cols="0" class="menu">
        <div class="menu_layout px-5 py-1" v-if="isMenu">
          <SearchTextFilter @reset="reset" class="pt-1"/>
          <SearchFilter @reset="reset" class="pt-1"/>
        </div>
      </v-col>
      <!-- 投稿リスト -->
      <v-col xl="10" lg="9" md="9" sm="12" cols="12">
        <v-layout wrap class="post_layout">
          <Post v-for="post in viewposts" :key="post.id" :item="post" />
        </v-layout>
        <infinite-loading ref="infiniteLoading" @infinite="infiniteLoad">
          <div slot="circle">Loading...</div>
          <!-- ステータスがcompleteに更新されると下記が表示される -->
          <span slot="no-more">-----検索結果は以上です-----</span>
          <!-- 結果が存在しない場合下記が表示される -->
          <span slot="no-results">-----検索結果はありません-----</span>
        </infinite-loading>
        <v-btn v-if="isLogin" fab color="warning" class="button hidden-md-and-up add_post" @click="showPost">
          <v-icon>mdi-feather</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <PostForm ref="post"/>
  </v-container>
</template>

<script>
import Post from '../components/Post.vue'
import SearchFilter from '../components/SearchFilter.vue'
import SearchTextFilter from '../components/SearchTextFilter.vue'
import InfiniteLoading from 'vue-infinite-loading';
import PostForm from '../components/PostForm.vue'
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import { mapState } from 'vuex'

export default {
  components: {
    PostForm,
    Post,
    SearchFilter,
    SearchTextFilter,
    InfiniteLoading
  },
  data () {
    return {
      showForm: false,
      scrollY: 0,
      isMenu:true,
    }
  },
  mounted() {
    window.addEventListener('scroll', this.calculateScrollY);
  },
  beforeDestroy() {
   window.removeEventListener('scroll', this.calculateScrollY);
  },
  methods: {
  /*
   * infiniteLoad
   * 自動実行されるmethod  
   */
    async infiniteLoad() {
      const formData = new FormData()
      formData.append('category', this.getCategory =='' ? 'ALL': this.getCategory)
      formData.append('title', this.getText)
      var data = {
        page : this.page + 1,
        formData : formData,
      }
      const response = await this.$store.dispatch('post/fetchPage', data)
      
      if (response == null) {
          return false;
      }

      if (response.last_page >= this.page + 1) {
        this.$store.commit('post/setPage', this.page + 1);
        this.$store.commit('post/setLastPage', response.last_page);

        for (let i=0;i<response.data.length;i++) {
          this.viewposts.push(response.data[i]);
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
      this.$store.commit('post/setPosts', []);
      this.$refs.infiniteLoading.stateChanger.reset();
      this.scrollY = 0;
    },
    calculateScrollY() {
      if (window.innerWidth < 960) {
        if(55 > this.scrollY) {
          this.isMenu = true;
        } else if (window.scrollY < this.scrollY) {
          this.isMenu = false;
        } else {
          this.isMenu = true;
        }
        this.scrollY = window.scrollY
      }
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    getCategory () {
      return this.$store.getters['filter/category']
    },
    getText () {
      return this.$store.getters['filter/text']
    },
    ...mapState({
        viewposts: state => state.post.viewposts,
        page: state => state.post.page,
        lastPage: state => state.post.lastPage,
    })
  },
}
</script>