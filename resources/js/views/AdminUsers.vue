<template>
  <div>
    <v-row class="mx-0">
      <v-col cols="12">
        <common-table
          ref="custom_table"
          :headers="table_headers"
          :api="table_api"
          :filters="table_filters"
          :avatar_columns="table_avatar_columns"
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
import CommonTable from '../components/table/CommonTable'
import { deleteAdminUser, getAdminUsers } from '../api/admin-user'

export default {
  name: 'AdminUsers',
  components: { CommonTable },
  data () {
    return {
      current_item: null,
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '名称', value: 'name', align: 'left', sortable: true },
        { text: '头像', value: 'avatar_full_path', align: 'center', width: 100, sortable: false},
        { text: '用户名', value: 'username', align: 'center', sortable: false},
        { text: '操作', value: 'action', align: 'center', width: 100, sortable: false},
      ],
      table_avatar_columns: ['item.avatar_full_path'],
      table_api: getAdminUsers,
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
          text: '添加',
          tip: '添加新用户',
          icon: 'plus',
        },
      ],
      table_actions: [
        {
          sign: 'edit',
          text: '编辑',
          tip: '编辑',
          icon: 'pencil',
        },
        {
          sign: 'delete',
          text: '删除',
          tip: '删除',
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
          name: 'admin-users-create',
        })
      }
    },
    onClickAction (params) {
      if (params.sign === 'edit') {
        this.$router.push({
          name: 'admin-users-edit',
          params: {
            id: params.item.id
          },
        })
      }else if (params.sign === 'delete') {
        this.current_item = params.item
        this.dialog = {
          show: true,
          title: '删除用户',
          text: '删除后将不可恢复，确定吗？',
          sign: 'deleteAdminUser'
        }
        this.$store.commit('setDialog', this.dialog)
      }
    },
    onDialogConfirm(sign) {
      this.dialog.show = false
      this.$store.commit('setDialog', this.dialog)
      if (sign === 'deleteAdminUser') {
        deleteAdminUser(this.current_item.id).then(() => {
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
