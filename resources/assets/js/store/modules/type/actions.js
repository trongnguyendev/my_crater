import * as types from './mutation-types'

export const fetchTypes = ({ commit, dispatch, state }, params) => {
  return new Promise((resolve, reject) => {
    window.axios
      .get(`/api/v1/types`, { params })
      .then((response) => {
        commit(types.BOOTSTRAP_TYPES, response.data.types.data)
        commit(types.SET_TOTAL_TYPES, response.data.typeTotalCount)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const fetchType = ({ commit, dispatch }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .get(`/api/v1/types/${id}`)
      .then((response) => {
        console.log(response)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const addType = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    window.axios
      .post('/api/v1/types', data)
      .then((response) => {
        commit(types.ADD_TYPE, response.data)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const updateType = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    console.log(data.id + "update type");

    window.axios
      .put(`/api/v1/types/${data.id}`, data)
      .then((response) => {
        commit(types.UPDATE_TYPE, response.data)
        resolve(response)
        
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const deleteType = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .post(`/api/v1/types/delete`, id)
      .then((response) => {
        commit(types.DELETE_TYPE, id)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
}

export const deleteMultipleTypes = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios
      .post(`/api/v1/types/delete`, { ids: state.selectedTypes })
      .then((response) => {
        console.log(response)
        commit(types.DELETE_MULTIPLE_TYPES, state.selectedTypes)
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

export const selectAllTypes = ({ commit, dispatch, state }) => {
  if (state.selectedTypes.length === state.types.length) {
    commit(types.SET_SELECTED_TYPES, [])
    commit(types.SET_SELECT_ALL_STATE, false)
  } else {
    let allTypeIds = state.types.map((type) => type.id)
    console.log(allTypeIds)
    commit(types.SET_SELECTED_TYPES, allTypeIds)
    commit(types.SET_SELECT_ALL_STATE, true)
  }
}

export const selectType = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECTED_TYPES, data)
  if (state.selectedTypes.length === state.types.length) {
    commit(types.SET_SELECT_ALL_STATE, true)
  } else {
    commit(types.SET_SELECT_ALL_STATE, false)
  }
}
