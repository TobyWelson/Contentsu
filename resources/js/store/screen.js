const actions = {
  showLoading (context) {
    context.commit('setShowLoading', true)
  }
};

// screen ステートの値を更新する setUser ミューテーション
const mutations = {
  setShowLoading(state, isLoading) {
    state.isLoading = isLoading;
  }
};

const state = {
  isLoading: false,
};

const getters = {
  loading: state => state.isLoading
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
