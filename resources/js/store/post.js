import { OK } from '../util'

 // 転載記事 API を呼び出す アクション
const actions = {
   // 投稿内容取得
  async fetch (context, data) {
    const response = await axios.get(`/api/posts/${data}`)
    
    if (response.status === OK) {
      return response.data
    } else {
        context.commit('error/setCode', response.status, { root: true })
    }
  },
  // 投稿内容取得
  async fetchPage (context, data) {
    const response = await axios.get(`/api/posts/?page=${data}`)

    if (response.status === OK) {
      return response.data
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  }
};

export default {
  namespaced: true,
  actions
};
