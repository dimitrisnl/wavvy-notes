<script>
  import { mapActions, mapState } from 'vuex'
  import moment from 'moment'

  export default {
    name: 'NotesForm',

    data() {
      return { note: { id: 0, category: -1, name: '' } }
    },

    mounted() {
      this.changeView()
    },

    watch: {
      $route: 'changeView',
    },

    computed: {
      ...mapState({
        categories: state => state.Categories.full_list,
      }),
      currentCategory() {
        return this.$route.query.category
      },
      isEditing() {
        return this.note.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.note.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill note name'] })
          return false
        }
        return true
      },
    },

    filters: {
      friendly_date: function (value) {
        return !!value ? moment.utc(value).local().format('DD/MM/YYYY HH:mm') : ''
      }
    },

    methods: {
      ...mapActions(['categoriesSetData', 'setFetching', 'resetMessages', 'setMessage']),
      changeView() {

        if (this.$route.params.id)
          this.fetch()
        else{
          this.reset()
          this.$refs.title.focus()
        }
      },
      fetch() {
        const id = this.$route.params.id

        if (id !== undefined) {
          this.setFetching({ fetching: true })
          this.$http.get(`notes/${id}`).then((res) => {

            const { id: _id, name, category, body, updated_at } = res.data.data
            this.note.name = name
            this.note.id = _id
            this.note.category = category.data.id
            this.note.body = body
            this.note.updated_at = updated_at.date

            this.setFetching({ fetching: false })
            this.$refs.textarea.focus()
          })
        }
      },
      submit() {
        if (this.isValid) {
          this.setFetching({ fetching: true })

          if (this.isEditing) {
            this.update()
          } else {
            this.save()
          }
        }
      },
      save() {
        this.$http.post('notes', this.note).then(() => {

          this.$bus.$emit('note.created')
          this.setFetching({ fetching: false })
          this.setMessage({ type: 'success', message: 'New note was created' })
          this.reset()
        })
      },
      update() {
        this.$http.put(`notes/${this.note.id}`, this.note).then(() => {

          this.$bus.$emit('note.updated')
          this.setFetching({ fetching: false })
          this.setMessage({ type: 'success', message: 'Note was updated' })
        })
      },

      remove(note) {
        this.note.showPopup = false
        this.$http.delete(`notes/${note.id}`).then(() => {
          this.$bus.$emit('note.updated')
          this.$router.push({ name: 'notes.new', query: { page: 1, category: this.currentCategory } })
        })
        .catch((error) => {
        })
      },
      reset() {
        this.note.id = 0
        this.note.name = ''
        this.note.body = ''

        if (this.currentCategory){
          this.note.category = this.currentCategory
        }
        else{
          this.note.category = this.categories[0].id
        }

      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="editor-interface">

    <div class="header">
      <div class="row ">
        <div class="col-8">
          <input ref="title" type="text" id="name" class="col" 
            placeholder='Note Title' v-model="note.name" tabindex="1">
        </div>
        <div class="col-4">
          <select name="category" id="category" class="col" v-model="note.category">
            <option v-for="category in categories" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class='editor'>
      <textarea ref="textarea" class='textarea' v-model="note.body" 
      placeholder="Your note" tabindex="2"></textarea>
    </div>
    <div class='footer'>
      <div class='meta'>
          <div class='updated' v-show='isEditing'>Last Updated: {{note.updated_at ? note.updated_at : '' | friendly_date}} </div>
      </div>
      <div class='controls'>
        <el-popover
          ref="deleteNote"
          placement="top"
          v-model="note.showPopup">
          <p>Are you sure to delete this note?</p>
          <div style="text-align: right; margin: 0">
            <el-button size="mini" type="text" @click="note.showPopup = false">Cancel</el-button>
            <el-button type="danger" size="mini" @click="remove(note)">Yes, Delete</el-button>
          </div>
        </el-popover>

        <el-button v-show='isEditing' size="large" class='danger' type='text' v-popover:deleteNote>Delete</el-button>
        <el-button size="large" native-type='submit'>Save</el-button>
      </div>
    </div>
  </form>
</template>

<style scoped lang='sass'>

.editor-interface
  height: 100%
  display: flex
  flex-direction: column

  .header
    margin-bottom: 1.6rem

  .editor
    flex: 1
    margin-bottom: 0.8rem
    .textarea
      height: 100%
      width: 100%
      resize: none
      padding: 1.6rem
      border: 1px solid #efefef

  .footer
    display: flex
    align-items: center

    .meta
      flex: 1

      .updated
        font-size: 0.8rem

    .controls

      .danger
        color: #fa5555
        margin-right: 1.6rem

</style>
