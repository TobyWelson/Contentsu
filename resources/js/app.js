import Vue from 'vue';
// ルーティングの定義をインポートする
import router from './router';
// ストアをインポートする
import store from './store';
// ルートコンポーネントをインポートする
import App from './App.vue';
// Axios ライブラリの設定
import './bootstrap'

new Vue({
  el: '#app',
  router, // ルーティングの定義を読み込む
  store, // ストアの定義を読み込む
  components: { App }, // ルートコンポーネントの使用を宣言する
  template: '<App />' // ルートコンポーネントを描画する
});

const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App />'
  })
}

createApp()