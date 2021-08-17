import * as types from './mutation-types'

export const fetchPosts = ({ commit, dispatch, state }, params) => {
  return new Promise((resolve, reject) => {
    window.axios
      .get(`/api/v1/posts`, { params })
      .then((response) => {
        commit(types.BOOTSTRAP_POSTS, response.data.posts.data)
        commit(types.SET_TOTAL_POSTS, response.data.postTotalCount)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const fetchPost = ({ commit, dispatch }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .get(`/api/v1/posts/${id}`)
      .then((response) => {
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

// export const addItem = ({ commit, dispatch, state }, data) => {
//   return new Promise((resolve, reject) => {
//     window.axios
//       .post('/api/v1/items', data)
//       .then((response) => {
//         commit(types.ADD_ITEM, response.data)
//         resolve(response)
//       })
//       .catch((err) => {
//         reject(err)
//       })
//   })
// }

// export const updateItem = ({ commit, dispatch, state }, data) => {
//   return new Promise((resolve, reject) => {
//     window.axios
//       .put(`/api/v1/items/${data.id}`, data)
//       .then((response) => {
//         console.log("-------------------")
//         console.log(state.items)
//         console.log("-------------------")
//         console.log(data)
//         console.log("-------------------")
//         console.log(response.data)
//         commit(types.UPDATE_ITEM, response.data)
//         resolve(response)
        
//       })
//       .catch((err) => {
//         reject(err)
//       })
//   })
// }

// export const deleteItem = ({ commit, dispatch, state }, id) => {
//   return new Promise((resolve, reject) => {
//     window.axios
//       .post(`/api/v1/items/delete`, id)
//       .then((response) => {
//         commit(types.DELETE_ITEM, id)
//         resolve(response)
//       })
//       .catch((err) => {
//         reject(err)
//       })
//   })
// }

// export const deleteMultipleItems = ({ commit, dispatch, state }, id) => {
//   return new Promise((resolve, reject) => {
//     window.axios
//       .post(`/api/v1/items/delete`, { ids: state.selectedItems })
//       .then((response) => {
//         commit(types.DELETE_MULTIPLE_ITEMS, state.selectedItems)
//         resolve(response)
//       })
//       .catch((err) => {
//         reject(err)
//       })
//   })
// }

export const setSelectAllState = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECT_ALL_STATE, data)
}

export const selectAllPosts = ({ commit, dispatch, state }) => {
  if (state.selectedPosts.length === state.items.length) {
    commit(types.SET_SELECTED_POSTS, [])
    commit(types.SET_SELECT_ALL_STATE, false)
  } else {
    let allPostIds = state.posts.map((post) => post.id)
    commit(types.SET_SELECTED_POSTS, allPostIds)
    commit(types.SET_SELECT_ALL_STATE, true)
  }
}

export const selectPost = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECTED_POSTS, data)
  if (state.selectedPosts.length === state.posts.length) {
    commit(types.SET_SELECT_ALL_STATE, true)
  } else {
    commit(types.SET_SELECT_ALL_STATE, false)
  }
}
