import { OK, UNPROCESSABLE_ENTITY, CREATED, FAILURE  } from '../util'

// 転載記事 API を呼び出す アクション
const actions = {
   // 投稿内容取得
  async fetch (context, data) {
    var res = await axios.get(`/api/posts/${data.id}/show`)
    const response = res;
    if (response.status === OK) {
      return response.data
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },
  // 投稿内容取得
  async fetchPage (context, data) {
    const response = await axios.get(`/api/posts/${data.category}/?page=${data.page}`)
    if (response.status === OK) {
      return response.data
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },
  // 投稿
  async posts (context, data) {
    const response = await axios.post('/api/posts', data)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setPostsErrorMessages', response.data.errors)
      return FAILURE
    }
    if (response.status === CREATED) {
      return response.data.id
    } else {
      context.commit('error/setCode', response.status)
      return FAILURE
    }
  },
  // 削除
  async delete (context, data) {
    const response = await axios.delete(`/api/posts/${data}`)
    if (response.status !== OK) {
      context.commit('error/setCode', response.status)
      return FAILURE
    }
    return response.data.id
  }
};

// category ステートの値を更新する setUser ミューテーション
const mutations = {
  setPosts(state, viewposts) {
    state.viewposts = viewposts;
  },
  setPage(state, page) {
    state.page = page;
  },
  setLastPage(state, lastPage) {
    state.lastPage = lastPage;
  },
  setPostsErrorMessages (state, messages) {
    state.postsErrorMessages = messages
  }
};

// 検索機能のカテゴリ状態を保持する category ステート
const state = {
  viewposts: [],
  page: 0,
  lastPage: 0,
  postsErrorMessages: null
};

const getters = {
  viewposts: state => state.viewposts,
}

export default {
  namespaced: true,
  actions,
  mutations,
  state,
  getters
};
