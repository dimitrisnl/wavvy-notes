<script>
  export default {
    name: 'Dashboard',
    mounted() {
      this.$router.push({ name: 'notes.index' })
    }
  }
</script>

<template>
  <div></div>
</template>

<style scoped>
  .cover-container {
    margin-top: 70px;
    text-align: center;
  }
</style>
