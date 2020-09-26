<template>
  <div>
    <!-- SPメニュー -->
    <v-navigation-drawer v-model="drawer" fixed temporary right>
      <v-container>
        <v-list-item v-if="isLogin">
          <router-link to="/myPage">
            <v-list-item-content>
              <v-list-item-title class="title grey--text text--darken-2"><v-icon>mdi-account</v-icon>{{ username }}</v-list-item-title>
            </v-list-item-content>
          </router-link>
        </v-list-item>
        <v-btn v-if="isLogin" outlined rounded @click="logout" class="font-weight-bold mb-3" width="100%" color="warning">ログアウト</v-btn>
        <v-btn v-if="!isLogin" outlined rounded @click="showLogin" class="font-weight-bold mb-2 mt-1" width="100%" color="warning">ログイン</v-btn>
        <v-btn v-if="!isLogin" depressed rounded @click="preRegist" class="font-weight-bold mb-3" width="100%" color="warning">新規登録</v-btn>
        <v-divider></v-divider>
        <v-list nav dense>
          <v-list-item v-for="item in items" :key="item.title" :to="item.to">
            <v-list-item-content><v-list-item-title v-text="item.title"/></v-list-item-content>
          </v-list-item>
        </v-list>
      </v-container>
    </v-navigation-drawer>
    <v-toolbar class="nav_tool">
      <div id="logo">
        <router-link class="navbar__brand" @click.native="reload" to="/">
          <img src="../../img/logo.png" alt="コン転ツ" height="55px"/>
        </router-link>
      </div>
      <div v-if="isLogin" class="hidden-sm-and-down">
        <router-link to="/myPage">
          <v-icon>mdi-account</v-icon>{{ username }}
        </router-link>
      </div>
      <v-spacer></v-spacer>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" class="hidden-md-and-up"/>
      <div class="hidden-sm-and-down">
        <v-btn v-if="isLogin" depressed rounded color="warning" class="font-weight-bold mx-1" @click="showPost">
          <v-icon small>mdi-feather</v-icon>投稿
        </v-btn>
        <v-btn v-if="isLogin" outlined rounded color="warning" class="font-weight-bold" @click="logout">ログアウト</v-btn>
        <v-btn v-if="isLogin" outlined rounded color="warning" class="font-weight-bold" @click="withdrawal">退会</v-btn>
        <v-btn v-if="!isLogin" outlined rounded color="warning" class="font-weight-bold" @click="showLogin">ログイン</v-btn>  
        <v-btn v-if="!isLogin" depressed rounded color="warning" class="font-weight-bold" @click="preRegist">新規登録</v-btn>
      </div>
    </v-toolbar>
    <PostForm ref="post"/>
    <LoginForm ref="login"/>
  </div>
</template>

<script>
import PostForm from './PostForm.vue'
import LoginForm from './LoginForm.vue'
import { SUCCESS } from '../util'

export default {
  components: {
    PostForm,
    LoginForm
  },
  data () {
    return {
      drawer: null,
      items: [
        { title: "TOP", to: "/"},
        { title: "FAQ", to: "/preRegist"},
        { title: "利用規約", to: "/preRegist"},
        { title: "特定商取引法に基づく表記", to: "/preRegist"},
        { title: "プライバシーポリシー", to: "/preRegist"},
        { title: "お問い合わせ", to: "/preRegist"},
        { title: "退会", to: "/withdrawal"},
      ]
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    username () {
      return this.$store.getters['auth/username']
    }
  },
  methods: {
    async logout () {
      var result = await this.$store.dispatch('auth/logout')
      if (result == SUCCESS) {
        this.drawer = false
        this.$store.commit('message/setContent', {
          content: 'ログアウトしました',
          timeout: 2000
        })
      }
    },
    preRegist () {
      this.$router.push('/preRegist')
    },
    withdrawal () {
      this.$router.push('/withdrawal')
    },
    showLogin() {
      this.$refs.login.isShowLoginDialog = true
    },
    showPost() {
      this.$refs.post.isShowPostDialog = true
    },
    async reload() {
      await this.$store.dispatch('screen/showLoading')
      this.$router.go({path: this.$router.currentRoute.path, force: true})
    },
  }
}
</script>