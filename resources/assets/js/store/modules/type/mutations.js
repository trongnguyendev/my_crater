import * as types from './mutation-types'

export default {
  [types.BOOTSTRAP_TYPES](state, types) {
    state.types = types
  },

  [types.SET_TOTAL_TYPES](state, totalTypes) {
    state.totalTypes = totalTypes
  },

  [types.ADD_TYPE](state, data) {
    state.types.push(data.type)
  },

  [types.UPDATE_TYPE](state, data) {
    let pos = state.types.findIndex((type) => type.id === data.type.id)
    state.types[pos] = data.type
  },

  [types.DELETE_TYPE](state, id) {  
    let index = state.types.findIndex((type) => type.id === id)
    state.types.splice(index, 1)
  },

  [types.DELETE_MULTIPLE_TYPES](state, selectedTypes) {
    selectedTypes.forEach((type) => {
      let index = state.types.findIndex((_type) => _type.id === type.id)
      state.types.splice(index, 1)
    })

    state.selectedTypes = []
  },

  [types.SET_SELECTED_TYPES](state, data) {
    state.selectedTypes = data
  },

  [types.SET_SELECT_ALL_STATE](state, data) {
    state.selectAllField = data
  },

}
