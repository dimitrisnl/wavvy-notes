import store       from '../store'
import { isEmpty } from 'lodash'
import { localStorageGetItem } from '../utils/local'

const needAuth = (auth, token) => auth !== undefined && auth && isEmpty(token)

const beforeEach = (to, from, next) => {

  store.dispatch('resetMessages')

  let token  = store.state.Auth.token
  const auth = to.meta.requiresAuth

  if (isEmpty(token)) {
    const localStoredToken = localStorageGetItem('token')
    const localStoredUser  = localStorageGetItem('user')

    if (localStoredToken !== undefined &&
        localStoredToken !== null      &&
        localStoredUser  !== undefined &&
        localStoredUser  !== null
      ) {
      token = localStoredToken.token
      store.dispatch('setToken', token)
      store.dispatch('setUser', localStoredUser.user)
    }
  }

  if (!needAuth(auth, token)) {
    next()
  }

  if (needAuth(auth, token)) {
    next({ name: 'auth.signin' })
  }
}

export default beforeEach
