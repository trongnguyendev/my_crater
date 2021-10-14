import mutations from './mutations'
import * as actions from './actions'
import * as getters from './getters'

const initialState = {
  types: [],
  totalTypes: 0,
  selectAllField: false,
  selectedTypes: [],
}

export default {
  namespaced: true,

  state: initialState,

  getters: getters,

  actions: actions,

  mutations: mutations
}
