import mutations from './mutations'
import * as actions from './actions'
import * as getters from './getters'

const initialState = {
  posts: [],
  totalPosts: 0,
  selectAllField: false,
  selectedPosts: [],
}

export default {
  namespaced: true,

  state: initialState,

  getters: getters,

  actions: actions,

  mutations: mutations
}
