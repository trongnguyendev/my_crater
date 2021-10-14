import * as types from './mutation-types'

export const fetchComments = ({ commit, dispatch, state }, params) => {
  return new Promise((resolve, reject) => {
    window.axios
      .get(`/api/v1/comments`, { params })
      .then((response) => {
        commit(types.BOOTSTRAP_COMMENTS, response.data.comments.data)
        commit(types.SET_TOTAL_COMMENTS, response.data.totalCommentCount)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const addComment = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    
    window.axios
      .post('/api/v1/comments', data)
      .then((response) => {
        commit(types.ADD_COMMENT, response.data)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const updateComment = ({ commit }, data) => {
  return new Promise((resolve, reject) => {

    window.axios
      .put(`/api/v1/comments/${data.id}`, data)
      .then((response) => {
        commit(types.UPDATE_COMMENT, response.data)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const deleteComment = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .post(`/api/v1/comments/delete`, id)
      .then((response) => {
        commit(types.DELETE_COMMENT, id)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}
