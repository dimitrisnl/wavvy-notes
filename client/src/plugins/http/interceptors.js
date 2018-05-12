import { isArray } from 'lodash'

export default (http, store, router) => {

  http.interceptors.response.use(
    response => response,
    (error)  => {

      const { response } = error
      if ([401, 400].indexOf(response.status) > -1) {
        router.push({ name: 'auth.signin' })
      }

      if (isArray(response.data)) {
        store.dispatch('setMessage', { type: 'error', message: response.data.messages })
      } else {
        store.dispatch('setMessage', { type: 'validation', message: response.data })
      }

      store.dispatch('setFetching', { fetching: false })

      return Promise.reject(error)
    }
  )
}
