import Vue from 'vue';
import VueRouter from 'vue-router';

// ページコンポーネントをインポートする
import PostList from './pages/PostList.vue';
import Login from './pages/Login.vue';
import SystemError from './pages/errors/System.vue'
import PostDetail from './pages/PostDetail.vue'
import Vuetify from '../../node_modules/vuetify/'

// ナビゲーションガード
import store from './store'
import NotFound from './pages/errors/NotFound.vue'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter);
Vue.use(Vuetify);

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: PostList
  },
  {
    path: '/posts/:id',
    component: PostDetail,
    props: true
  },
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/500',
    component: SystemError
  },
  {
    path: '*',
    component: NotFound
  }
];

// VueRouterインスタンスを作成する
// ページ遷移した後のスクロール位置をコントロールする箇所
const router = new VueRouter({
  mode: 'history',
  // scrollBehavior () {
  //   return { x: 0, y: 0 }
  // },
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;
