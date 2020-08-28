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
  category: 1,
  text: '',
  categories: [
    { categoryName: "新着順", id: 1 },
    { categoryName: "ランダム順", id: 2 },
    { categoryName: "アニメ", id: 3 },
    { categoryName: "ゲーム", id: 4 },
    { categoryName: "ドラマ", id: 5 },
    { categoryName: "ビジネス・マネー", id: 6 },
    { categoryName: "健康・ダイエット", id: 7 },
    { categoryName: "時事関連", id: 8 },
  ],
  registCategories: [
    { categoryName: "アニメ", id: 3 },
    { categoryName: "ゲーム", id: 4 },
    { categoryName: "ドラマ", id: 5 },
    { categoryName: "ビジネス・マネー", id: 6 },
    { categoryName: "健康・ダイエット", id: 7 },
    { categoryName: "時事関連", id: 8 },
  ]
};

const getters = {
  category: state => state.category,
  text: state => state.text,
  categories: state => state.categories,
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
};
