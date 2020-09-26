import Vue from 'vue';
import VueRouter from 'vue-router';

// ページコンポーネントをインポートする
import PostList from './pages/PostList.vue';
import PreRegist from './pages/PreRegist.vue';
import PreRegistComplete from './pages/PreRegistComplete.vue';
import Regist from './pages/Regist.vue';
import Withdrawal from './pages/Withdrawal.vue';
import SystemError from './pages/errors/System.vue';
import PostDetail from './pages/PostDetail.vue';
import Vuetify from '../../node_modules/vuetify/';
import MyPage from './pages/MyPage.vue';

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
    component: PostList,
    props: route => {
      const page = route.query.page
      return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    }
  },
  {
    path: '/posts/:id',
    component: PostDetail,
    props: true
  },
  {
    path: '/regist/:id',
    component: Regist,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/preRegist',
    component: PreRegist,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/preRegistComplete',
    component: PreRegistComplete,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/withdrawal',
    component: Withdrawal,
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/myPage',
    component: MyPage,
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check']) {
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
const router = new VueRouter({
  mode: 'history',
  scrollBehavior () {
    return { x: 0, y: 0 }
  },
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;
