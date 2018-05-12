<script>
import { mapGetters }  from 'vuex'

import Navbar from './components/root/navbar'
import Alerts from './components/general/alerts'

export default {
  name: 'Root',
  components: {
    Navbar,
    Alerts,
  },
  computed: {
    ...mapGetters(['shouldShowNavigation']),
  },
}
</script>

<template>
  <div id="app">
    <navbar v-show="shouldShowNavigation"></navbar>
    <div class="main">
      <alerts></alerts>
      <router-view></router-view>
    </div>
  </div>
</template>

<style lang="sass" src="assets/sass/app.sass"></style>
