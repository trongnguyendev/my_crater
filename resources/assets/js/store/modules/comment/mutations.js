import * as types from './mutation-types'

export default {
  [types.BOOTSTRAP_COMMENTS](state, comments) {
    state.comments = comments
  },

  [types.SET_TOTAL_COMMENTS](state, totalComments) {
    state.totalComments = totalComments
  },

  [types.ADD_COMMENT](state, data) {
    state.comments.push(data.comment)
  },

  [types.UPDATE_COMMENT](state, data) {
    let pos = state.comments.findIndex((comment) => comment.id === data.comment.id)
    state.comments[pos] = data.comment
  },


  [types.DELETE_COMMENT](state, id) {  
    let index = state.comments.findIndex((comment) => comment.id === id)
    state.comments.splice(index, 1)
  },


}
