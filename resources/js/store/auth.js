import { OK } from '../util'

/**
 * アクション→コミットでミューテーション呼び出し→ステート更新
 */
 // 会員登録 API を呼び出す register アクション
const actions = {
  async register (context, data) {
    const response = await axios.post('/api/register', data)
    context.commit('setUser', response.data)
  },
  async login (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data)
      .catch(err => err.response || err)
  
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
  
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },
  async logout (context) {
    const response = await axios.post('/api/logout')
    context.commit('setUser', null)
  },
  async currentUser (context) {
    const response = await axios.get('/api/user')
    const user = response.data || null
    context.commit('setUser', user)
  }
};

// user ステートの値を更新する setUser ミューテーション
const mutations = {
  setUser (state, user) {
    state.user = user
  },
  setApiStatus (state, status) {
    state.apiStatus = status
  }
};

// ログイン済みユーザーを保持する user ステート
const state = {
  user: null,
  apiStatus: null
};

const getters = {
  check: state => !! state.user,
  username: state => state.user ? state.user.name : ''
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
