import * as TYPES from '../../store/types'

const state = {
  full_list: [],
}

/* eslint-disable no-param-reassign */
const mutations = {
  [TYPES.CATEGORIES_SET_DATA](st, obj) {
    if (obj.full_list) {
      st.full_list = obj.full_list
    }
  },
}

const actions = {
  categoriesSetData({ commit }, obj) {
    commit(TYPES.CATEGORIES_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
