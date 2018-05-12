
<script>
  import { mapState, mapActions } from 'vuex'
  import { isEmpty } from 'lodash'

  export default {
    computed: {
      ...mapState(['messages']),
      hasSuccessMessage() {
        return this.messages.success !== ''
      },
      hasErrorMessages() {
        return this.messages.error.length > 0
      },
      hasValidationMessages() {
        return !isEmpty(this.messages.validation)
      },
    },
    watch: {
      hasSuccessMessage: function (val) {
        if (val){
          this.$message({
            message: this.messages.success,
            type: 'success'
          });
          this.dismiss('success')
        }
       },
      hasErrorMessages: function (val) {
        if (val)
          this.$message({
            message: this.messages.error,
            type: 'error'
          });
          this.dismiss('error')
       },
      hasValidationMessages: function (val) {
        if (val)
          this.$message({
            message: this.messages.validation.messages[0],
            type: 'error'
          });
          this.dismiss('validation')
       }
    },
    methods: {
      ...mapActions(['setMessage']),
      dismiss(type) {
        let obj
        if (type === 'error') {
          obj = { type, message: [] }
        } else if (type === 'validation') {
          obj = { type, message: {} }
        } else {
          obj = { type, message: '' }
        }
        this.setMessage(obj)
      },
    },
  }
</script>

<template>
  <div>
  </div>
</template>
