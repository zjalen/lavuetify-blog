<template>
  <div>
    <v-row class="mx-0">
      <v-col cols="12">
        <common-table
          ref="custom_table"
          :headers="table_headers"
          :header_actions="table_head_actions"
          :api="table_api"
          :actions="table_actions"
          @headerAction="onHeaderAction"
          @action="onClickAction"
        ></common-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>

import { getPages, deletePage, updatePage } from '../api/page'
import CommonTable from '../components/table/CommonTable'

export default {
  name: 'Pages',
  components: { CommonTable },
  data () {
    return {
      current_item: null,
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '标题', value: 'title', align: 'left', sortable: true },
        { text: '名称', value: 'name', align: 'left', sortable: true},
        { text: '链接', value: 'url', align: 'left', sortable: true },
        { text: '操作', value: 'action', align: 'center', sortable: false },
      ],
      table_api: getPages,
      table_filters: [
        {
          column: 'title',
          title: '标题',
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
          text: '创建',
          tip: '创建新页面',
          icon: 'plus',
        },
      ],
      table_actions: [
        {
          sign: 'edit',
          text: '编辑',
          tip: '编辑页面',
          icon: 'pencil',
        },
        {
          sign: 'delete',
          text: '删除',
          tip: '删除页面',
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
          name: 'pages-create',
        })
      }
    },
    onClickAction (params) {
      if (params.sign === 'edit') {
        this.$router.push({
          name: 'pages-edit',
          params: {
            id: params.item.id
          },
        })
      }else if (params.sign === 'delete') {
        this.current_item = params.item
        this.dialog = {
          show: true,
          title: '删除页面',
          text: '删除后将不可恢复，确定吗？',
          sign: 'deletePage'
        }
        this.$store.commit('setDialog', this.dialog)
      }else if (params.sign === 'is_shown') {
        let param = {}
        param[params.sign] = params.item[params.sign] ? 1 : 0
        param['_method'] = 'PUT'
        updatePage(params.item.id, param).then(response => {
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
      if (sign === 'deletePage') {
        deletePage(this.current_item.id).then(() => {
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
