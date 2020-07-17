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
          :bool_columns="table_bool_columns"
          @action="onClickAction"
        ></common-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import CommonTable from '../components/table/CommonTable'
import { getUsers, updateUser } from '../api/user'

export default {
  name: 'Users',
  components: { CommonTable },
  data () {
    return {
      current_item: null,
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '名称', value: 'nickname', align: 'left', sortable: true },
        { text: '头像', value: 'avatar', align: 'center', width: 100, sortable: false},
        { text: 'type', value: 'type', align: 'center', sortable: false},
        { text: '上次登录ip', value: 'last_login_ip', align: 'center', sortable: false},
        { text: '黑名单', value: 'is_black', align: 'center', width: 50, sortable: false},
      ],
      table_avatar_columns: ['item.avatar'],
      table_bool_columns: ['item.is_black'],
      table_api: getUsers,
      table_filters: [
        {
          column: 'nickname',
          title: '名称',
          value: '',
          type: 'text',
          options: [],
          compare: 'like',
          sign: 'where',
        }
      ]
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
    onClickAction (params) {
     if (params.sign === 'is_black') {
        let dt = {}
       dt[params.sign] = params.item[params.sign] ? 1 : 0
        updateUser(params.item.id, dt).then(response => {
          this.$store.commit('setSnackbar', {
            message: response.data,
            color: 'success',
            timeout: 1500,
            show: true
          })
        })
      }
    }
  },
}
</script>

<style scoped>

</style>
