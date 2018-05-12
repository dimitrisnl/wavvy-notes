import * as TYPES from '../../../store/types'

const state = {
  list: [],
  pagination: {},
}

/* eslint-disable no-param-reassign */
const mutations = {
  [TYPES.NOTES_SET_DATA](st, obj) {
    st.list = obj.list
    st.pagination = obj.pagination
  },
}

const actions = {
  notesSetData({ commit }, obj) {
    commit(TYPES.NOTES_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
