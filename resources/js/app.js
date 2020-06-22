import Vue from 'vue';
// ルーティングの定義をインポートする
import router from './router';
// ストアをインポートする
import store from './store';
// ルートコンポーネントをインポートする
import App from './App.vue';
// Axios ライブラリの設定
import './bootstrap';
// vuetify ライブラリの設定
import vuetify from './vuetify'

const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router,
    store,
    vuetify,
    components: { App },
    template: '<App />'
  })
}

createApp();
