<template>
  <div>
    <v-navigation-drawer v-model="drawer" fixed temporary>
      <v-container>
        <v-list-item v-if="isLogin">
          <v-list-item-content>
            <v-list-item-title class="title grey--text text--darken-2"><v-icon>mdi-account</v-icon>{{ username }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-btn v-if="isLogin" outlined rounded @click="logout" class="font-weight-bold mb-3" width="100%" color="warning">ログアウト</v-btn>
        <v-btn v-if="!isLogin" outlined rounded @click="showLogin" class="font-weight-bold mb-2 mt-1" width="100%" color="warning">ログイン</v-btn>
        <v-btn v-if="!isLogin" depressed rounded @click="regist" class="font-weight-bold mb-3" width="100%" color="warning">新規登録</v-btn>
        <v-divider></v-divider>
      <v-list nav dense>
        <v-list-item v-for="item in items" :key="item.title" :to="item.to">
          <v-list-item-content><v-list-item-title v-text="item.title"/></v-list-item-content>
        </v-list-item>
      </v-list>
      </v-container>
    </v-navigation-drawer>
    <v-toolbar class="nav-tool">
      <div id="logo">
        <RouterLink class="navbar__brand" to="/">
          <img src="../../img/logo_contents_1.png" alt="コン転ツ" width="60px" height="55px"/>
        </RouterLink>
      </div>
      <div v-if="isLogin" class="hidden-sm-and-down">
        <v-icon>mdi-account</v-icon>{{ username }}
      </div>
      <v-spacer></v-spacer>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" class="hidden-md-and-up"/>
      <div class="hidden-sm-and-down">
        <v-btn v-if="isLogin" depressed rounded color="warning" class="font-weight-bold mx-1" @click="showForm = ! showForm">
          <v-icon small>ion-md-add</v-icon>転載
        </v-btn>
        <v-btn v-if="isLogin" outlined rounded color="warning" class="font-weight-bold" @click="logout">ログアウト</v-btn>
        <v-btn v-if="!isLogin" outlined rounded color="warning" class="font-weight-bold" @click="showLogin">ログイン</v-btn>  
        <v-btn v-if="!isLogin" depressed rounded color="warning" class="font-weight-bold" @click="regist">新規登録</v-btn>
      </div>
    </v-toolbar>
    <PostForm v-model="showForm" />
    <LoginForm ref="dlg"></LoginForm>
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
      showForm: false,
      drawer: null,
      items: [
        { title: "TOP", to: "/"},
        { title: "FAQ", to: "/Regist"},
        { title: "利用規約", to: "/Regist"},
        { title: "特定商取引法に基づく表記", to: "/Regist"},
        { title: "プライバシーポリシー", to: "/Regist"},
        { title: "お問い合わせ", to: "/Regist"},
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
    regist () {
      this.$router.push('/Regist')
    },
    showLogin() {
      this.$refs.dlg.isDialogShow = true
    }
  }
}
</script>