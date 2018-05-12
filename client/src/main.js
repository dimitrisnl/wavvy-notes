import Vue       from 'vue'
import ElementUI from 'element-ui'
import locale    from 'element-ui/lib/locale/lang/en'
import { sync }  from 'vuex-router-sync'

import Root       from './Root'
import store      from './store'
import router     from './router'
import httpPlugin from './plugins/http'
import eventbus   from './plugins/eventbus'

import 'element-ui/lib/theme-chalk/index.css'

Vue.use(ElementUI, { locale })
Vue.use(httpPlugin, { store, router })
Vue.use(eventbus)

sync(store, router)

/* eslint-disable no-new */
new Vue({
  store,
  router,
  el: '#app',
  render: h => h(Root),
})