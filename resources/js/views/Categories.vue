<template>
  <div>
    <v-row class="mx-0">
      <v-col cols="12" :md="6">
        <v-card>
          <v-card-title class="primary white--text">
            文章分类
            <v-spacer/>
            <v-btn @click="createCategory(null)">添加</v-btn>
          </v-card-title>
          <v-treeview
            :items="categories"
            :active="activeCategories"
            activatable
            return-object
            open-all
            @update:active="onActiveChange"
          >
            <template v-slot:append="{ item }">
              <v-btn v-if="item.level === 1" icon @click.stop="createCategory(item.id)">
                <v-icon>
                  mdi-plus
                </v-icon>
              </v-btn>
            </template>
          </v-treeview>
        </v-card>
      </v-col>
      <v-col cols="12" :md="6">
        <v-card v-if="current_item">
          <v-card-title class="secondary white--text">
            编辑内容
          </v-card-title>
          <v-card-text class="mt-4">
            <v-form>
              <v-select
                label="父级"
                hint="留空时为一级分类"
                outlined
                :items="superCategories"
                item-text="name"
                item-value="id"
                clearable
                v-model="current_item.parent_id"
              ></v-select>
              <v-text-field
                label="名称"
                outlined
                v-model="current_item.name"
              ></v-text-field>
              <v-text-field
                label="排序"
                hint="输入数字越小，排序越靠前"
                type="number"
                outlined
                v-model="current_item.order"
              ></v-text-field>
              <v-switch v-model="current_item.show_as_menu" label="前端菜单显示/隐藏" inset></v-switch>
            </v-form>
          </v-card-text>
          <v-card-actions class="pa-4">
            <v-spacer/>
            <v-btn class="error" @click="onDeleteCategory">删除</v-btn>
            <v-btn class="primary" @click="onSaveCategory">保存</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { getCategories, deleteCategory, updateCategory, createCategory } from '../api/category'

export default {
  name: 'Categories',
  data () {
    return {
      categories: [],
      current_item: null,
      activeCategories: []
    }
  },
  computed: {
    superCategories () {
      return this.categories.filter(val => {
        return this.current_item && val.id !== this.current_item.id
      })
    },
  },
  mounted () {
    this.initData()
    this.$VM.$on('onDialogConfirm', this.onDialogConfirm)
  },
  beforeDestroy () {
    this.$VM.$off('onDialogConfirm', this.onDialogConfirm)
  },
  methods: {
    initData () {
      getCategories().then(response => {
        this.categories = response.data.items
      })
    },
    onDialogConfirm (sign) {
      this.dialog.show = false
      this.$store.commit('setDialog', this.dialog)
      if (sign === 'deleteCategory') {
        deleteCategory(this.current_item.id).then(() => {
          this.$store.commit('setSnackbar', {
            message: '删除成功',
            color: 'success',
            timeout: 1500,
            show: true,
          })
          this.$nextTick(() => {
            this.initData()
          })
        })
      }
    },
    onActiveChange (items) {
      if (items.length > 0) {
        this.current_item = items[0]
      }
      else {
        this.current_item = null
      }
    },
    createCategory (parent_id = null) {
      this.activeCategories = []
      this.$nextTick(() => {
        this.current_item = {
          name: '',
          parent_id: parent_id,
          order: '',
        }
      })
    },
    onDeleteCategory() {
      if (this.current_item && this.current_item.id) {
        this.dialog = {
          show: true,
          title: '删除分类',
          text: '确定删除删除分类吗？',
          sign: 'deleteCategory',
        }
        this.$store.commit('setDialog', this.dialog)
      }
    },
    onSaveCategory() {
      if (this.current_item) {
        this.current_item.show_as_menu = this.current_item.show_as_menu ? 1 : 0
        if (!this.current_item.id) {
          this.current_item.level = 1
          createCategory(this.current_item).then(() => {
            this.$store.commit('setSnackbar', {
              message: '创建成功',
              color: 'success',
              timeout: 1500,
              show: true,
            })
            this.$nextTick(() => {
              this.initData()
            })
          })
        }else {
          updateCategory(this.current_item.id, this.current_item).then(() => {
            this.$store.commit('setSnackbar', {
              message: '保存成功',
              color: 'success',
              timeout: 1500,
              show: true,
            })
            this.$nextTick(() => {
              this.initData()
            })
          })
        }
      }
    }
  },
}
</script>

<style scoped>

</style>
