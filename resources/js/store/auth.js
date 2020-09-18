import { OK, CREATED, NO_CONTENT, UNPROCESSABLE_ENTITY, SUCCESS, FAILURE } from '../util'


/**
 * アクション→コミットでミューテーション呼び出し→ステート更新
 */
 // 会員登録 API を呼び出す register アクション
const actions = {
  // 仮会員登録
  async preRegister (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/pre-register', data)
    
    if (response.status === CREATED) {
      context.commit('setApiStatus', true)
      return SUCCESS
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
    return FAILURE
  },

   // 会員登録
  async register (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/register', data)

    if (response.status === CREATED) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return SUCCESS
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
    return FAILURE
  },

  // ログイン
  async login (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data)
  
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return SUCCESS
    }
    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
    return FAILURE
  },

  // ログアウト
  async logout (context) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return SUCCESS
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
    return FAILURE
  },

  // ログインユーザーチェック
  async currentUser (context) {
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
    const user = response.data || null

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', user)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  // 退会
  async withdrawaler (context) {
    context.commit('setApiStatus', null)
    const response = await axios.delete('/api/withdrawaler')

    if (response.status === NO_CONTENT) {
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return SUCCESS
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
    return FAILURE
  },

};

// user ステートの値を更新する setUser ミューテーション
const mutations = {
  setUser (state, user) {
    state.user = user
  },
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  }
};

// ログイン済みユーザーを保持する user ステート
const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null
};

const getters = {
  check: state => !! state.user,
  username: state => state.user ? state.user.name : '',
  userid: state => state.user ? state.user.id : ''
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
