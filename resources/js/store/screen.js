// screen ステートの値を更新する setUser ミューテーション
const mutations = {
  setCurrent(state, current) {
    state.current = current;
  },
  setPrev(state, prev) {
    state.prev = prev;
  }
};

// 画面状態を保持する screen ステート
const state = {
  current: '',
  prev: '',
};

const getters = {
  current: state => state.current,
  prev: state => state.prev,
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
};
