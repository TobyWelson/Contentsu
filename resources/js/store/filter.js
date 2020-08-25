const actions = {
  categoryfilter (context, data) {
    context.commit('setCategory', categories[`${data}`]);
  }
};

// category ステートの値を更新する setUser ミューテーション
const mutations = {
  setCategory(state, category) {
    state.category = category;
  },
  setText(state, text) {
    state.text = text;
  },
};

// 検索機能のカテゴリ状態を保持する category ステート
const state = {
  category: '',
  text: '',
  categories: [
    '新着順',
    'ランダム表示',
    'アニメ',
    'ゲーム',
    'ドラマ',
  ],
  registCategories: [
    'アニメ',    // 1
    'ゲーム',    // 2
    'ドラマ',    // 3
  ]
};

const getters = {
  category: state => state.category,
  text: state => state.text,
  categories: state => state.categories,
};

export default {
  namespaced: true,
  actions,
  state,
  getters,
  mutations,
};
