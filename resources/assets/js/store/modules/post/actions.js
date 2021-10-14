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

export const addPost = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {

    let config = {
      headers: { 
        'Content-type': 'multipart/form-data' 
      }
    }
    
    window.axios
      .post('/api/v1/posts', data)
      .then((response) => {
        console.log(data)
        console.log("aaa")
        console.log(response)
        console.log("data")
        commit(types.ADD_POST, response.data)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const updateThumbnail = ({ commit }, data) => {
  return new Promise((resolve, reject)=> {

    let config = {
      headers: {
        'Content-type': 'multipart/form-data'
      }
    }

    window.axios
      .post(`/api/v1/posts/update-thumbnail/${data[1]}`, data[0], config)
      .then((response) => {
        commit(types.UPDATE_THUMBNAIL_POST, response.data)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const updatePost = ({ commit }, data) => {
  return new Promise((resolve, reject) => {

    window.axios
      .put(`/api/v1/posts/${data.id}`, data)
      .then((response) => {
        commit(types.UPDATE_POST, response.data)
        resolve(response)
        
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const deletePost = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .post(`/api/v1/posts/delete`, id)
      .then((response) => {
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
