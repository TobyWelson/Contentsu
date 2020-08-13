import { UNPROCESSABLE_ENTITY, CREATED, FAILURE, SUCCESS  } from '../util'

// コメントAPI を呼び出す アクション
const actions = {
   // 投稿
  async add (context, data) {
    var response = await axios.post(`/api/posts/${data.id}/comments`, {
      content: data.comment
    })
    // バリデーションエラー
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setCommentsErrorMessages', response.data.errors)
      return FAILURE
    }
    // その他のエラー
    if (response.status !== CREATED) {
      this.$store.commit('error/setCode', response.status)
      return FAILURE
    }
    return response.data
  }
};

// category ステートの値を更新する setUser ミューテーション
const mutations = {
  setCommentsErrorMessages (state, messages) {
    state.commentsErrorMessages = messages
  }
};

// 検索機能のカテゴリ状態を保持する category ステート
const state = {
  commentsErrorMessages: null
};

export default {
  namespaced: true,
  actions,
  mutations,
  state
};
