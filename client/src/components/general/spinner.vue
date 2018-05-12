<script>
  import { mapState } from 'vuex'

  export default {
    computed: {
      ...mapState(['fetching']),
    },
  }
</script>

<template>
  <div class="container">
    <i class="el-icon-loading" v-show='fetching'></i>
  </div>
</template>

<style scoped>
  .container {
    display: inline-block;
    width: 40px;
    color: #2c3e50;
    position: absolute;
    z-index: 1;
    right: 8px;
    top: 18px;
  }
</style>
