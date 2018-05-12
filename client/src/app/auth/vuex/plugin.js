import { localStorageSetItem } from 'utils/local'
import * as TYPES from './mutations-types'

const subscribe = (store) => {
  store.subscribe((mutation, { Auth }) => {
    if (TYPES.SET_USER === mutation.type) {
      localStorageSetItem('user', { user: Auth.user })
    }

    if (TYPES.SET_TOKEN === mutation.type) {
      localStorageSetItem('token', { token: Auth.token })
    }
  })
}

export default (store) => {
  subscribe(store)
};
