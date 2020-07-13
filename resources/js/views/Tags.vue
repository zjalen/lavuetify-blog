<template>
  <div>
    <v-row class="mx-0">
      <v-col cols="12">
        <common-table
          ref="custom_table"
          :headers="table_headers"
          :api="table_api"
          :filters="table_filters"
          :actions="table_actions"
          :chip_columns="table_chip_columns"
          :header_actions="table_head_actions"
          @headerAction="onHeaderAction"
          @action="onClickAction"
        ></common-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { deleteTag, getTags } from '../api/index'
import CommonTable from '../components/table/CommonTable'

export default {
  name: 'Tags',
  components: { CommonTable },
  data () {
    return {
      current_item: null,
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '名称', value: 'name', align: 'left', sortable: true },
        { text: '操作', value: 'action', align: 'center', sortable: false },
      ],
      table_api: getTags,
      table_chip_columns: ['item.name'],
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
          text: '添加标签',
          tip: '添加新标签',
          icon: 'plus',
        },
      ],
      table_actions: [
        {
          sign: 'edit',
          text: '编辑',
          tip: '编辑主题',
          icon: 'pencil',
        },
        {
          sign: 'delete',
          text: '删除',
          tip: '删除主题',
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
          name: 'tags-create',
        })
      }
    },
    onClickAction (params) {
      if (params.sign === 'edit') {
        this.$router.push({
          name: 'tags-edit',
          params: {
            id: params.item.id
          },
        })
      }else if (params.sign === 'delete') {
        this.current_item = params.item
        this.dialog = {
          show: true,
          title: '删除标签',
          text: '只删除主题，关联文章自动解绑，确定删除吗？',
          sign: 'deleteTag'
        }
        this.$store.commit('setDialog', this.dialog)
      }
    },
    onDialogConfirm(sign) {
      this.dialog.show = false
      this.$store.commit('setDialog', this.dialog)
      if (sign === 'deleteTag') {
        deleteTag(this.current_item.id).then(() => {
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
