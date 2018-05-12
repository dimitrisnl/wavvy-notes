import { http, setToken as httpSetToken } from 'plugins/http'
import { isEmpty } from 'lodash'
import { getData } from 'utils/get'
import * as TYPES from './mutations-types'

export const attemptLogin = ({ dispatch }, { email, password }) => http.post('/login', { email, password })
    .then(getData) 
    .then(({ token, user }) => {
      dispatch('setUser', user)
      dispatch('setToken', token)

      return user
    })

export const attemptRegister = ({ dispatch }, { username, email, password }) => http.post('/register', { username, email, password })
    .then(getData)
    .then(({ token, user }) => {
      dispatch('setUser', user)
      dispatch('setToken', token)
      
      return user
    })    

export const logout = ({ dispatch }) => {
  http.post('/auth/token/revoke')
  return Promise.all([
    dispatch('setToken', null),
    dispatch('setUser', {}),
  ])
}

export const setUser = ({ commit }, user) => {
  commit(TYPES.SET_USER, user)

  Promise.resolve(user)
}

export const setToken = ({ commit }, payload) => {

  const token = (isEmpty(payload)) ? null : payload.token || payload

  httpSetToken(token)

  commit(TYPES.SET_TOKEN, token)

  return Promise.resolve(token)
}
