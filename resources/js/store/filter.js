const actions = {
  categoryfilter (context, data) {
    context.commit('setCategory', categories[`${data}`]);
  }
};

// category ステートの値を更新する setUser ミューテーション
const mutations = {
  setCategory(state, category) {
    state.category = category;
  }
};

// 検索機能のカテゴリ状態を保持する category ステート
const state = {
  category: '',
  categories: [
    'ALL',      // 0
    'アニメ',    // 1
    'ゲーム',    // 2
    'ドラマ',    // 3
  ]
};

const getters = {
  category: state => state.category,
  categories: state => state.categories,
};

export default {
  namespaced: true,
  actions,
  state,
  getters,
  mutations,
};
