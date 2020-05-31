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
    const response = await axios.post('/api/login', data)
    context.commit('setUser', response.data)
  },
  async logout (context) {
    const response = await axios.post('/api/logout')
    context.commit('setUser', null)
  }
};

// user ステートの値を更新する setUser ミューテーション
const mutations = {
  setUser (state, user) {
    state.user = user
  }
};

// ログイン済みユーザーを保持する user ステート
const state = {
  user: null
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
