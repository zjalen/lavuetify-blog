<template>
  <div>
    <v-row class="mx-0">
      <v-col cols="12">
        <common-table
          ref="custom_table"
          :headers="table_headers"
          :api="table_api"
          :filters="table_filters"
          :bool_columns="table_bool_columns"
          :actions="table_actions"
          :header_actions="table_head_actions"
          @headerAction="onHeaderAction"
          @action="onClickAction"
        ></common-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { getCategories, deleteCategory, editCategory } from '../api/index'
import CommonTable from '../components/table/CommonTable'

export default {
  name: 'Categories',
  components: { CommonTable },
  data () {
    return {
      current_item: null,
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '名称', value: 'name', align: 'left', sortable: true },
        { text: '父级', value: 'parent_id', align: 'left', sortable: true},
        { text: '排序', value: 'order', align: 'left', sortable: true },
        { text: '等级', value: 'level', align: 'left', sortable: true },
        { text: '显示栏目', value: 'show_as_menu', align: 'left', sortable: true },
        { text: '操作', value: 'action', align: 'center', sortable: false },
      ],
      table_bool_columns: [
        'item.show_as_menu'
      ],
      table_api: getCategories,
      table_filters: [
        {
          column: 'name',
          title: '名称',
          value: '',
          type: 'text',
          options: [],
          compare: 'like',
          sign: 'where',
        }
      ],
      table_head_actions: [
        {
          sign: 'create',
          text: '添加分类',
          tip: '添加新分类',
          icon: 'plus',
        },
      ],
      table_actions: [
        {
          sign: 'edit',
          text: '编辑',
          tip: '编辑分类',
          icon: 'pencil',
        },
        {
          sign: 'delete',
          text: '删除',
          tip: '删除评论',
          icon: 'delete',
        },
      ],
    }
  },
  mounted () {
    this.initData()
    this.$VM.$on('onDialogConfirm', this.onDialogConfirm)
  },
  beforeDestroy() {
    this.$VM.$off('onDialogConfirm', this.onDialogConfirm)
  },
  methods: {
    initData () {
    },
    onHeaderAction(params) {
      if (params.sign === 'create') {
        this.$router.push({
          name: 'categories-create',
        })
      }
    },
    onClickAction (params) {
      if (params.sign === 'edit') {
        this.$router.push({
          name: 'categories-edit',
          params: {
            id: params.item.id
          },
        })
      }else if (params.sign === 'delete') {
        this.current_item = params.item
        this.dialog = {
          show: true,
          title: '删除分类',
          text: '确定删除删除分类吗？',
          sign: 'deleteCategory'
        }
        this.$store.commit('setDialog', this.dialog)
      }else if (params.sign === 'show_as_menu') {
        let param = {}
        param[params.sign] = params.item[params.sign] ? 1 : 0
        editCategory(params.item.id, param).then(response => {
          this.$store.commit('setSnackbar', {
            message: response.data,
            color: 'success',
            timeout: 1500,
            show: true
          })
        })
      }
    },
    onDialogConfirm(sign) {
      this.dialog.show = false
      this.$store.commit('setDialog', this.dialog)
      if (sign === 'deleteCategory') {
        deleteCategory(this.current_item.id).then(() => {
          this.$store.commit('setSnackbar', {
            message: '删除成功',
            color: 'success',
            timeout: 1500,
            show: true
          })
          this.$nextTick(() => {
            this.$refs.custom_table.init()
          })
        })
      }
    },
  },
}
</script>

<style scoped>

</style>
