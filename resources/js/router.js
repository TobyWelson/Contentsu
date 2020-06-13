import Vue from 'vue';
import VueRouter from 'vue-router';

// ページコンポーネントをインポートする
import PostList from './pages/PostList.vue';
import Login from './pages/Login.vue';
import SystemError from './pages/errors/System.vue'
import PostDetail from './pages/PostDetail.vue'

// ナビゲーションガード
import store from './store'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter);

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
  }
];

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history', // ★ 追加
    routes
});

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;
