<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'
  import { cloneDeep, debounce } from 'lodash'

  export default {
    name: 'Categories',
    props: ['visible'],

    watch:{
      visible: 'fetch',
      list:    'clone'
    },

    methods: {
      ...mapActions(['categoriesSetData', 'setFetching', 'setMessage']),

      closeModal() {
        this.$bus.$emit('categories.modal.closed')
      },

      fetch() {
        
        if (!this.visible) return false

        this.setFetching({ fetching: true })
        this.$http.get('categories/full-list').then(({ data }) => {
          this.categoriesSetData({
            full_list: data.data
          })
          this.setFetching({ fetching: false })
        })      
      },
      save: debounce(function(category) {

          if (!category.name){
            this.$message({
              message: 'Category name cannot be empty',
              type: 'error'
            });            
            return false
          }

          this.$http.put(`categories/${category.id}`, category).then(() => {
              this.setMessage({ type: 'success', message: 'Category was updated' })
              this.fetch()
            })
            .catch((error) => {})
        }, 250),
      create: debounce(function(category) {
          this.$refs.new_category.disabled = true;
          this.$http.post('categories', { name: this.new_category.name }).then(() => {
              this.setMessage({ type: 'success', message: 'Category created' })
              this.reset()
              this.fetch()
            })
            .catch((error) => {})
        }, 250),
      remove(category) {

        category.showPopup = false;

        if (this.categories.length == 1){
          this.$message({
            message: 'You cannot delete your last category :(',
            type: 'error'
          });
          return false
        }

        this.$http.delete(`categories/${category.id}`).then(() => {
          this.fetch()
          this.$bus.$emit('category.removed')
        })
        .catch((error) => {
        })
      },
      clone(){
        this.categories = cloneDeep(this.list)
      },
      reset(){
        this.new_category.name = null
        this.$refs.new_category.disabled = false;
      }
    },
    computed: {
      ...mapState({
        fetching: state => state.fetching,
        list: state => state.Categories.full_list,
      }),
    },
    data(){
      return { categories: [], new_category: { name: null } }
    }
  }
</script>

<template>


<el-dialog @close='closeModal()' title="Manage Categories" class='manage-categories' :visible.sync="visible">
  <div class='container'>
  <div v-for='category in categories' class='row align-items-center category-wrapper'>
      <input  class='col-11' v-model="category.name" auto-complete="off" @change='save(category)'/>
      <el-popover ref='deleteCategory' placement="top" v-model='category.showPopup' width='250'>
        <div class='confirm-delete-prompt'>Are you sure you want to delete this category? </br>
          Notice that <b>every</b> note included, will be deleted as well.</div>
          <div class='buttons'>
            <el-button size="mini" type="text" @click="category.showPopup = false">Cancel</el-button>
            <el-button class='' type="danger" size="mini" @click="remove(category)">Yes, Delete</el-button>
          </div>
      <i class='el-icon-delete2 col-1' slot="reference" />
      </el-popover>
  </div>
</div>
<div class='add-new-category-header'>Add New Category</div>
<div class='new-category'>
    <input class='col-12' ref='new_category' v-model="new_category.name" placeholder='Category name' auto-complete="off" @change='create(new_category)'/>
</div>

  <span slot="footer" class="dialog-footer">
    <el-button @click="closeModal()">Close</el-button>
  </span>
</el-dialog>

</template>

<style scoped lang='sass'>
  .fade-enter-active, .fade-leave-active
    transition: opacity .7s ease
  
  .fade-enter, .fade-leave-active 
    opacity: 0

  .manage-categories
    .container
      max-height: 35vh
      overflow: auto
      .category-wrapper
        margin-bottom: 1.6rem

  .confirm-delete-prompt
    word-break: break-word
    font-size: 0.8rem
  .buttons
    float: right
    margin-top: 0.8rem
  .add-new-category-header
    margin: 2.4rem 0
    line-height: 24px
    font-size: 18px
    color: #2d2f33


</style>
