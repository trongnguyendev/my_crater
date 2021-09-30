import * as types from './mutation-types'

export const fetchPosts = ({ commit, dispatch, state }, params) => {
  return new Promise((resolve, reject) => {
    window.axios
      .get(`/api/v1/posts`, { params })
      .then((response) => {
        console.log(response)
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
        console.log(response)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const addPost = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    console.log(data)
    window.axios
      .post('/api/v1/posts', data)
      .then((response) => {
        commit(types.ADD_POST, response.data)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

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

export const deletePost = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .post(`/api/v1/posts/delete`, id)
      .then((response) => {
        console.log("deeee")
        console.log(response)
        console.log("deleted")
        commit(types.DELETE_POST, id)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const deleteMultiplePosts = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .post(`/api/v1/posts/delete`, { ids: state.selectedPosts })
      .then((response) => {
        console.log(response)
        commit(types.DELETE_MULTIPLE_POSTS, state.selectedPosts)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const setSelectAllState = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECT_ALL_STATE, data)
}

export const selectAllPosts = ({ commit, dispatch, state }) => {
  if (state.selectedPosts.length === state.posts.length) {
    commit(types.SET_SELECTED_POSTS, [])
    commit(types.SET_SELECT_ALL_STATE, false)
  } else {
    let allPostIds = state.posts.map((post) => post.id)
    console.log(allPostIds)
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
