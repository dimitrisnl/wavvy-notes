
<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'
  import moment from 'moment'
  import CategoriesModal from '../../categories/form'

  export default {
    name: 'Notes',
    components: {
      CategoriesModal
    },

    methods: {
      edit(id) {
        this.$router.push({
          name: 'notes.edit',
          params: { id },
          query: { page: this.currentPage, category: this.currentCategory } })
      },
      create() {
        this.$router.push({ name: 'notes.new', query: { page: 1, category: this.currentCategory } })
      },

      ...mapActions(['notesSetData', 'setFetching', 'categoriesSetData']),

      filterByCategory(category = null){
        if (category)
          this.$router.push({ name: 'notes.index', query: { page: 1, category: category.id } })
        else
          this.$router.push({ name: 'notes.index', query: { page: 1 } })

        this.fetch()
      },

      fetch() {

        this.setFetching({ fetching: true })

        let params = { page: this.currentPage }

        if (this.currentCategory)
          params.category = this.currentCategory

        this.$http.get(`notes`, {
          params: params
        }).then(({ data }) => {

          this.notesSetData({
            list: data.data,
            pagination: data.meta.pagination,
          })

          this.setFetching({ fetching: false })
        })
      },

      fetchCategories() {
        if (!this.categories.length) {
          this.setFetching({ fetching: true })
          this.$http.get('categories/full-list').then(({ data }) => {

            this.categoriesSetData({
              full_list: data.data,
            })

            this.setFetching({ fetching: false })
          })
        }
      },

      navigate(obj) {

        let params = { page: obj.page }
        if (this.currentCategory)
          params.category = this.currentCategory

        this.$router.push({ name: 'notes.index', query: params })
        Vue.nextTick(() => this.fetch())
      },
      manageCategories() {
        this.show_modal = true
        // this.$router.push({ name: 'categories.index', query: { page: 1 } })
      },
      pageChanged(page){
        this.navigate({page : page})
      }
    },

    filters: {
      friendly_date: function (value) {
        return moment.utc(value).local().format('DD/MM/YYYY')
      },
      summary: function (value){
        return value ? value.substring(0,50) : ''
      }  
    },

    computed: {
      ...mapState({
        fetching: state => state.fetching,
        pagination: state => state.Notes.pagination,
        list: state => state.Notes.list,
        categories: state => state.Categories.full_list,
      }),
      notes() {
        return this.list
      },
      currentNote() {
        return this.$route.params.id
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10) || 1
      },
      currentCategory() {
        return this.$route.query.category
      },
      isFormVisible() {
        return this.$route.name === 'notes.new' || this.$route.name === 'notes.edit'
      },
    },
    /**
    * Right before navigate out to another route
    * clears all event handlers, thus avoiding
    * multiple handlers to be set and the annoying
    * behaviour of multiple AJAX calls being made.
    */
    beforeRouteLeave(to, from, next) {
      
      this.$bus.$off('navigate')
      this.$bus.$off('note.created')
      this.$bus.$off('note.updated')
      this.$bus.$off('category.removed')
      this.$bus.$off('categories.modal.closed')

      next()
    },
    mounted() {

      this.$bus.$on('navigate', obj => this.navigate(obj))
      this.$bus.$on('note.created', () => this.fetch())
      this.$bus.$on('note.updated', () => this.fetch())
      this.$bus.$on('category.removed', () => this.fetch())
      this.$bus.$on('categories.modal.closed', () => this.show_modal = false)

      this.fetchCategories()
      this.fetch()
    },

    updated() {
    },
    data() {
      return {
        show_modal: false
      }
    }
  }
</script>

<template>
  <el-card class="notes-interface">
      <div class='side-list'>
        <el-button class='new-note' @click="create()">New Note</el-button>
        <el-button class='all-notes' type='text' @click="filterByCategory(null)">All Categories</el-button>
        <div class='categories-label' @click="manageCategories()">Categories <i class='category-icon el-icon-edit' /></div>
        <div class='categories-wrap'>
          <el-button v-for="category in categories" :class="{'active' : currentCategory == category.id}" size='small' type="text" 
            class='category-entry' @click="filterByCategory(category)"> {{ category.name }} 
          </el-button>
        </div>
      </div>
      <div class='available-notes'>
        <ul class='list-unstyled  notes-list'>
          <li v-for="note in notes" class='note-entry' 
            @click.prevent="edit(note.id)" :class="{'active' : currentNote == note.id}">
            <div class='header'>
              <div class='title'>{{ note.name }}</div>
              <div class='date'>{{ note.created_at.date | friendly_date }}</div>
            </div>
            <div class='summary' v-if='!!note.body'>
              {{note.body | summary}}
            </div>
          </li>
          <li class='empty-state' v-show='notes.length == 0'>Nothing in this category yet :(</li>
        </ul>
          <div class='pagination' v-show='notes.length > 0 && pagination.total_pages > 1'>
          <el-pagination
            small
            layout="prev, pager, next"
            :total="pagination.total"
            :page-size='pagination.per_page'
            @current-change='pageChanged'>
          </el-pagination>
        </div>
      </div>
      <div class='notes-editor'>
        <transition name="fade">
          <router-view></router-view>
        </transition>
      </div>
      <div>
    </div>
    <categories-modal :visible='show_modal'></categories-modal>
    </el-card>


</template>

<style scoped lang='sass'>

    .fade-enter-active, .fade-leave-active 
      transition: opacity .7s ease
    .fade-enter, .fade-leave-active 
      opacity: 0

    .notes-interface
      display: flex
      height: 100%
      border-radius: 3px
      padding: 2.4rem
      border-bottom: 2px solid #409EFF

      .notes-editor
        flex: 0 0 60%
        padding-left: 2.4rem

      .side-list
        padding: 0 1.6rem
        flex: 0 0 15%

        .new-note
          margin-bottom: 1.6rem
          width: 100%

        .all-notes
          margin-left: 0
          margin-bottom: 0.8rem
          font-size: 1.1em

        .categories-wrap
          height: 50vh
          overflow: auto
          .category-entry
            width: 100%
            margin: 0 0 0.4rem 0
            text-align: left
            color: inherit

            &.active
              padding-left: 0.8rem
              border-left: 2px solid #409EFF
              border-radius: 0
        
        .categories-label
          font-size: 1.1rem
          margin-bottom: 0.8rem
          display: flex
          align-items: center
        
          .category-icon
            cursor: pointer
            transition: all 0.2s ease-in
            margin-left: 0.8rem
            padding: 5px
            border-radius: 100%
            border: 1px solid #409EFF
            color: #409EFF
            font-size: 0.6rem

            &:active
              transform: scale(0.9)

      .available-notes
        flex: 0 0 25%
        display: flex
        flex-direction: column
        height: 100%
        
      .pagination
        display: flex
        justify-content: center
  
      .notes-list
        height: 100%
        margin-bottom: 1.6rem
        overflow: auto

        .empty-state
          height: 100%
          display: flex
          align-items: center
          justify-content: center
  
        .note-entry
          cursor: pointer
          padding: 0.8rem
          transition: background 0.3s
          border-bottom: 1px solid #efefef
          border-left: 2px solid transparent

          &.active
            border-left: 2px solid #409EFF

          &:hover
            background: #f9f9f9
  
          .header
            display: flex
            align-items: flex-start
  
            .title
              flex: 1
              margin-right: 1.6rem
              font-weight: 400
              font-size: 1rem
              word-break: break-word
  
            .date
              display: flex
              justify-content: flex-end
              font-size: 0.8rem
              position: relative
              top: 3px
  
          .summary
            display: flex
            margin-top: 0.8rem
            font-style: italic
            font-size: 0.8rem
    
</style>
