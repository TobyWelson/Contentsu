const actions = {
  showLoading (context) {
    context.commit('setShowLoading', true)
  },
  onMenuClick (context) {
    context.commit('onMenuClick')
  }
};

// screen ステートの値を更新する setUser ミューテーション
const mutations = {
  setShowLoading(state, isLoading) {
    state.isLoading = isLoading;
  },
  onMenuClick(state) {
    if (state.isMenu == false) {
      state.isMenu = true;
    } else {
      state.isMenu = false;
    }
  }
};

const state = {
  isLoading: false,
  isMenu: false
};

const getters = {
  loading: state => state.isLoading,
  menu: state => state.isMenu
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
