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
    <v-dialog v-model="show_dialog" :width="$vuetify.breakpoint.mdAndUp ? '50%' : '90%'">
      <tag-create-and-edit :current_item="current_item" @onCancel="onDialogCancel" @onSubmit="onDialogSubmit"></tag-create-and-edit>
    </v-dialog>
  </div>
</template>

<script>
import { createTag, deleteTag, editTag, getTags } from '../api/index'
import CommonTable from '../components/table/CommonTable'
import TagCreateAndEdit from './TagCreateAndEdit'

export default {
  name: 'Tags',
  components: { TagCreateAndEdit, CommonTable },
  data () {
    return {
      show_dialog: false,
      current_item: {},
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '名称', value: 'name', align: 'left', sortable: true },
        { text: '使用次数', value: 'articles_count', align: 'left', sortable: false },
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
          tip: '编辑标签',
          icon: 'pencil',
        },
        {
          sign: 'delete',
          text: '删除',
          tip: '删除标签',
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
        // this.$router.push({
        //   name: 'tags-create',
        // })
        this.show_dialog = true
      }
    },
    onClickAction (params) {
      this.current_item = params.item
      if (params.sign === 'edit') {
        this.show_dialog = true
      }else if (params.sign === 'delete') {
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
    onDialogCancel() {
      this.current_item = {}
      this.show_dialog = false
    },
    onDialogSubmit(item) {
      if (item.id) {
        editTag(item.id, item).then(() => {
          this.$store.commit('setSnackbar', {
            message: '操作成功',
            color: 'success',
            timeout: 1500,
            show: true
          })
          this.$nextTick(() => {
            this.show_dialog = false
            this.$refs.custom_table.init()
            this.current_item = {}
          })
        })
      }else {
        createTag(item).then(() => {
          this.$store.commit('setSnackbar', {
            message: '操作成功',
            color: 'success',
            timeout: 1500,
            show: true
          })
          this.$nextTick(() => {
            this.show_dialog = false
            this.$refs.custom_table.init()
            this.current_item = {}
          })
        })
      }
    }
  },
}
</script>

<style scoped>

</style>
