<script>
  import { mapActions, mapGetters } from 'vuex'
  import spinner from '../general/spinner'

  export default {
    components: {
      spinner,
    },
    computed: {
      ...mapGetters(['currentUser', 'isLogged', 'fetching']),
    },
    watch: {
      isLogged(value) {
        if (value === false) {
          this.$router.push({ name: 'auth.signin' })
        }
      },
    },
    methods: {
      ...mapActions(['logout']),
      /* eslint-disable no-undef */
      navigate(name) {
        switch (name) {
          case 'logout':
            this.logout()
            break;
          default:
            this.$router.push({ name })
            break;
        }
      },
    },
  }
</script>

<template>
  <div>
    <el-menu default-active="notes.index" class="navigation" mode="horizontal"
      @select="navigate">
      <el-menu-item index="notes.index" class='brand'>Wavvy Notes</el-menu-item>
      <el-submenu index="menu-user" class="logout-button">
        <template slot="title">{{ currentUser.username }}</template>
        <el-menu-item index="logout">Logout</el-menu-item>
      </el-submenu>
    </el-menu>
      <spinner></spinner>
  </div>
</template>

<style scoped>
  .navigation {
    padding: 0 1.6rem;
  }
  .brand {
    font-size: 1.2em;
  }
  .logout-button {
    float: right!important;
  }
</style>
