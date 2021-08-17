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

  // [types.UPDATE_ITEM](state, data) {
  //   let pos = state.items.findIndex((item) => item.id === data.item.id)
  //   console.log("-----pos--------------")
  //   console.log(pos)
  //   console.log("-------end pos------------")
  //   console.log(data.item.discount)
  //   console.log("-------post data------------")
  //   state.items[pos] = data.item
  // },

  // [types.DELETE_ITEM](state, id) {
  //   let index = state.items.findIndex((item) => item.id === id)
  //   state.items.splice(index, 1)
  // },

  // [types.DELETE_MULTIPLE_ITEMS](state, selectedItems) {
  //   selectedItems.forEach((item) => {
  //     let index = state.items.findIndex((_item) => _item.id === item.id)
  //     state.items.splice(index, 1)
  //   })

  //   state.selectedItems = []
  // },

  [types.SET_SELECTED_POSTS](state, data) {
    state.selectedPosts = data
  },

  [types.SET_SELECT_ALL_STATE](state, data) {
    state.selectAllField = data
  },

}
