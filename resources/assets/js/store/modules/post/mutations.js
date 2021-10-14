import * as types from './mutation-types'

export default {
  [types.BOOTSTRAP_POSTS](state, posts) {
    state.posts = posts
  },

  [types.SET_TOTAL_POSTS](state, totalPosts) {
    state.totalPosts = totalPosts
  },

  [types.ADD_POST](state, data) {
    state.posts.push(data.post)
  },

  [types.UPDATE_POST](state, data) {
    let pos = state.posts.findIndex((post) => post.id === data.post.id)
    state.posts[pos] = data.post
  },

  [types.UPDATE_THUMBNAIL_POST](state, data) {
    let pos = state.posts.findIndex((post) => post.id === data.post.id)
    state.posts[pos]['thumbnail'] = data.post
  },

  [types.DELETE_POST](state, id) {  
    let index = state.posts.findIndex((post) => post.id === id)
    state.posts.splice(index, 1)
  },

  [types.DELETE_MULTIPLE_POSTS](state, selectedPosts) {
    selectedPosts.forEach((item) => {
      let index = state.posts.findIndex((_item) => _item.id === item.id)
      state.posts.splice(index, 1)
    })

    state.selectedPosts = []
  },

  [types.SET_SELECTED_POSTS](state, data) {
    state.selectedPosts = data
  },

  [types.SET_SELECT_ALL_STATE](state, data) {
    state.selectAllField = data
  },

}
